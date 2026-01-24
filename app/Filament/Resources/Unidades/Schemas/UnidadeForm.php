<?php

namespace App\Filament\Resources\Unidades\Schemas;

use App\Models\Empresa;
use App\Models\Grupo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;

class UnidadeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Estrutura Organizacional')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('grupo_id')
                                ->label('Grupo Nome')
                                ->options(fn() => Grupo::query()->orderBy('nome')->pluck('nome', 'id'))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->dehydrated(false)
                                ->afterStateUpdated(fn($set) => $set('empresa_id', null))
                                ->helperText('Obrigatório. Selecione o Grupo do vínculo.'),
                            Select::make('empresa_id')
                                ->label('Empresa Nome')
                                ->options(function ($get) {
                                    $grupoId = $get('grupo_id');
                                    if (! $grupoId) {
                                        return [];
                                    }
                                    return Empresa::query()
                                        ->where('grupo_id', $grupoId)
                                        ->orderBy('nome')
                                        ->pluck('nome', 'id');
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->helperText('Obrigatório. Selecione a Empresa do vínculo.')
                                ->afterStateUpdated(function ($state, $set) {
                                    if (! $state) {
                                        return;
                                    }
                                    $empresa = Empresa::find($state);
                                    if (! $empresa) {
                                        return;
                                    }
                                    // Pré-preenche endereço/contato a partir da Empresa
                                    $set('cep', $empresa->cep);
                                    $set('logradouro', $empresa->logradouro);
                                    $set('bairro', $empresa->bairro);
                                    $set('cidade', $empresa->cidade);
                                    $set('uf', $empresa->uf);
                                    $set('telefone', $empresa->telefone);
                                    $set('usar_dados_empresa', true);
                                }),
                        ]),
                    ]),

                Section::make('Identificação')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nome')
                                ->label('Unidade Nome')
                                ->required()
                                ->helperText('Obrigatório. Nome da unidade.'),
                            Select::make('tipo_documento')
                                ->label('Tipo de Documento')
                                ->options([
                                    'cpf' => 'CPF',
                                    'cnpj' => 'CNPJ',
                                ])
                                ->live()
                                ->helperText('Selecione o tipo para aplicar a máscara.'),
                            TextInput::make('documento')
                                ->label('Documento')
                                ->placeholder('CPF ou CNPJ')
                                ->mask(fn($get) => $get('tipo_documento') === 'cpf'
                                    ? '000.000.000-00'
                                    : '00.000.000/0000-00')
                                ->live()
                                ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                                ->helperText('Digite o CPF ou CNPJ.'),
                            TextInput::make('telefone')
                                ->label('Telefone')
                                ->mask('(00) 0000-0000||(00) 0 0000-0000')
                                ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                                ->helperText('Contato (opcional).'),
                        ]),
                    ]),

                Section::make('Endereço / Dados Complementares')
                    ->schema([
                        Toggle::make('usar_dados_empresa')
                            ->label('Usar dados da Empresa')
                            ->default(true)
                            ->helperText('Por padrão, copia o endereço da Empresa; desative para editar.'),
                        Grid::make(2)->schema([
                            TextInput::make('cep')
                                ->label('CEP')
                                ->placeholder('00000-000')
                                ->mask('99999-999')
                                ->live()
                                ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                                ->rule('nullable', 'digits:8')
                                ->disabled(fn($get) => $get('usar_dados_empresa'))
                                ->afterStateUpdated(function ($state, $set) {
                                    $cep = preg_replace('/\D/', '', $state ?? '');
                                    if (strlen($cep) !== 8) {
                                        return;
                                    }
                                    $resp = Http::timeout(8)->get("https://viacep.com.br/ws/{$cep}/json/");
                                    if (! $resp->successful() || ($resp['erro'] ?? false)) {
                                        return;
                                    }
                                    $set('logradouro', $resp['logradouro'] ?? null);
                                    $set('bairro', $resp['bairro'] ?? null);
                                    $set('cidade', $resp['localidade'] ?? null);
                                    $set('uf', $resp['uf'] ?? null);
                                }),
                            TextInput::make('logradouro')
                                ->label('Logradouro')
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('numero')
                                ->label('Número')
                                ->maxLength(20)
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('complemento')
                                ->label('Complemento')
                                ->maxLength(50)
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('bairro')
                                ->label('Bairro')
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('cidade')
                                ->label('Cidade')
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('uf')
                                ->label('UF')
                                ->maxLength(2)
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            Toggle::make('ativo')
                                ->label('Ativo')
                                ->required()
                                ->helperText('Define se a Unidade está ativa.'),
                        ]),
                    ]),
            ]);
    }
}
