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
                            Select::make('empresa_id')
                                ->label('Empresa')
                                ->relationship('empresa', 'nm_razao_social')
                                ->getOptionLabelFromRecordUsing(fn (Empresa $record) => $record->nm_razao_social ?: '-- sem nome --')
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
                                    $set('ds_cep', $empresa->ds_cep);
                                    $set('ds_logradouro', $empresa->ds_logradouro);
                                    $set('ds_bairro', $empresa->ds_bairro);
                                    $set('ds_cidade', $empresa->ds_cidade);
                                    $set('sgl_estado', $empresa->sgl_estado);
                                    $set('ds_telefone', $empresa->ds_telefone);
                                    $set('usar_dados_empresa', true);
                                }),
                        ]),
                    ]),

                Section::make('Identificação')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nm_fantasia')
                                ->label('Nome da Unidade')
                                ->required()
                                ->helperText('Obrigatório. Nome da unidade.'),
                            TextInput::make('nr_cnpj')
                                ->label('CNPJ')
                                ->placeholder('00.000.000/0000-00')
                                ->mask('99.999.999/9999-99')
                                ->live()
                                ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                                ->rule('nullable', 'digits:14'),
                            TextInput::make('cd_cnae')
                                ->label('CNAE')
                                ->maxLength(10)
                                ->helperText('5 dígitos.'),
                            TextInput::make('nr_grau_risco')
                                ->label('Grau de Risco')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(4)
                                ->helperText('Derivado do CNAE; ajuste se necessário.'),
                            TextInput::make('ds_telefone')
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
                            TextInput::make('ds_cep')
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
                                    $set('ds_logradouro', $resp['logradouro'] ?? null);
                                    $set('ds_bairro', $resp['bairro'] ?? null);
                                    $set('ds_cidade', $resp['localidade'] ?? null);
                                    $set('sgl_estado', $resp['uf'] ?? null);
                                }),
                            TextInput::make('ds_logradouro')
                                ->label('Logradouro')
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('ds_numero')
                                ->label('Número')
                                ->maxLength(20)
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('ds_complemento')
                                ->label('Complemento')
                                ->maxLength(50)
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('ds_bairro')
                                ->label('Bairro')
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('ds_cidade')
                                ->label('Cidade')
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            TextInput::make('sgl_estado')
                                ->label('UF')
                                ->maxLength(2)
                                ->disabled(fn($get) => $get('usar_dados_empresa')),
                            Toggle::make('ativo')
                                ->label('Ativo')
                                ->required()
                                ->helperText('Define se a Unidade está ativa.'),
                            Grid::make(2)->schema([
                                TextInput::make('status_integracao')
                                    ->label('Status Integração (A/I)')
                                    ->maxLength(1)
                                    ->default('A'),
                                TextInput::make('codigo_externo')
                                    ->label('Código Externo')
                                    ->maxLength(50)
                                    ->helperText('cd_interno_unidade'),
                            ]),
                            TextInput::make('indexmed_id')
                                ->label('ID IndexMed')
                                ->numeric()
                                ->minValue(1)
                                ->nullable(),
                        ]),
                    ]),
            ]);
    }
}