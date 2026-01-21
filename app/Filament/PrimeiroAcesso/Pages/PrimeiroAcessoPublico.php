<?php

declare(strict_types=1);

namespace App\Filament\PrimeiroAcesso\Pages;

use App\Services\PrimeiroAcessoService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class PrimeiroAcessoPublico extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Primeiro Acesso';

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return moduleEnabled('primeiro_acesso_filament');
    }

    protected string $view = 'filament.primeiro-acesso.pages.primeiro-acesso-publico';

    public ?array $data = [];

    public int $step = 1;

    public ?array $colaboradorResumo = null;

    public ?string $status = null;

    public ?string $emailAcao = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Etapa 1 — CPF e Data de Nascimento')
                    ->schema([
                        TextInput::make('cpf')
                            ->label('CPF')
                            ->required(),
                        DatePicker::make('data_nascimento')
                            ->label('Data de Nascimento')
                            ->required(),
                    ])
                    ->visible(fn() => $this->step === 1),

                Section::make('Etapa 2 — Confirmação mínima')
                    ->schema([
                        Placeholder::make('nome')
                            ->label('Nome')
                            ->content(fn() => $this->colaboradorResumo['nome'] ?? '-'),
                        Placeholder::make('empresa')
                            ->label('Empresa')
                            ->content(fn() => $this->colaboradorResumo['empresa_nome'] ?? '-'),
                        Placeholder::make('unidade')
                            ->label('Unidade')
                            ->content(fn() => $this->colaboradorResumo['unidade_nome'] ?? '-'),
                        Placeholder::make('email_mask')
                            ->label('Email')
                            ->content(fn() => $this->colaboradorResumo['email_mask'] ?? 'Não informado'),
                    ])
                    ->columns(2)
                    ->visible(fn() => $this->step === 2),

                Section::make('Etapa 3 — Email')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('email_confirmacao')
                            ->label('Confirmar email')
                            ->email()
                            ->required(),
                    ])
                    ->columns(2)
                    ->visible(fn() => $this->step === 3),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        if ($this->step === 1) {
            return [
                Action::make('validar')
                    ->label('Continuar')
                    ->action(fn() => $this->validarCpf()),
            ];
        }

        if ($this->step === 2) {
            $actions = [];

            if (($this->colaboradorResumo['email_mask'] ?? null) !== null) {
                $actions[] = Action::make('enviar_existente')
                    ->label('Enviar acesso')
                    ->action(fn() => $this->enviarEmailExistente());

                $actions[] = Action::make('corrigir_email')
                    ->label('Corrigir email')
                    ->action(function () {
                        $this->emailAcao = 'corrigir_email';
                        $this->step = 3;
                    });
            } else {
                $actions[] = Action::make('informar_email')
                    ->label('Informar email')
                    ->action(function () {
                        $this->emailAcao = 'informar_novo';
                        $this->step = 3;
                    });
            }

            return $actions;
        }

        if ($this->step === 3) {
            return [
                Action::make('enviar')
                    ->label('Enviar acesso')
                    ->action(fn() => $this->enviarEmailNovoOuCorrigir()),
            ];
        }

        return [];
    }

    private function validarCpf(): void
    {
        $state = $this->form->getState();

        $resultado = app(PrimeiroAcessoService::class)->iniciar(
            $state['cpf'] ?? '',
            $state['data_nascimento'] ?? '',
            request()->ip(),
            request()->userAgent()
        );

        if ($resultado['status'] === 'colaborador_nao_encontrado') {
            Notification::make()
                ->title('Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.')
                ->danger()
                ->send();
            return;
        }

        $this->colaboradorResumo = $resultado['colaborador'] ?? [];
        $this->status = $resultado['status'];
        $this->step = 2;
    }

    private function enviarEmailExistente(): void
    {
        $state = $this->form->getState();
        $service = app(PrimeiroAcessoService::class);
        $colaborador = $service->resolverColaborador($state['cpf'] ?? '', $state['data_nascimento'] ?? '');

        if (! $colaborador) {
            Notification::make()
                ->title('Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.')
                ->danger()
                ->send();
            return;
        }

        $service->enviarAcessoParaEmailExistente($colaborador, request()->ip(), request()->userAgent());

        Notification::make()
            ->title('Se os dados estiverem corretos, o acesso será enviado ao email cadastrado.')
            ->success()
            ->send();
    }

    private function enviarEmailNovoOuCorrigir(): void
    {
        $state = $this->form->getState();
        $service = app(PrimeiroAcessoService::class);
        $colaborador = $service->resolverColaborador($state['cpf'] ?? '', $state['data_nascimento'] ?? '');

        if (! $colaborador) {
            Notification::make()
                ->title('Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.')
                ->danger()
                ->send();
            return;
        }

        if (($state['email'] ?? null) !== ($state['email_confirmacao'] ?? null)) {
            Notification::make()
                ->title('Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.')
                ->danger()
                ->send();
            return;
        }

        if ($this->emailAcao === 'corrigir_email') {
            $service->registrarEmailCorrigido($colaborador, $state['email'] ?? '', null, null, request()->ip(), request()->userAgent());

            Notification::make()
                ->title('Solicitação registrada. O RH analisará o ajuste.')
                ->success()
                ->send();

            $this->step = 2;
            return;
        }

        $status = $service->coletarEmailNovo($colaborador, $state['email'] ?? '', request()->ip(), request()->userAgent());

        Notification::make()
            ->title($status === 'bloqueado'
                ? 'Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.'
                : 'Se os dados estiverem corretos, o acesso será enviado ao email informado.')
            ->success($status !== 'bloqueado')
            ->danger($status === 'bloqueado')
            ->send();

        $this->step = 2;
    }
}
