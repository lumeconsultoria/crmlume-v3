<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailPendencias\Tables;

use App\Models\EmailPendencia;
use App\Services\PrimeiroAcessoService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EmailPendenciasTable
{
    public static function configure(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->query(
                applyColaboradorRelationScope(
                    EmailPendencia::query()->with(['colaborador.empresa', 'colaborador.unidade']),
                    $user
                )
            )
            ->columns([
                Tables\Columns\TextColumn::make('colaborador.nome')
                    ->label('Colaborador')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('colaborador.empresa.nome')
                    ->label('Empresa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('colaborador.unidade.nome')
                    ->label('Unidade')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_sugerido')
                    ->label('Email sugerido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('motivo')
                    ->label('Motivo')
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('empresa')
                    ->label('Empresa')
                    ->relationship('colaborador.empresa', 'nome'),
                Tables\Filters\SelectFilter::make('unidade')
                    ->label('Unidade')
                    ->relationship('colaborador.unidade', 'nome'),
                Tables\Filters\Filter::make('periodo')
                    ->form([
                        DatePicker::make('inicio')->label('Início'),
                        DatePicker::make('fim')->label('Fim'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['inicio'] ?? null) {
                            $query->whereDate('created_at', '>=', $data['inicio']);
                        }
                        if ($data['fim'] ?? null) {
                            $query->whereDate('created_at', '<=', $data['fim']);
                        }
                    }),
            ])
            ->actions([
                Action::make('aprovar_email')
                    ->label('Aprovar email')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn(EmailPendencia $record) => Auth::user()?->can('update', $record))
                    ->action(function (EmailPendencia $record) {
                        app(PrimeiroAcessoService::class)->aprovarEmailPendencia($record, Auth::id());

                        Notification::make()
                            ->title('Email aprovado e acesso enviado')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
