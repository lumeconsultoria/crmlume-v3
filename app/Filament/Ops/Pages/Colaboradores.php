<?php

namespace App\Filament\Ops\Pages;

use App\Models\Colaborador;
use App\Models\Solicitacao;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Colaboradores extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $view = 'filament.ops.pages.colaboradores';

    protected static ?string $navigationGroup = 'Operacional';

    protected static ?string $navigationLabel = 'Colaboradores';

    protected static ?int $navigationSort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(Colaborador::query()->with(['funcao', 'unidade', 'empresa']))
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('empresa.nome')
                    ->label('Empresa')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unidade.nome')
                    ->label('Unidade')
                    ->sortable(),

                Tables\Columns\TextColumn::make('funcao.nome')
                    ->label('Função')
                    ->sortable(),

                Tables\Columns\IconColumn::make('ativo')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ativo')
                    ->label('Status')
                    ->options([
                        true => 'Ativo',
                        false => 'Inativo',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('solicitar')
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
                    ->action(function (Colaborador $record, array $data) {
                        Solicitacao::create([
                            'colaborador_id' => $record->id,
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
                        $this->notifyRH($record, $data);
                    }),

                Tables\Actions\Action::make('ver')
                    ->label('Ver Detalhes')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Colaborador $record): string => static::getUrl(['record' => $record->id])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function notifyRH(Colaborador $colaborador, array $data): void
    {
        $rhUsers = \App\Models\User::role('rh')->get();

        foreach ($rhUsers as $user) {
            Notification::make()
                ->title('Nova solicitação de alteração')
                ->body("Colaborador: {$colaborador->nome} - Tipo: {$data['tipo']}")
                ->success()
                ->sendToDatabase($user);
        }
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return parent::getUrl($parameters, $isAbsolute, 'ops', $tenant);
    }
}
