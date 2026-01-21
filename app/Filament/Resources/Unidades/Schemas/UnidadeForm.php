<?php

namespace App\Filament\Resources\Unidades\Schemas;

use App\Models\Empresa;
use App\Models\Grupo;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class UnidadeForm
{
    public static function configure(Schema $schema): Schema
    {
        // Hierarquia obrigatória: Grupo → Empresa → Unidade.
        return $schema
            ->components([
                Section::make('Estrutura Organizacional')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('grupo_id')
                                    ->label('Grupo Nome')
                                    ->options(fn() => Grupo::query()->orderBy('nome')->pluck('nome', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(fn(Set $set, $state, $record) => $set('grupo_id', $record?->empresa?->grupo_id))
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('empresa_id', null);
                                    })
                                    ->helperText('Obrigatório. Selecione o Grupo do vínculo.'),
                                Select::make('empresa_id')
                                    ->label('Empresa Nome')
                                    ->options(function (Get $get) {
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
                                    ->disabled(fn(Get $get): bool => ! $get('grupo_id'))
                                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $empresa = Empresa::query()->find($state);

                                        if (! $empresa) {
                                            return;
                                        }

                                        if (! $get('tipo_documento')) {
                                            $set('tipo_documento', $empresa->tipo_documento);
                                        }
                                        if (! $get('documento')) {
                                            $set('documento', $empresa->documento);
                                        }
                                        if (! $get('cnae')) {
                                            $set('cnae', $empresa->cnae);
                                        }
                                        if (! $get('atividade')) {
                                            $set('atividade', $empresa->atividade);
                                        }
                                        if (! $get('grau_risco')) {
                                            $set('grau_risco', $empresa->grau_risco);
                                        }
                                        if (! $get('cep')) {
                                            $set('cep', $empresa->cep);
                                        }
                                        if (! $get('logradouro')) {
                                            $set('logradouro', $empresa->logradouro);
                                        }
                                        if (! $get('bairro')) {
                                            $set('bairro', $empresa->bairro);
                                        }
                                        if (! $get('cidade')) {
                                            $set('cidade', $empresa->cidade);
                                        }
                                        if (! $get('uf')) {
                                            $set('uf', $empresa->uf);
                                        }
                                    })
                                    ->helperText('Obrigatório. Selecione a Empresa do vínculo.'),
                            ]),
                    ]),
                Section::make('Identificação')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nome')
                                    ->label('Unidade Nome')
                                    ->required()
                                    ->helperText('Obrigatório. Nome oficial da Unidade.'),
                                Select::make('tipo_documento')
                                    ->label('Tipo de Documento')
                                    ->options([
                                        'cpf' => 'CPF',
                                        'cnpj' => 'CNPJ',
                                    ])
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->helperText('Selecione o tipo de documento para aplicar a máscara.'),
                                TextInput::make('documento')
                                    ->label('Documento')
                                    ->mask(fn(Get $get): string => $get('tipo_documento') === 'cpf'
                                        ? '000.000.000-00'
                                        : '00.000.000/0000-00')
                                    ->live(debounce: 800)
                                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                        if ($get('tipo_documento') !== 'cnpj' || ! $state) {
                                            return;
                                        }

                                        $cnpj = self::normalizeDigits($state);

                                        if (strlen($cnpj) !== 14) {
                                            return;
                                        }

                                        $data = self::fetchCnpj($cnpj);

                                        if (! $data) {
                                            return;
                                        }

                                        $nome = $data['nome_fantasia'] ?? $data['razao_social'] ?? null;
                                        if ($nome) {
                                            $set('nome', $nome);
                                        }

                                        if (! empty($data['cnae'])) {
                                            $set('cnae', $data['cnae']);
                                        }

                                        if (! empty($data['atividade'])) {
                                            $set('atividade', $data['atividade']);
                                        }

                                        if (! empty($data['cep'])) {
                                            $set('cep', $data['cep']);
                                        }

                                        if (! empty($data['logradouro'])) {
                                            $set('logradouro', $data['logradouro']);
                                        }

                                        if (! empty($data['bairro'])) {
                                            $set('bairro', $data['bairro']);
                                        }

                                        if (! empty($data['cidade'])) {
                                            $set('cidade', $data['cidade']);
                                        }

                                        if (! empty($data['uf'])) {
                                            $set('uf', $data['uf']);
                                        }

                                        $grauRisco = self::deriveGrauRisco($data['cnae'] ?? null);
                                        if ($grauRisco) {
                                            $set('grau_risco', $grauRisco);
                                        }
                                    })
                                    ->helperText('Digite o CPF ou CNPJ. Para CNPJ, os dados serão preenchidos automaticamente.'),
                                TextInput::make('cnae')
                                    ->label('CNAE')
                                    ->helperText('Principal atividade econômica conforme CNAE.'),
                                TextInput::make('atividade')
                                    ->label('Atividade')
                                    ->helperText('Descrição da atividade principal.'),
                                TextInput::make('grau_risco')
                                    ->label('Grau de Risco')
                                    ->helperText('Derivado do CNAE (NR 4). Ajuste se necessário.'),
                            ]),
                    ]),
                Section::make('Endereço / Dados Complementares')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('cep')
                                    ->label('CEP')
                                    ->mask('00000-000')
                                    ->live(debounce: 800)
                                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $cep = self::normalizeDigits($state);

                                        if (strlen($cep) !== 8) {
                                            return;
                                        }

                                        $data = self::fetchCep($cep);

                                        if (! $data) {
                                            return;
                                        }

                                        $set('logradouro', $data['logradouro'] ?? null);
                                        $set('bairro', $data['bairro'] ?? null);
                                        $set('cidade', $data['cidade'] ?? null);
                                        $set('uf', $data['uf'] ?? null);
                                    })
                                    ->helperText('Digite o CEP para preencher o endereço automaticamente.'),
                                TextInput::make('logradouro')
                                    ->label('Logradouro'),
                                TextInput::make('bairro')
                                    ->label('Bairro'),
                                TextInput::make('cidade')
                                    ->label('Cidade'),
                                TextInput::make('uf')
                                    ->label('UF')
                                    ->maxLength(2),
                                Toggle::make('ativo')
                                    ->label('Ativo')
                                    ->required()
                                    ->helperText('Obrigatório. Define se a Unidade está ativa.'),
                            ]),
                    ]),
            ]);
    }

    private static function normalizeDigits(?string $value): string
    {
        return preg_replace('/\D/', '', $value ?? '') ?? '';
    }

    private static function fetchCep(string $cep): ?array
    {
        try {
            /** @var Response $response */
            $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cep}/json/");

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            if (! is_array($data) || ($data['erro'] ?? false)) {
                return null;
            }

            return [
                'logradouro' => $data['logradouro'] ?? null,
                'bairro' => $data['bairro'] ?? null,
                'cidade' => $data['localidade'] ?? null,
                'uf' => $data['uf'] ?? null,
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    private static function fetchCnpj(string $cnpj): ?array
    {
        try {
            /** @var Response $response */
            $response = Http::timeout(8)->get("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            if (! is_array($data) || ($data['status'] ?? null) === 'ERROR') {
                return null;
            }

            $atividade = $data['atividade_principal'][0]['text'] ?? $data['cnae_fiscal_descricao'] ?? null;
            $cnae = $data['atividade_principal'][0]['code'] ?? $data['cnae_fiscal'] ?? null;

            return [
                'razao_social' => $data['nome'] ?? null,
                'nome_fantasia' => $data['fantasia'] ?? null,
                'cnae' => $cnae,
                'atividade' => $atividade,
                'cep' => $data['cep'] ?? null,
                'logradouro' => $data['logradouro'] ?? null,
                'bairro' => $data['bairro'] ?? null,
                'cidade' => $data['municipio'] ?? null,
                'uf' => $data['uf'] ?? null,
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    private static function deriveGrauRisco(?string $cnae): ?string
    {
        if (! $cnae) {
            return null;
        }

        $digits = self::normalizeDigits($cnae);
        if (strlen($digits) < 5) {
            return null;
        }

        $tronco = substr($digits, 0, 5);

        $mapa = [
            // Adicione aqui o mapeamento oficial NR 4 (tronco 5 dígitos → grau de risco).
        ];

        return $mapa[$tronco] ?? null;
    }
}
