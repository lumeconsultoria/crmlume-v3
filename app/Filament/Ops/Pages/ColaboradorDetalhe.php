<?php

namespace App\Filament\Ops\Pages;

use App\Models\Colaborador;
use App\Models\Solicitacao;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ColaboradorDetalhe extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected static bool $shouldRegisterNavigation = false;

    public ?Colaborador $colaborador = null;

    public function getView(): string
    {
        return 'filament.ops.pages.colaborador-detalhe';
    }

    public function mount(int $record): void
    {
        $this->colaborador = Colaborador::with([
            'funcao.setor',
            'unidade',
            'empresa.grupo',
            'user',
        ])->findOrFail($record);

        $user = Auth::user();
        if (! $user || ! userCanAccessColaborador($user, $this->colaborador)) {
            redirect()->to('/ops/registro-pontos');
        }
    }

    public function getTitle(): string
    {
        return $this->colaborador?->nome ?? 'Detalhes do Colaborador';
    }

    public function colaboradorInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->colaborador)
            ->schema([
                Section::make('Informações Pessoais')
                    ->schema([
                        TextEntry::make('nome')
                            ->label('Nome Completo'),

                        TextEntry::make('ativo')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state ? 'Ativo' : 'Inativo'),
                    ])
                    ->columns(2),

                Section::make('Estrutura Organizacional')
                    ->schema([
                        TextEntry::make('empresa.grupo.nome')
                            ->label('Grupo'),

                        TextEntry::make('empresa.nome')
                            ->label('Empresa'),

                        TextEntry::make('unidade.nome')
                            ->label('Unidade'),

                        TextEntry::make('funcao.setor.nome')
                            ->label('Setor'),

                        TextEntry::make('funcao.nome')
                            ->label('Função'),
                    ])
                    ->columns(2),

                Section::make('Informações do Sistema')
                    ->schema([
                        TextEntry::make('user.email')
                            ->label('E-mail de Acesso')
                            ->default('Sem acesso ao sistema'),

                        TextEntry::make('created_at')
                            ->label('Cadastrado em')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Última atualização')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('solicitar')
                ->label('Solicitar Alteração')
                ->icon('heroicon-o-pencil-square')
                ->form([
                    Select::make('tipo')
                        ->label('Tipo de Solicitação')
                        ->options([
                            'alteracao_dados' => 'Alteração de Dados',
                            'alteracao_funcao' => 'Alteração de Função',
                            'alteracao_unidade' => 'Alteração de Unidade',
                            'desligamento' => 'Desligamento',
                            'reativacao' => 'Reativação',
                            'outros' => 'Outros',
                        ])
                        ->required(),

                    Textarea::make('descricao')
                        ->label('Descrição')
                        ->required()
                        ->rows(4)
                        ->placeholder('Descreva a alteração solicitada...'),
                ])
                ->action(function (array $data) {
                    Solicitacao::create([
                        'colaborador_id' => $this->colaborador->id,
                        'solicitante_id' => Auth::id(),
                        'tipo' => $data['tipo'],
                        'descricao' => $data['descricao'],
                        'status' => 'pendente',
                    ]);

                    Notification::make()
                        ->title('Solicitação criada com sucesso')
                        ->success()
                        ->send();

                    // Notificar usuários RH
                    $this->notifyRH($data);
                }),

            Action::make('voltar')
                ->label('Voltar')
                ->icon('heroicon-o-arrow-left')
                ->url(Colaboradores::getUrl()),
        ];
    }

    protected function notifyRH(array $data): void
    {
        $rhUsers = \App\Models\User::role('rh')->get();

        foreach ($rhUsers as $user) {
            Notification::make()
                ->title('Nova solicitação de alteração')
                ->body("Colaborador: {$this->colaborador->nome} - Tipo: {$data['tipo']}")
                ->success()
                ->sendToDatabase($user);
        }
    }
}
