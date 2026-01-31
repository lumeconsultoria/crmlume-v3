<?php

namespace App\Filament\Resources\Colaboradors\Schemas;

use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ColaboradorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Estrutura')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('grupo_id')
                                ->label('Grupo/Cliente')
                                ->options(fn () => Grupo::query()
                                    ->orderBy('nome')
                                    ->get()
                                    ->mapWithKeys(fn ($g) => [$g->id => $g->nome ?: '-- sem nome --'])
                                )
                                ->searchable()
                                ->preload()
                                ->live()
                                ->dehydrated(false)
                                ->afterStateUpdated(function ($set) {
                                    $set('empresa_id', null);
                                    $set('unidade_id', null);
                                    $set('setor_id', null);
                                    $set('funcao_id', null);
                                })
                                ->helperText('Selecione o Grupo/Cliente.'),
                            Select::make('empresa_id')
                                ->label('Empresa/Empregador')
                                ->options(function ($get) {
                                    $grupoId = $get('grupo_id');
                                    if (! $grupoId) {
                                        return [];
                                    }

                                    return Empresa::query()
                                        ->where('grupo_id', $grupoId)
                                        ->orderBy('nm_razao_social')
                                        ->get()
                                        ->mapWithKeys(fn ($e) => [$e->id => $e->nm_razao_social ?: '-- sem nome --']);
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->dehydrated(false)
                                ->afterStateUpdated(function ($set) {
                                    $set('unidade_id', null);
                                    $set('setor_id', null);
                                    $set('funcao_id', null);
                                })
                                ->helperText('Selecione a Empresa/Empregador.'),
                            Select::make('unidade_id')
                                ->label('Unidade')
                                ->options(function ($get) {
                                    $empresaId = $get('empresa_id');
                                    if (! $empresaId) {
                                        return [];
                                    }

                                    return Unidade::query()
                                        ->where('empresa_id', $empresaId)
                                        ->orderBy('nm_fantasia')
                                        ->get()
                                        ->mapWithKeys(fn ($u) => [$u->id => $u->nm_fantasia ?: '-- sem nome --']);
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->dehydrated(false)
                                ->afterStateUpdated(function ($set) {
                                    $set('setor_id', null);
                                    $set('funcao_id', null);
                                })
                                ->helperText('Selecione a Unidade.'),
                            Select::make('setor_id')
                                ->label('Setor/Departamento')
                                ->options(function ($get) {
                                    $unidadeId = $get('unidade_id');
                                    if (! $unidadeId) {
                                        return [];
                                    }

                                    return Setor::query()
                                        ->where('unidade_id', $unidadeId)
                                        ->orderBy('nome')
                                        ->get()
                                        ->mapWithKeys(fn ($s) => [$s->id => $s->nome ?: '-- sem nome --']);
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->dehydrated(false)
                                ->afterStateUpdated(fn ($set) => $set('funcao_id', null))
                                ->helperText('Selecione o Setor/Departamento.'),
                            Select::make('funcao_id')
                                ->label('Cargo/Função')
                                ->options(function ($get) {
                                    $setorId = $get('setor_id');
                                    if (! $setorId) {
                                        return [];
                                    }

                                    return Funcao::query()
                                        ->where('setor_id', $setorId)
                                        ->orderBy('nome')
                                        ->get()
                                        ->mapWithKeys(fn ($f) => [$f->id => $f->nome ?: '-- sem nome --']);
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Selecione a função; unidade/empresa/grupo serão validados.'),
                        ]),
                    ]),

                Section::make('Dados do Colaborador')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('fl_tipo')
                                ->label('Tipo (Funcionário ou Candidato)')
                                ->options([
                                    'F' => 'Funcionário',
                                    'C' => 'Candidato',
                                ])
                                ->default('F')
                                ->required()
                                ->live(),
                            Toggle::make('trabalhador_sem_vinculo')
                                ->label('Trabalhador sem vínculo de emprego?')
                                ->helperText('Quando marcado, matrícula pode ficar vazia.')
                                ->default(false)
                                ->live(),
                            TextInput::make('nome')
                                ->label('Nome completo')
                                ->required(),
                            TextInput::make('matricula')
                                ->label('Matrícula')
                                ->maxLength(50)
                                ->required(fn ($get) => $get('fl_tipo') === 'F' && ! $get('trabalhador_sem_vinculo'))
                                ->helperText('Obrigatória para Funcionário; opcional para Candidato ou sem vínculo.'),
                            TextInput::make('cpf')
                                ->label('CPF')
                                ->mask('000.000.000-00')
                                ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state ?? ''))
                                ->rule('required', 'digits:11'),
                            Select::make('genero')
                                ->label('Gênero')
                                ->options([
                                    'M' => 'Masculino',
                                    'F' => 'Feminino',
                                    'O' => 'Outro',
                                ])
                                ->required(),
                            DatePicker::make('data_nascimento')
                                ->label('Data de nascimento')
                                ->required(),
                            DatePicker::make('data_admissao')
                                ->label('Data de admissão (ou previsão)')
                                ->required(fn ($get) => $get('fl_tipo') === 'F')
                                ->helperText('Obrigatória para Funcionário.'),
                            DatePicker::make('ultima_avaliacao_clinica')
                                ->label('Última avaliação clínica')
                                ->helperText('Preencha se já existe ASO anterior.')
                                ->nullable(),
                            TextInput::make('user_email')
                                ->label('E-mail (login)')
                                ->email()
                                ->required(),
                            Toggle::make('user_ativo')
                                ->label('Usuário ativo')
                                ->default(true),
                        ]),
                    ]),

                Section::make('Status / Integração')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('ativo')
                                ->label('Ativo (interno)')
                                ->required()
                                ->default(true),
                            Select::make('status_integracao')
                                ->label('Situação/Status')
                                ->options([
                                    'A' => 'Ativo',
                                    'I' => 'Inativo/Demitido',
                                    'F' => 'Férias',
                                    'T' => 'Afastado',
                                ])
                                ->required()
                                ->default('A')
                                ->live()
                                ->afterStateUpdated(fn ($state, $set) => $set('ativo', $state !== 'I')),
                        ]),
                        TextInput::make('codigo_externo')
                            ->label('Código Externo')
                            ->maxLength(50)
                            ->helperText('Opcional'),
                        TextInput::make('indexmed_id')
                            ->label('ID IndexMed')
                            ->numeric()
                            ->nullable(),
                    ]),
            ]);
    }
}
