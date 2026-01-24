<?php

namespace App\Filament\Resources\Empresas\Schemas;

use App\Models\Grupo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class EmpresaForm
{
    public static function configure(Schema $schema): Schema
    {
        // Hierarquia obrigatória: evita dados órfãos e garante consistência para ABAC/RBAC.
        return $schema
            ->components([
                Section::make('Estrutura Organizacional')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('grupo_id')
                                ->label('Grupo Nome')
                                ->relationship('grupo', 'nome')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Obrigatório. Selecione o Grupo do vínculo.'),
                        ]),
                    ]),

                Section::make('Identificação')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('razao_social')
                                ->label('Razão Social')
                                ->maxLength(255)
                                ->helperText('Nome jurídico.'),
                            TextInput::make('nome_fantasia')
                                ->label('Nome Fantasia')
                                ->maxLength(255)
                                ->helperText('Nome comercial, se existir.'),
                            TextInput::make('documento')
                                ->label('CNPJ')
                                ->placeholder('00.000.000/0000-00')
                                ->mask('99.999.999/9999-99')
                                ->live()
                                ->dehydrateStateUsing(fn($state) => self::normalizeDigits($state))
                                ->rule('nullable', 'digits:14')
                                ->unique(ignoreRecord: true, column: 'documento')
                                ->helperText('Digite o CNPJ para validar e sugerir dados. Será normalizado ao salvar.')
                                ->afterStateUpdated(function ($state, $set) {
                                    $cnpj = self::normalizeDigits($state ?? '');
                                    if (strlen($cnpj) !== 14) {
                                        return;
                                    }
                                    $data = self::fetchCnpj($cnpj);
                                    if (! $data) {
                                        return;
                                    }
                                    // Preenche apenas campos jurídicos; nome interno fica para confirmação do usuário.
                                    $set('razao_social', $data['razao_social'] ?? ($data['nome'] ?? null));
                                    $set('nome_fantasia', $data['nome_fantasia'] ?? null);
                                    $set('cnae', $data['cnae'] ?? null);
                                    $set('atividade', $data['atividade'] ?? null);
                                    $set('telefone', $data['telefone'] ?? null);
                                    $set('email', $data['email'] ?? null);
                                    $set('grau_risco', $data['cnae'] ? self::lookupGrauRisco($data['cnae']) : null);
                                    $set('cep', $data['cep'] ?? null);
                                    $set('logradouro', $data['logradouro'] ?? null);
                                    $set('bairro', $data['bairro'] ?? null);
                                    $set('cidade', $data['cidade'] ?? null);
                                    $set('uf', $data['uf'] ?? null);
                                }),
                            TextInput::make('nome')
                                ->label('Empresa Nome')
                                ->required()
                                ->helperText('Obrigatório. Nome oficial da Empresa.'),
                            TextInput::make('cnae')
                                ->label('CNAE')
                                ->helperText('Principal atividade econômica conforme CNAE.'),
                            TextInput::make('atividade')
                                ->label('Atividade')
                                ->helperText('Descrição da atividade principal.'),
                            TextInput::make('grau_risco')
                                ->label('Grau de Risco')
                                ->helperText('Derivado do CNAE (NR 4). Ajuste se necessário.'),
                            TextInput::make('telefone')
                                ->label('Telefone')
                                ->mask('(00) 0000-0000||(00) 0 0000-0000')
                                ->dehydrateStateUsing(fn($state) => self::normalizeDigits($state))
                                ->helperText('Contato principal (opcional).'),
                            TextInput::make('email')
                                ->label('E-mail')
                                ->email()
                                ->maxLength(255)
                                ->helperText('Opcional.'),
                        ]),
                    ]),

                Section::make('Endereço')
                    ->schema([
                        Toggle::make('editar_endereco')
                            ->label('Permitir edição manual do endereço')
                            ->default(false)
                            ->helperText('Campos são preenchidos pelo CEP; ative para editar.'),
                        Grid::make(3)->schema([
                            TextInput::make('cep')
                                ->label('CEP')
                                ->placeholder('00000-000')
                                ->mask('99999-999')
                                ->live()
                                ->helperText('Somente números.')
                                ->dehydrateStateUsing(fn($state) => self::normalizeDigits($state))
                                ->rule('nullable', 'digits:8')
                                ->afterStateUpdated(function ($state, $set) {
                                    $cep = self::normalizeDigits($state ?? '');
                                    if (strlen($cep) !== 8) {
                                        return;
                                    }
                                    $resp = Http::timeout(8)->get("https://viacep.com.br/ws/{$cep}/json/");
                                    if (! $resp->successful()) {
                                        return;
                                    }
                                    $data = $resp->json();
                                    if (! is_array($data) || ($data['erro'] ?? false)) {
                                        return;
                                    }
                                    $set('logradouro', $data['logradouro'] ?? null);
                                    $set('bairro', $data['bairro'] ?? null);
                                    $set('cidade', $data['localidade'] ?? null);
                                    $set('uf', $data['uf'] ?? null);
                                }),
                            TextInput::make('logradouro')
                                ->label('Logradouro')
                                ->columnSpan(2)
                                ->disabled(fn($get) => ! $get('editar_endereco')),
                            TextInput::make('numero')
                                ->label('Número')
                                ->maxLength(20)
                                ->disabled(fn($get) => ! $get('editar_endereco')),
                            TextInput::make('complemento')
                                ->label('Complemento')
                                ->maxLength(50)
                                ->disabled(fn($get) => ! $get('editar_endereco')),
                            TextInput::make('bairro')
                                ->label('Bairro')
                                ->disabled(fn($get) => ! $get('editar_endereco')),
                            TextInput::make('cidade')
                                ->label('Cidade')
                                ->disabled(fn($get) => ! $get('editar_endereco')),
                            Select::make('uf')
                                ->label('UF')
                                ->options([
                                    'AC' => 'AC',
                                    'AL' => 'AL',
                                    'AP' => 'AP',
                                    'AM' => 'AM',
                                    'BA' => 'BA',
                                    'CE' => 'CE',
                                    'DF' => 'DF',
                                    'ES' => 'ES',
                                    'GO' => 'GO',
                                    'MA' => 'MA',
                                    'MT' => 'MT',
                                    'MS' => 'MS',
                                    'MG' => 'MG',
                                    'PA' => 'PA',
                                    'PB' => 'PB',
                                    'PR' => 'PR',
                                    'PE' => 'PE',
                                    'PI' => 'PI',
                                    'RJ' => 'RJ',
                                    'RN' => 'RN',
                                    'RS' => 'RS',
                                    'RO' => 'RO',
                                    'RR' => 'RR',
                                    'SC' => 'SC',
                                    'SP' => 'SP',
                                    'SE' => 'SE',
                                    'TO' => 'TO',
                                ])
                                ->searchable()
                                ->disabled(fn($get) => ! $get('editar_endereco')),
                        ]),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('ativo')
                            ->label('Ativo')
                            ->default(true)
                            ->required()
                            ->helperText('Obrigatório. Define se a Empresa está ativa.'),
                    ]),
            ]);
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
                'telefone' => $data['telefone'] ?? null,
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

    private static array $cnaeRiskCache = [];

    private static function lookupGrauRisco(?string $cnae): ?string
    {
        if (! $cnae) {
            return null;
        }

        $normalized = self::normalizeDigits($cnae);
        if ($normalized === '') {
            return null;
        }

        // Cache em memória por request.
        if (! self::$cnaeRiskCache) {
            $path = base_path('database/cnae_nr04_validado.csv');
            if (! is_file($path)) {
                return null;
            }

            $rows = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            foreach ($rows as $i => $row) {
                if ($i === 0) { // header
                    continue;
                }
                $cols = str_getcsv($row);
                [$tronco, $cnaeCompleto, , $grau] = array_pad($cols, 4, null);
                if (! $cnaeCompleto || ! $grau) {
                    continue;
                }
                $keyFull = self::normalizeDigits($cnaeCompleto);
                self::$cnaeRiskCache[$keyFull] = $grau;
                if ($tronco) {
                    self::$cnaeRiskCache['tronco_' . $tronco] = $grau;
                }
            }
        }

        // Tenta match completo
        if (isset(self::$cnaeRiskCache[$normalized])) {
            return self::$cnaeRiskCache[$normalized];
        }

        // Fallback pelo tronco (5 dígitos iniciais)
        $tronco = substr($normalized, 0, 5);
        return self::$cnaeRiskCache['tronco_' . $tronco] ?? null;
    }

    private static function normalizeDigits(string $value): string
    {
        return preg_replace('/\D/', '', $value) ?? '';
    }
}
