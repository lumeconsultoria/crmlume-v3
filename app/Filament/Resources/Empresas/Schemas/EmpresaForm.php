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
                        Grid::make(2)
                            ->schema([
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
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nome')
                                    ->label('Empresa Nome')
                                    ->required()
                                    ->helperText('Obrigatório. Nome oficial da Empresa.'),
                                TextInput::make('documento')
                                    ->label('CNPJ')
                                    ->mask('00.000.000/0000-00')
                                    ->helperText('Digite o CNPJ (opcional).'),
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

    private static function normalizeDigits(string $value): string
    {
        return preg_replace('/\D/', '', $value) ?? '';
    }
}

