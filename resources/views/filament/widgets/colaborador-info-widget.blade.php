<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $colaborador = $this->getColaborador();
        @endphp

        @if($colaborador)
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-full bg-primary-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($colaborador->nome, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">{{ $colaborador->nome }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $colaborador->funcao->nome }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Empresa</p>
                        <p class="text-base">{{ $colaborador->empresa->nome }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Unidade</p>
                        <p class="text-base">{{ $colaborador->unidade->nome }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Setor</p>
                        <p class="text-base">{{ $colaborador->funcao->setor->nome }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Função</p>
                        <p class="text-base">{{ $colaborador->funcao->nome }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Status</p>
                        <p class="text-base">
                            @if($colaborador->ativo)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">
                                    Inativo
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">Nenhum colaborador vinculado a este usuário.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
