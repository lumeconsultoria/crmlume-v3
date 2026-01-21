<?php

namespace App\Filament\Ops\Pages;

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
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DryRunImportacao extends Page
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Dry-Run Importação';

    protected static ?string $navigationLabel = 'Dry-Run Importação';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Importação';

    protected string $view = 'filament.ops.pages.dry-run-importacao';

    public ?array $data = [];

    public array $report = [];

    public array $errors = [];

    public static function canAccess(): bool
    {
        return false;
    }

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

            if (count($data) === 1 && trim((string) $data[0]) === '') {
                continue;
            }

            if (count($data) !== count($header)) {
                $errors[] = "{$label}: linha {$line} com quantidade de colunas inválida.";
                continue;
            }

            $row = [];
            foreach ($header as $index => $column) {
                $row[$column] = trim((string) $data[$index]);
            }

            $rows[] = $row;
        }

        fclose($handle);

        return [
            'rows' => $rows,
            'errors' => $errors,
        ];
    }

    private function analisarEstrutura(array $rows): array
    {
        $errors = [];

        $gruposCriar = [];
        $empresasCriar = [];
        $unidadesCriar = [];
        $setoresCriar = [];
        $funcoesCriar = [];

        $index = [
            'grupos' => [],
            'empresas' => [],
            'unidades' => [],
            'setores' => [],
            'funcoes' => [],
        ];

        foreach ($rows as $rowIndex => $row) {
            $linha = $rowIndex + 2;

            $grupo = $row['Grupo_Nome'] ?? '';
            $empresa = $row['Empresa_Nome'] ?? '';
            $unidade = $row['Unidade_Nome'] ?? '';
            $setor = $row['Setor_Nome'] ?? '';
            $funcao = $row['Funcao_Nome'] ?? '';

            if ($grupo === '' || $empresa === '' || $unidade === '' || $setor === '' || $funcao === '') {
                $errors[] = "Estrutura: linha {$linha} com campos obrigatórios ausentes.";
                continue;
            }

            $grupoModel = Grupo::query()->where('nome', $grupo)->first();

            if (! $grupoModel) {
                $gruposCriar[$grupo] = $grupo;
            }

            $index['grupos'][$grupo] = true;

            $empresaModel = null;
            if ($grupoModel) {
                $empresaModel = Empresa::query()
                    ->where('nome', $empresa)
                    ->where('grupo_id', $grupoModel->id)
                    ->first();

                $empresaConflict = Empresa::query()->where('nome', $empresa)->where('grupo_id', '!=', $grupoModel->id)->exists();
                if ($empresaConflict) {
                    $errors[] = "Estrutura: linha {$linha} - Empresa '{$empresa}' não pertence ao Grupo '{$grupo}'.";
                    continue;
                }
            }

            if (! $empresaModel) {
                $empresasCriar[$grupo . '|' . $empresa] = $empresa;
            }

            $index['empresas'][$grupo . '|' . $empresa] = true;

            $unidadeModel = null;
            if ($empresaModel) {
                $unidadeModel = Unidade::query()
                    ->where('nome', $unidade)
                    ->where('empresa_id', $empresaModel->id)
                    ->first();

                $unidadeConflict = Unidade::query()->where('nome', $unidade)->where('empresa_id', '!=', $empresaModel->id)->exists();
                if ($unidadeConflict) {
                    $errors[] = "Estrutura: linha {$linha} - Unidade '{$unidade}' não pertence à Empresa '{$empresa}'.";
                    continue;
                }
            }

            if (! $unidadeModel) {
                $unidadesCriar[$empresa . '|' . $unidade] = $unidade;
            }

            $index['unidades'][$empresa . '|' . $unidade] = true;

            $setorModel = null;
            if ($unidadeModel) {
                $setorModel = Setor::query()
                    ->where('nome', $setor)
                    ->where('unidade_id', $unidadeModel->id)
                    ->first();

                $setorConflict = Setor::query()->where('nome', $setor)->where('unidade_id', '!=', $unidadeModel->id)->exists();
                if ($setorConflict) {
                    $errors[] = "Estrutura: linha {$linha} - Setor '{$setor}' não pertence à Unidade '{$unidade}'.";
                    continue;
                }
            }

            if (! $setorModel) {
                $setoresCriar[$unidade . '|' . $setor] = $setor;
            }

            $index['setores'][$unidade . '|' . $setor] = true;

            $funcaoModel = null;
            if ($setorModel) {
                $funcaoModel = Funcao::query()
                    ->where('nome', $funcao)
                    ->where('setor_id', $setorModel->id)
                    ->first();

                $funcaoConflict = Funcao::query()->where('nome', $funcao)->where('setor_id', '!=', $setorModel->id)->exists();
                if ($funcaoConflict) {
                    $errors[] = "Estrutura: linha {$linha} - Função '{$funcao}' não pertence ao Setor '{$setor}'.";
                    continue;
                }
            }

            if (! $funcaoModel) {
                $funcoesCriar[$setor . '|' . $funcao] = $funcao;
            }

            $index['funcoes'][$setor . '|' . $funcao] = true;
        }

        return [
            'report' => [
                'grupos' => array_values($gruposCriar),
                'empresas' => array_values($empresasCriar),
                'unidades' => array_values($unidadesCriar),
                'setores' => array_values($setoresCriar),
                'funcoes' => array_values($funcoesCriar),
            ],
            'index' => $index,
            'errors' => $errors,
        ];
    }

    private function analisarColaboradores(array $rows, array $estruturaIndex): array
    {
        $errorsBlocking = [];
        $errorsAlert = [];
        $errorsInfo = [];

        $total = 0;
        $novos = 0;
        $atualizar = 0;
        $usuariosCriar = 0;
        $usuariosPendentes = 0;

        $colaboradoresVistos = [];

        foreach ($rows as $rowIndex => $row) {
            $linha = $rowIndex + 2;

            $grupo = $row['Grupo_Nome'] ?? '';
            $empresa = $row['Empresa_Nome'] ?? '';
            $unidade = $row['Unidade_Nome'] ?? '';
            $setor = $row['Setor_Nome'] ?? '';
            $funcao = $row['Funcao_Nome'] ?? '';
            $nome = $row['Colaborador_Nome'] ?? '';
            $email = $row['Usuario_Email'] ?? '';
            $ativo = $row['Colaborador_Ativo'] ?? '';
            $usuarioAtivo = $row['Usuario_Ativo'] ?? '';

            $total++;

            if ($grupo === '' || $empresa === '' || $unidade === '' || $setor === '' || $funcao === '' || $nome === '') {
                $errorsBlocking[] = "Colaboradores: linha {$linha} com campos obrigatórios ausentes.";
                continue;
            }

            if (! $this->estruturaResolvida($estruturaIndex, $grupo, $empresa, $unidade, $setor, $funcao)) {
                $errorsBlocking[] = "Colaboradores: linha {$linha} com estrutura inválida ou incompleta.";
                continue;
            }

            if (! $this->isBooleanOrEmpty($ativo)) {
                $errorsBlocking[] = "Colaboradores: linha {$linha} com Colaborador_Ativo inválido (use true/false).";
                continue;
            }

            if (! $this->isBooleanOrEmpty($usuarioAtivo)) {
                $errorsBlocking[] = "Colaboradores: linha {$linha} com Usuario_Ativo inválido (use true/false).";
                continue;
            }

            $chave = mb_strtolower($nome) . '|' . mb_strtolower($empresa) . '|' . mb_strtolower($unidade);
            if (isset($colaboradoresVistos[$chave])) {
                $errorsAlert[] = "Colaboradores: linha {$linha} duplicada (Nome + Empresa + Unidade).";
                continue;
            }

            $colaboradoresVistos[$chave] = true;

            $colaboradorExistente = $this->buscarColaboradorExistente($grupo, $empresa, $unidade, $nome);

            if ($colaboradorExistente) {
                $atualizar++;
            } else {
                $novos++;
            }

            if ($email === '') {
                $usuariosPendentes++;
                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorsAlert[] = "Colaboradores: linha {$linha} com email inválido (usuario ficará pendente).";
                $usuariosPendentes++;
                continue;
            }

            if ($colaboradorExistente) {
                $userExiste = User::query()->where('colaborador_id', $colaboradorExistente->id)->exists();
                if (! $userExiste) {
                    $usuariosCriar++;
                }
            } else {
                $usuariosCriar++;
            }
        }

        return [
            'report' => [
                'total' => $total,
                'novos' => $novos,
                'atualizar' => $atualizar,
                'usuarios_criar' => $usuariosCriar,
                'usuarios_pendentes' => $usuariosPendentes,
            ],
            'errors_blocking' => $errorsBlocking,
            'errors_alert' => $errorsAlert,
            'errors_info' => $errorsInfo,
        ];
    }

    private function estruturaResolvida(array $index, string $grupo, string $empresa, string $unidade, string $setor, string $funcao): bool
    {
        if (! isset($index['grupos'][$grupo])) {
            $grupoExiste = Grupo::query()->where('nome', $grupo)->exists();
            if (! $grupoExiste) {
                return false;
            }
        }

        $empresaKey = $grupo . '|' . $empresa;
        if (! isset($index['empresas'][$empresaKey])) {
            $grupoModel = Grupo::query()->where('nome', $grupo)->first();
            if (! $grupoModel) {
                return false;
            }

            $empresaExiste = Empresa::query()
                ->where('nome', $empresa)
                ->where('grupo_id', $grupoModel->id)
                ->exists();

            if (! $empresaExiste) {
                return false;
            }
        }

        $unidadeKey = $empresa . '|' . $unidade;
        if (! isset($index['unidades'][$unidadeKey])) {
            $empresaModel = Empresa::query()
                ->where('nome', $empresa)
                ->first();

            if (! $empresaModel) {
                return false;
            }

            $unidadeExiste = Unidade::query()
                ->where('nome', $unidade)
                ->where('empresa_id', $empresaModel->id)
                ->exists();

            if (! $unidadeExiste) {
                return false;
            }
        }

        $setorKey = $unidade . '|' . $setor;
        if (! isset($index['setores'][$setorKey])) {
            $unidadeModel = Unidade::query()->where('nome', $unidade)->first();
            if (! $unidadeModel) {
                return false;
            }

            $setorExiste = Setor::query()
                ->where('nome', $setor)
                ->where('unidade_id', $unidadeModel->id)
                ->exists();

            if (! $setorExiste) {
                return false;
            }
        }

        $funcaoKey = $setor . '|' . $funcao;
        if (! isset($index['funcoes'][$funcaoKey])) {
            $setorModel = Setor::query()->where('nome', $setor)->first();
            if (! $setorModel) {
                return false;
            }

            $funcaoExiste = Funcao::query()
                ->where('nome', $funcao)
                ->where('setor_id', $setorModel->id)
                ->exists();

            if (! $funcaoExiste) {
                return false;
            }
        }

        return true;
    }

    private function buscarColaboradorExistente(string $grupo, string $empresa, string $unidade, string $nome): ?Colaborador
    {
        $grupoModel = Grupo::query()->where('nome', $grupo)->first();
        if (! $grupoModel) {
            return null;
        }

        $empresaModel = Empresa::query()
            ->where('nome', $empresa)
            ->where('grupo_id', $grupoModel->id)
            ->first();

        if (! $empresaModel) {
            return null;
        }

        $unidadeModel = Unidade::query()
            ->where('nome', $unidade)
            ->where('empresa_id', $empresaModel->id)
            ->first();

        if (! $unidadeModel) {
            return null;
        }

        return Colaborador::query()
            ->where('nome', $nome)
            ->where('empresa_id', $empresaModel->id)
            ->where('unidade_id', $unidadeModel->id)
            ->first();
    }

    private function isBooleanOrEmpty(string $value): bool
    {
        if ($value === '') {
            return true;
        }

        $normalized = mb_strtolower($value);

        return in_array($normalized, ['true', 'false'], true);
    }
}
