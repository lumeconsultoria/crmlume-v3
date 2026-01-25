<?php

namespace App\Filament\Resources\Unidades\Schemas;

use App\Models\Empresa;
use App\Models\Grupo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class UnidadeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ================================
             * Estrutura Organizacional
             * ================================ */
            Section::make('Estrutura Organizacional')
                ->schema([
                    Grid::make(2)->schema([

                        Select::make('grupo_id')
                            ->label('Grupo')
                            ->options(fn() => Grupo::query()
                                ->orderBy('nome')
                                ->get()
                                ->mapWithKeys(fn($grupo) => [
                                    $grupo->id => $grupo->nome ?: '-- sem nome --',
                                ])
                                ->all())
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(
                                fn($state, Set $set) => $set('empresa_id', null)
                            ),

                        Select::make('empresa_id')
                            ->label('Empresa')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->options(function ($get) {
                                return Empresa::query()
                                    ->when(
                                        $get('grupo_id'),
                                        fn($q, $grupoId) => $q->where('grupo_id', $grupoId)
                                    )
                                    ->orderBy('nm_razao_social')
                                    ->get()
                                    ->mapWithKeys(fn($empresa) => [
                                        $empresa->id => $empresa->nm_razao_social
                                            ?: ($empresa->nm_fantasia ?: '-- sem nome --'),
                                    ])
                                    ->all();
                            })
                            ->rules(fn($get) => [
                                'required',
                                Rule::exists('empresas', 'id')
                                    ->when(
                                        $get('grupo_id'),
                                        fn($rule) => $rule->where('grupo_id', $get('grupo_id'))
                                    ),
                            ])
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (! $state) {
                                    return;
                                }

                                $empresa = Empresa::find($state);
                                if (! $empresa) {
                                    return;
                                }

                                if ($get('usar_dados_empresa')) {
                                    self::fillFromEmpresa($empresa, $set);
                                }
                            }),
                    ]),
                ]),

            /* ================================
             * Identificação
             * ================================ */
            Section::make('Identificação')
                ->schema([
                    Grid::make(2)->schema([

                        TextInput::make('nome')
                            ->label('Nome da Unidade')
                            ->required(),

                        TextInput::make('nr_cnpj')
                            ->label('CNPJ')
                            ->mask('99.999.999/9999-99')
                            ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                            ->rule('nullable', 'digits:14'),

                        TextInput::make('cd_cnae')
                            ->label('CNAE'),

                        TextInput::make('nr_grau_risco')
                            ->label('Grau de Risco')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4),

                        TextInput::make('ds_telefone')
                            ->label('Telefone')
                            ->mask('(00) 0000-0000||(00) 0 0000-0000')
                            ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? '')),
                    ]),
                ]),

            /* ================================
             * Endereço / Dados Complementares
             * ================================ */
            Section::make('Endereço / Dados Complementares')
                ->schema([

                    Toggle::make('usar_dados_empresa')
                        ->label('Usar dados da Empresa')
                        ->default(true)
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if (! $state) {
                                // limpando para nova filial
                                foreach (self::$empresaCloneFields as $field) {
                                    $set($field, null);
                                }
                                return;
                            }

                            $empresaId = $get('empresa_id');
                            if (! $empresaId) {
                                return;
                            }

                            $empresa = Empresa::find($empresaId);
                            if ($empresa) {
                                self::fillFromEmpresa($empresa, $set);
                            }
                        }),

                    Grid::make(2)->schema([

                        TextInput::make('ds_cep')
                            ->label('CEP')
                            ->mask('99999-999')
                            ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                            ->rule('nullable', 'digits:8')
                            ->disabled(fn(Get $get) => $get('usar_dados_empresa'))
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $cep = preg_replace('/\D/', '', $state ?? '');
                                if (strlen($cep) !== 8) {
                                    return;
                                }

                                $resp = Http::get("https://viacep.com.br/ws/{$cep}/json/");
                                if (! $resp->successful() || ($resp['erro'] ?? false)) {
                                    return;
                                }

                                $set('ds_logradouro', $resp['logradouro'] ?? null);
                                $set('ds_bairro', $resp['bairro'] ?? null);
                                $set('ds_cidade', $resp['localidade'] ?? null);
                                $set('sgl_estado', $resp['uf'] ?? null);
                            }),

                        TextInput::make('ds_logradouro')->label('Logradouro')->disabled(fn(Get $get) => $get('usar_dados_empresa')),
                        TextInput::make('ds_numero')->label('Número')->disabled(fn(Get $get) => $get('usar_dados_empresa')),
                        TextInput::make('ds_complemento')->label('Complemento')->disabled(fn(Get $get) => $get('usar_dados_empresa')),
                        TextInput::make('ds_bairro')->label('Bairro')->disabled(fn(Get $get) => $get('usar_dados_empresa')),
                        TextInput::make('ds_cidade')->label('Cidade')->disabled(fn(Get $get) => $get('usar_dados_empresa')),
                        TextInput::make('sgl_estado')->label('UF')->disabled(fn(Get $get) => $get('usar_dados_empresa')),

                        Toggle::make('ativo')->label('Ativo')->default(true),

                        TextInput::make('status_integracao')->label('Status Integração')->default('A'),
                        TextInput::make('codigo_externo')->label('Código Externo'),
                        TextInput::make('indexmed_id')->label('ID IndexMed')->numeric(),
                    ]),
                ]),
        ]);
    }

    /* ================================
     * Copia dados da Empresa
     * ================================ */
    private static function fillFromEmpresa(Empresa $empresa, Set $set): void
    {
        $set('nome', $empresa->nm_fantasia ?? $empresa->nm_razao_social);
        $set('nr_cnpj', $empresa->nr_cnpj);
        $set('cd_cnae', $empresa->cd_cnae);
        $set('nr_grau_risco', $empresa->nr_grau_risco);
        $set('ds_telefone', $empresa->ds_telefone);
        $set('ds_cep', $empresa->ds_cep);
        $set('ds_logradouro', $empresa->ds_logradouro);
        $set('ds_numero', $empresa->ds_numero);
        $set('ds_complemento', $empresa->ds_complemento);
        $set('ds_bairro', $empresa->ds_bairro);
        $set('ds_cidade', $empresa->ds_cidade);
        $set('sgl_estado', $empresa->sgl_estado);
    }

    private static array $empresaCloneFields = [
        'nome',
        'nr_cnpj',
        'cd_cnae',
        'nr_grau_risco',
        'ds_telefone',
        'ds_cep',
        'ds_logradouro',
        'ds_numero',
        'ds_complemento',
        'ds_bairro',
        'ds_cidade',
        'sgl_estado',
    ];
}
