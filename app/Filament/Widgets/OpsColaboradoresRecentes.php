<?php

namespace App\Filament\Widgets;

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Setor;
use App\Models\Unidade;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OpsColaboradoresRecentes extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Colaborador::query()
                    ->with(['funcao', 'unidade', 'empresa'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->heading('Colaboradores Recentes');
    }
}
