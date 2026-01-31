<x-filament::page>
    <div class="space-y-6">
        <x-filament::section
            heading="Fluxo único de cadastro"
            description="Visualização hierárquica Grupo → Empresa → Unidade → Setor → Função → Colaborador. Ações reutilizam os Resources existentes e respeitam Policies.">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Use as ações contextuais para criar ou editar nós. Relacionamentos são pré-preenchidos via query string, sem duplicar formulários.
                </p>
                <div class="flex flex-wrap gap-2">
                    <x-filament::button color="gray" size="sm" icon="heroicon-o-arrow-path" wire:click="refreshTree">
                        Atualizar
                    </x-filament::button>

                    @if ($canCreateGrupo)
                        <x-filament::button tag="a" size="sm" icon="heroicon-o-plus"
                            href="{{ \App\Filament\Resources\Grupos\GrupoResource::getUrl('create') }}">
                            Novo Grupo
                        </x-filament::button>
                    @endif
                </div>
            </div>
        </x-filament::section>

        <div class="space-y-4">
            @forelse ($tree as $grupo)
                <div class="rounded-xl border border-gray-200/70 bg-white/70 shadow-sm ring-1 ring-gray-100/60 dark:border-white/10 dark:bg-gray-950/40 dark:ring-white/5">
                    <div class="flex items-start justify-between gap-3 px-4 py-3">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-gray-500">Grupo</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-50">{{ $grupo->nome }}</div>
                            <div class="text-xs text-gray-500">{{ $grupo->empresas->count() }} empresas</div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @can('update', $grupo)
                                <x-filament::button tag="a" size="xs" color="gray" icon="heroicon-o-pencil-square"
                                    href="{{ \App\Filament\Resources\Grupos\GrupoResource::getUrl('edit', ['record' => $grupo]) }}">
                                    Editar
                                </x-filament::button>
                            @endcan
                            @can('create', \App\Models\Empresa::class)
                                <x-filament::button tag="a" size="xs" icon="heroicon-o-building-office"
                                    href="{{ $this->getCreateEmpresaUrl($grupo->id) }}">
                                    Nova Empresa
                                </x-filament::button>
                            @endcan
                        </div>
                    </div>

                    @if ($grupo->empresas->isNotEmpty())
                        <div class="border-t border-gray-200/70 px-4 py-3 dark:border-white/10 space-y-3">
                            @foreach ($grupo->empresas as $empresa)
                                <div class="rounded-lg border border-gray-100 bg-white/80 px-3 py-2 shadow-xs dark:border-white/5 dark:bg-gray-900/40">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-[11px] uppercase text-gray-500">Empresa</div>
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $empresa->nome }}</div>
                                            <div class="text-[11px] text-gray-500">{{ $empresa->unidades->count() }} unidades</div>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @can('update', $empresa)
                                                <x-filament::button tag="a" size="xs" color="gray" icon="heroicon-o-pencil-square"
                                                    href="{{ \App\Filament\Resources\Empresas\EmpresaResource::getUrl('edit', ['record' => $empresa]) }}">
                                                    Editar
                                                </x-filament::button>
                                            @endcan
                                            @can('create', \App\Models\Unidade::class)
                                                <x-filament::button tag="a" size="xs" icon="heroicon-o-building-storefront"
                                                    href="{{ $this->getCreateUnidadeUrl($grupo->id, $empresa->id) }}">
                                                    Nova Unidade
                                                </x-filament::button>
                                            @endcan
                                        </div>
                                    </div>

                                    @if ($empresa->unidades->isNotEmpty())
                                        <div class="mt-2 space-y-2 border-l border-dashed border-gray-200 pl-3 dark:border-white/10">
                                            @foreach ($empresa->unidades as $unidade)
                                                <div class="rounded-lg bg-gray-50/70 px-3 py-2 dark:bg-gray-900/50">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div>
                                                            <div class="text-[11px] uppercase text-gray-500">Unidade</div>
                                                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $unidade->nome }}</div>
                                                            <div class="text-[11px] text-gray-500">{{ $unidade->setores->count() }} setores</div>
                                                        </div>
                                                        <div class="flex flex-wrap gap-2">
                                                            @can('update', $unidade)
                                                                <x-filament::button tag="a" size="xs" color="gray" icon="heroicon-o-pencil-square"
                                                                    href="{{ \App\Filament\Resources\Unidades\UnidadeResource::getUrl('edit', ['record' => $unidade]) }}">
                                                                    Editar
                                                                </x-filament::button>
                                                            @endcan
                                                            @can('create', \App\Models\Setor::class)
                                                                <x-filament::button tag="a" size="xs" icon="heroicon-o-rectangle-group"
                                                                    href="{{ $this->getCreateSetorUrl($grupo->id, $empresa->id, $unidade->id) }}">
                                                                    Novo Setor
                                                                </x-filament::button>
                                                            @endcan
                                                        </div>
                                                    </div>

                                                    @if ($unidade->setores->isNotEmpty())
                                                        <div class="mt-2 space-y-2 border-l border-dashed border-gray-200 pl-3 dark:border-white/10">
                                                            @foreach ($unidade->setores as $setor)
                                                                <div class="rounded-lg bg-white/80 px-3 py-2 shadow-inner dark:bg-gray-900/40">
                                                                    <div class="flex items-start justify-between gap-3">
                                                                        <div>
                                                                            <div class="text-[11px] uppercase text-gray-500">Setor</div>
                                                                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $setor->nome }}</div>
                                                                            <div class="text-[11px] text-gray-500">{{ $setor->funcoes->count() }} funções</div>
                                                                        </div>
                                                                        <div class="flex flex-wrap gap-2">
                                                                            @can('update', $setor)
                                                                                <x-filament::button tag="a" size="xs" color="gray" icon="heroicon-o-pencil-square"
                                                                                    href="{{ \App\Filament\Resources\Setors\SetorResource::getUrl('edit', ['record' => $setor]) }}">
                                                                                    Editar
                                                                                </x-filament::button>
                                                                            @endcan
                                                                            @can('create', \App\Models\Funcao::class)
                                                                                <x-filament::button tag="a" size="xs" icon="heroicon-o-briefcase"
                                                                                    href="{{ $this->getCreateFuncaoUrl($grupo->id, $empresa->id, $unidade->id, $setor->id) }}">
                                                                                    Nova Função
                                                                                </x-filament::button>
                                                                            @endcan
                                                                        </div>
                                                                    </div>

                                                                    @if ($setor->funcoes->isNotEmpty())
                                                                        <div class="mt-2 space-y-2 border-l border-dashed border-gray-200 pl-3 dark:border-white/10">
                                                                            @foreach ($setor->funcoes as $funcao)
                                                                                <div class="rounded-md bg-gray-50/90 px-3 py-2 dark:bg-gray-900/60">
                                                                                    <div class="flex items-start justify-between gap-3">
                                                                                        <div>
                                                                                            <div class="text-[11px] uppercase text-gray-500">Função</div>
                                                                                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $funcao->nome }}</div>
                                                                                            <div class="text-[11px] text-gray-500">{{ $funcao->colaboradores->count() }} colaboradores</div>
                                                                                        </div>
                                                                                        <div class="flex flex-wrap gap-2">
                                                                                            @can('update', $funcao)
                                                                                                <x-filament::button tag="a" size="xs" color="gray" icon="heroicon-o-pencil-square"
                                                                                                    href="{{ \App\Filament\Resources\Funcaos\FuncaoResource::getUrl('edit', ['record' => $funcao]) }}">
                                                                                                    Editar
                                                                                                </x-filament::button>
                                                                                            @endcan
                                                                                            @can('create', \App\Models\Colaborador::class)
                                                                                                <x-filament::button tag="a" size="xs" icon="heroicon-o-user-plus"
                                                                                                    href="{{ $this->getCreateColaboradorUrl($grupo->id, $empresa->id, $unidade->id, $setor->id, $funcao->id) }}">
                                                                                                    Novo Colaborador
                                                                                                </x-filament::button>
                                                                                            @endcan
                                                                                        </div>
                                                                                    </div>

                                                                                    @if ($funcao->colaboradores->isNotEmpty())
                                                                                        <div class="mt-2 grid gap-2 border-l border-dashed border-gray-200 pl-3 text-sm dark:border-white/10">
                                                                                            @foreach ($funcao->colaboradores as $colaborador)
                                                                                                <div class="flex items-center justify-between">
                                                                                                    <div class="truncate text-gray-900 dark:text-gray-100">
                                                                                                        {{ $colaborador->nome }}
                                                                                                    </div>
                                                                                                    @can('update', $colaborador)
                                                                                                        <x-filament::button tag="a" size="xs" color="gray"
                                                                                                            icon="heroicon-o-pencil-square"
                                                                                                            href="{{ \App\Filament\Resources\Colaboradors\ColaboradorResource::getUrl('edit', ['record' => $colaborador]) }}">
                                                                                                            Editar
                                                                                                        </x-filament::button>
                                                                                                    @endcan
                                                                                                </div>
                                                                                            @endforeach
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <x-filament::section>
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        Nenhum Grupo visível para o seu usuário. Verifique suas permissões ou crie um Grupo para iniciar a estrutura.
                    </div>
                </x-filament::section>
            @endforelse
        </div>
    </div>
</x-filament::page>
