<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrimeiroAcessos\Tables;

use App\Models\PrimeiroAcesso;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;

class PrimeiroAcessosTable
{
    public static function configure(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->query(
                applyColaboradorRelationScope(
                    PrimeiroAcesso::query()->with(['colaborador.empresa', 'colaborador.unidade']),
                    $user
                )
            )
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\TextColumn::make('colaborador.nome')
                    ->label('Colaborador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('colaborador.empresa.nome')
                    ->label('Empresa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('colaborador.unidade.nome')
                    ->label('Unidade')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_informado')
                    ->label('Email informado')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'iniciado' => 'iniciado',
                        'colaborador_nao_encontrado' => 'colaborador_nao_encontrado',
                        'email_existente_enviado' => 'email_existente_enviado',
                        'email_novo_coletado' => 'email_novo_coletado',
                        'email_corrigido' => 'email_corrigido',
                        'token_emitido' => 'token_emitido',
                        'concluido' => 'concluido',
                        'bloqueado' => 'bloqueado',
                    ]),
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
            ->actions([])
            ->defaultSort('created_at', 'desc');
    }
}
