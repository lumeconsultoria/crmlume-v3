<?php

namespace App\Console\Commands;

use App\Services\ImportacaoDefinitivaService;
use Illuminate\Console\Command;

class ImportarIndexmedCommand extends Command
{
    protected $signature = 'crm:importar-indexmed {--confirmar : Confirma a execução da importação definitiva}';

    protected $description = 'Importação definitiva a partir dos CSVs do diretório _temp_gpt_schema';

    public function handle(ImportacaoDefinitivaService $service): int
    {
        if (! $this->option('confirmar')) {
            $this->error('Execução não confirmada. Use --confirmar para prosseguir.');
            return self::FAILURE;
        }

        $estruturaPath = base_path('_temp_gpt_schema/estrutura_organizacional_crmlume_v1.csv');
        $colaboradoresPath = base_path('_temp_gpt_schema/colaboradores_crmlume_v1.csv');

        if (! is_readable($estruturaPath)) {
            $this->error('Arquivo de estrutura não encontrado ou ilegível: ' . $estruturaPath);
            return self::FAILURE;
        }

        if (! is_readable($colaboradoresPath)) {
            $this->error('Arquivo de colaboradores não encontrado ou ilegível: ' . $colaboradoresPath);
            return self::FAILURE;
        }

        $estruturaRows = $this->readCsv($estruturaPath);
        $colaboradoresRows = $this->readCsv($colaboradoresPath);

        $summary = $service->run($estruturaRows, $colaboradoresRows, true);

        $this->info('Importação definitiva concluída.');
        $this->line('Resumo:');
        foreach ($summary as $key => $value) {
            $label = str_replace('_', ' ', (string) $key);
            $this->line("- {$label}: {$value}");
        }

        return self::SUCCESS;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function readCsv(string $path): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('Não foi possível abrir o CSV: ' . $path);
        }

        $header = fgetcsv($handle);

        if (! is_array($header)) {
            fclose($handle);
            throw new \RuntimeException('Cabeçalho CSV não encontrado: ' . $path);
        }

        $header = array_map(function (string $value): string {
            $value = trim($value);
            $value = preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;
            return trim($value, '"');
        }, $header);

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === 1 && trim((string) $data[0]) === '') {
                continue;
            }

            $row = [];
            foreach ($header as $index => $column) {
                $row[$column] = trim((string) ($data[$index] ?? ''));
            }

            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }
}
