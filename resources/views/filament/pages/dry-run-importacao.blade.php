<x-filament::page>
    <div class="space-y-6">
        <x-filament::section>
            {{ $this->form }}

            <div class="mt-4">
                <x-filament::button wire:click="executeDryRun">
                    Executar Dry-Run
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section heading="Relatório Final">
            <div class="grid gap-4 md:grid-cols-2">
                <x-filament::card>
                    <h3 class="text-base font-semibold">Estrutura Organizacional</h3>
                    <div class="mt-3 space-y-1 text-sm">
                        <div><strong>Grupos a criar:</strong> {{ count($report['estrutura']['grupos'] ?? []) }}</div>
                        <div><strong>Empresas a criar:</strong> {{ count($report['estrutura']['empresas'] ?? []) }}
                        </div>
                        <div><strong>Unidades a criar:</strong> {{ count($report['estrutura']['unidades'] ?? []) }}
                        </div>
                        <div><strong>Setores a criar:</strong> {{ count($report['estrutura']['setores'] ?? []) }}</div>
                        <div><strong>Funções a criar:</strong> {{ count($report['estrutura']['funcoes'] ?? []) }}</div>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <h3 class="text-base font-semibold">Colaboradores</h3>
                    <div class="mt-3 space-y-1 text-sm">
                        <div><strong>Total lidos:</strong> {{ $report['colaboradores']['total'] ?? 0 }}</div>
                        <div><strong>Novos colaboradores:</strong> {{ $report['colaboradores']['novos'] ?? 0 }}</div>
                        <div><strong>Colaboradores a atualizar:</strong> {{ $report['colaboradores']['atualizar'] ?? 0
                            }}</div>
                        <div><strong>Usuários que seriam criados:</strong> {{ $report['colaboradores']['usuarios_criar']
                            ?? 0 }}</div>
                        <div><strong>Usuários pendentes (sem email):</strong> {{
                            $report['colaboradores']['usuarios_pendentes'] ?? 0 }}</div>
                    </div>
                </x-filament::card>
            </div>
        </x-filament::section>

        <x-filament::section heading="Erros do Dry-Run">
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-semibold">Bloqueantes</h4>
                    @if (! empty($report['erros']['bloqueante']))
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($report['erros']['bloqueante'] as $erro)
                        <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-sm text-gray-600">Nenhum erro bloqueante encontrado.</div>
                    @endif
                </div>

                <div>
                    <h4 class="text-sm font-semibold">Alertas</h4>
                    @if (! empty($report['erros']['alerta']))
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($report['erros']['alerta'] as $erro)
                        <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-sm text-gray-600">Nenhum alerta encontrado.</div>
                    @endif
                </div>

                <div>
                    <h4 class="text-sm font-semibold">Informativos</h4>
                    @if (! empty($report['erros']['informativo']))
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($report['erros']['informativo'] as $erro)
                        <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-sm text-gray-600">Nenhum informativo.</div>
                    @endif
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament::page>