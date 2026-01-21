<?php

namespace App\Filament\Resources\Empresas\Schemas;

use App\Models\Grupo;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                                    ->disabled(fn(): bool => Grupo::query()->count() === 0)
                                    ->helperText('Obrigatório. Selecione o Grupo do vínculo.'),
                            ]),
                    ]),
                }
            }
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
