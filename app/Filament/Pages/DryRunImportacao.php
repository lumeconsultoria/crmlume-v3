<?php

namespace App\Filament\Pages;

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\User;
use App\Services\DryRunImportReport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DryRunImportacao extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Dry-Run Importação';

    protected static ?string $navigationLabel = 'Dry-Run Importação';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Importação';

    protected string $view = 'filament.pages.dry-run-importacao';

    public ?array $data = [];

    public array $report = [];

    public array $errors = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                FileUpload::make('estrutura_csv')
                    ->label('CSV - Estrutura Organizacional')
                    ->required()
                    ->acceptedFileTypes([
                        'text/csv',
                        'text/plain',
                        'application/vnd.ms-excel',
                    ])
                    ->storeFiles(false),
                FileUpload::make('colaboradores_csv')
                    ->label('CSV - Colaboradores')
                    ->required()
                    ->acceptedFileTypes([
                        'text/csv',
                        'text/plain',
                        'application/vnd.ms-excel',
                    ])
                    ->storeFiles(false),
            ])
            ->statePath('data');
    }

    public function executeDryRun(): void
    {
        $this->errors = [];
        $this->report = [];

        $report = new DryRunImportReport();

        $state = $this->form->getState();

        $estruturaPath = $this->resolveUploadPath($state['estrutura_csv'] ?? null);
        $colaboradoresPath = $this->resolveUploadPath($state['colaboradores_csv'] ?? null);

        if (! $estruturaPath || ! $colaboradoresPath) {
            $report->addError(DryRunImportReport::TYPE_BLOCKING, 'Arquivos CSV obrigatórios não foram fornecidos.');
            $this->report = $report->toArray();
            return;
        }

        $estrutura = $this->readCsv($estruturaPath, DryRunImportReport::ESTRUTURA_HEADERS, 'Estrutura Organizacional');

        $colaboradores = $this->readCsv($colaboradoresPath, DryRunImportReport::COLABORADORES_HEADERS, 'Colaboradores');

        foreach ($estrutura['errors'] as $error) {
            $report->addError(DryRunImportReport::TYPE_BLOCKING, $error);
        }

        foreach ($colaboradores['errors'] as $error) {
            $report->addError(DryRunImportReport::TYPE_BLOCKING, $error);
        }

        if ($report->hasBlockingErrors()) {
            $this->report = $report->toArray();
            return;
        }

        $estruturaReport = $this->analisarEstrutura($estrutura['rows']);
        $colaboradoresReport = $this->analisarColaboradores($colaboradores['rows'], $estruturaReport['index']);

        foreach ($estruturaReport['errors'] as $error) {
            $report->addError(DryRunImportReport::TYPE_BLOCKING, $error);
        }

        foreach ($colaboradoresReport['errors_blocking'] as $error) {
            $report->addError(DryRunImportReport::TYPE_BLOCKING, $error);
        }

        foreach ($colaboradoresReport['errors_alert'] as $error) {
            $report->addError(DryRunImportReport::TYPE_ALERT, $error);
        }

        foreach ($colaboradoresReport['errors_info'] as $error) {
            $report->addError(DryRunImportReport::TYPE_INFO, $error);
        }

        $report->estrutura = $estruturaReport['report'];
        $report->colaboradores = $colaboradoresReport['report'];

        $this->report = $report->toArray();
    }

    private function resolveUploadPath(mixed $file): ?string
    {
        if ($file instanceof TemporaryUploadedFile) {
            return $file->getRealPath();
        }

        if (is_string($file) && $file !== '') {
            return $file;
        }

        return null;
    }

    private function readCsv(string $path, array $expectedHeaders, string $label): array
    {
        $rows = [];
        $errors = [];

        if (! is_readable($path)) {
            return [
                'rows' => [],
                'errors' => ["{$label}: arquivo não pode ser lido."],
            ];
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            return [
                'rows' => [],
                'errors' => ["{$label}: arquivo inválido."],
            ];
        }

        $header = fgetcsv($handle);

        if (! is_array($header)) {
            fclose($handle);
            return [
                'rows' => [],
                'errors' => ["{$label}: cabeçalho CSV não encontrado."],
            ];
        }

        $header = array_map(function (string $value): string {
            $value = trim($value);
            $value = preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;
            $value = trim($value, '"');
            return $value;
        }, $header);

        foreach ($header as $column) {
            if (preg_match('/\bid\b/i', $column) === 1) {
                $errors[] = "{$label}: coluna inválida '{$column}' (IDs externos não são permitidos).";
            }
        }

        $missing = array_values(array_diff($expectedHeaders, $header));
        $extra = array_values(array_diff($header, $expectedHeaders));

        if (! empty($missing)) {
            $errors[] = "{$label}: colunas obrigatórias ausentes: " . implode(', ', $missing) . '.';
        }

        if (! empty($extra)) {
            $errors[] = "{$label}: colunas não permitidas: " . implode(', ', $extra) . '.';
        }

        $line = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $line++;
            if (! array_filter($data, fn ($value) => $value !== null && trim((string) $value) !== '')) {
                continue;
            }

            if (count($data) !== count($header)) {
                $errors[] = "{$label}: linha {$line} possui " . count($data) . " colunas (esperado " . count($header) . ").";
                continue;
            }

            $rows[] = array_combine($header, $data);
        }

        fclose($handle);

        return [
            'rows' => $rows,
            'errors' => $errors,
        ];
    }

    private function analisarEstrutura(array $rows): array
    {
        $report = [
            'grupos' => [],
            'empresas' => [],
            'unidades' => [],
            'setores' => [],
            'funcoes' => [],
        ];

        $errors = [];
        $index = [
            'grupos' => [],
            'empresas' => [],
            'unidades' => [],
            'setores' => [],
            'funcoes' => [],
        ];

        foreach ($rows as $row) {
            $grupoNome = trim($row['GRUPO'] ?? '');
            $empresaNome = trim($row['EMPRESA'] ?? '');
            $unidadeNome = trim($row['UNIDADE'] ?? '');
            $setorNome = trim($row['SETOR'] ?? '');
            $funcaoNome = trim($row['FUNCAO'] ?? '');

            if ($grupoNome === '' || $empresaNome === '' || $unidadeNome === '' || $setorNome === '' || $funcaoNome === '') {
                $errors[] = 'Estrutura Organizacional: todos os campos são obrigatórios.';
                continue;
            }

            if (! isset($index['grupos'][$grupoNome])) {
                $index['grupos'][$grupoNome] = Grupo::where('nome', $grupoNome)->first();
                if (! $index['grupos'][$grupoNome]) {
                    $report['grupos'][] = $grupoNome;
                }
            }

            $empresaKey = $grupoNome . '|' . $empresaNome;
            if (! isset($index['empresas'][$empresaKey])) {
                $index['empresas'][$empresaKey] = Empresa::where('nome', $empresaNome)
                    ->whereHas('grupo', function ($query) use ($grupoNome) {
                        $query->where('nome', $grupoNome);
                    })
                    ->first();
                if (! $index['empresas'][$empresaKey]) {
                    $report['empresas'][] = $empresaNome;
                }
            }

            $unidadeKey = $empresaKey . '|' . $unidadeNome;
            if (! isset($index['unidades'][$unidadeKey])) {
                $index['unidades'][$unidadeKey] = Unidade::where('nome', $unidadeNome)
                    ->whereHas('empresa', function ($query) use ($empresaNome) {
                        $query->where('nome', $empresaNome);
                    })
                    ->first();
                if (! $index['unidades'][$unidadeKey]) {
                    $report['unidades'][] = $unidadeNome;
                }
            }

            $setorKey = $unidadeKey . '|' . $setorNome;
            if (! isset($index['setores'][$setorKey])) {
                $index['setores'][$setorKey] = Setor::where('nome', $setorNome)
                    ->whereHas('unidade', function ($query) use ($unidadeNome) {
                        $query->where('nome', $unidadeNome);
                    })
                    ->first();
                if (! $index['setores'][$setorKey]) {
                    $report['setores'][] = $setorNome;
                }
            }

            $funcaoKey = $setorKey . '|' . $funcaoNome;
            if (! isset($index['funcoes'][$funcaoKey])) {
                $index['funcoes'][$funcaoKey] = Funcao::where('nome', $funcaoNome)
                    ->whereHas('setor', function ($query) use ($setorNome) {
                        $query->where('nome', $setorNome);
                    })
                    ->first();
                if (! $index['funcoes'][$funcaoKey]) {
                    $report['funcoes'][] = $funcaoNome;
                }
            }
        }

        return [
            'report' => $report,
            'errors' => $errors,
            'index' => $index,
        ];
    }

    private function analisarColaboradores(array $rows, array $estruturaIndex): array
    {
        $report = [
            'total' => 0,
            'novos' => 0,
            'atualizar' => 0,
            'usuarios_criar' => 0,
            'usuarios_pendentes' => 0,
        ];

        $errorsBlocking = [];
        $errorsAlert = [];
        $errorsInfo = [];

        foreach ($rows as $row) {
            $report['total']++;

            $cpf = trim($row['CPF'] ?? '');
            $nome = trim($row['NOME'] ?? '');
            $dataNascimento = trim($row['DATA_NASCIMENTO'] ?? '');
            $email = trim($row['EMAIL'] ?? '');
            $grupoNome = trim($row['GRUPO'] ?? '');
            $empresaNome = trim($row['EMPRESA'] ?? '');
            $unidadeNome = trim($row['UNIDADE'] ?? '');
            $setorNome = trim($row['SETOR'] ?? '');
            $funcaoNome = trim($row['FUNCAO'] ?? '');

            if ($cpf === '' || $nome === '' || $dataNascimento === '' || $grupoNome === '' || $empresaNome === '' || $unidadeNome === '' || $setorNome === '' || $funcaoNome === '') {
                $errorsBlocking[] = 'Colaboradores: todos os campos obrigatórios devem estar preenchidos.';
                continue;
            }

            $grupoKey = $grupoNome;
            $empresaKey = $grupoNome . '|' . $empresaNome;
            $unidadeKey = $empresaKey . '|' . $unidadeNome;
            $setorKey = $unidadeKey . '|' . $setorNome;
            $funcaoKey = $setorKey . '|' . $funcaoNome;

            if (! isset($estruturaIndex['grupos'][$grupoKey])) {
                $errorsBlocking[] = "Colaboradores: grupo '{$grupoNome}' não encontrado na estrutura.";
                continue;
            }

            if (! isset($estruturaIndex['empresas'][$empresaKey])) {
                $errorsBlocking[] = "Colaboradores: empresa '{$empresaNome}' não encontrada na estrutura.";
                continue;
            }

            if (! isset($estruturaIndex['unidades'][$unidadeKey])) {
                $errorsBlocking[] = "Colaboradores: unidade '{$unidadeNome}' não encontrada na estrutura.";
                continue;
            }

            if (! isset($estruturaIndex['setores'][$setorKey])) {
                $errorsBlocking[] = "Colaboradores: setor '{$setorNome}' não encontrado na estrutura.";
                continue;
            }

            if (! isset($estruturaIndex['funcoes'][$funcaoKey])) {
                $errorsBlocking[] = "Colaboradores: função '{$funcaoNome}' não encontrada na estrutura.";
                continue;
            }

            $cpfHash = hash('sha256', preg_replace('/\D/', '', $cpf));
            $colaborador = Colaborador::where('cpf_hash', $cpfHash)->first();

            if (! $colaborador) {
                $report['novos']++;
            } else {
                $report['atualizar']++;
            }

            if ($email === '') {
                $report['usuarios_pendentes']++;
                $errorsInfo[] = "Colaboradores: CPF {$cpf} sem email, usuário ficará pendente.";
                continue;
            }

            $existingUser = User::where('email', $email)->first();

            if (! $existingUser) {
                $report['usuarios_criar']++;
            } else {
                $errorsAlert[] = "Colaboradores: email {$email} já existe, usuário será reutilizado.";
            }
        }

        return [
            'report' => $report,
            'errors_blocking' => $errorsBlocking,
            'errors_alert' => $errorsAlert,
            'errors_info' => $errorsInfo,
        ];
    }
}