<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Resources\Colaboradors\ColaboradorResource;
use App\Filament\Resources\Empresas\EmpresaResource;
use App\Filament\Resources\Funcaos\FuncaoResource;
use App\Filament\Resources\Grupos\GrupoResource;
use App\Filament\Resources\Setors\SetorResource;
use App\Filament\Resources\Unidades\UnidadeResource;
use App\Models\Grupo;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EstruturaOrganizacional extends Page
{
    protected static ?string $title = 'Estrutura Organizacional';

    protected static ?string $navigationLabel = 'Estrutura Organizacional';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?int $navigationSort = 6;

    protected static string|\UnitEnum|null $navigationGroup = 'Core do Sistema';

    protected static ?string $slug = 'estrutura-organizacional';

    protected string $view = 'filament.pages.estrutura-organizacional';

    public Collection $tree;

    public bool $canCreateGrupo = false;

    public static function shouldRegisterNavigation(): bool
    {
        return moduleEnabled('estrutura_organizacional');
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user?->can('viewAny', Grupo::class) ?? false;
    }

    public function mount(): void
    {
        $this->canCreateGrupo = Auth::user()?->can('create', Grupo::class) ?? false;
        $this->tree = $this->buildTree();
    }

    public function refreshTree(): void
    {
        $this->tree = $this->buildTree();
    }

    private function buildTree(): Collection
    {
        $user = Auth::user();

        if (! $user || ! $user->can('viewAny', Grupo::class)) {
            return collect();
        }

        $groups = Grupo::query()
            ->with([
                'empresas.unidades.setores.funcoes.colaboradores',
            ])
            ->orderBy('nome')
            ->get()
            ->filter(fn(Grupo $grupo) => $user->can('view', $grupo))
            ->values();

        return $groups->map(function (Grupo $grupo) use ($user) {
            $empresas = $grupo->empresas
                ->filter(fn($empresa) => $user->can('view', $empresa))
                ->map(function ($empresa) use ($user) {
                    $unidades = $empresa->unidades
                        ->filter(fn($unidade) => $user->can('view', $unidade))
                        ->map(function ($unidade) use ($user) {
                            $setores = $unidade->setores
                                ->filter(fn($setor) => $user->can('view', $setor))
                                ->map(function ($setor) use ($user) {
                                    $funcoes = $setor->funcoes
                                        ->filter(fn($funcao) => $user->can('view', $funcao))
                                        ->map(function ($funcao) use ($user) {
                                            $colaboradores = $funcao->colaboradores
                                                ->filter(fn($colaborador) => $user->can('view', $colaborador))
                                                ->values();

                                            return tap($funcao)->setRelation('colaboradores', $colaboradores);
                                        })
                                        ->values();

                                    return tap($setor)->setRelation('funcoes', $funcoes);
                                })
                                ->values();

                            return tap($unidade)->setRelation('setores', $setores);
                        })
                        ->values();

                    return tap($empresa)->setRelation('unidades', $unidades);
                })
                ->values();

            return tap($grupo)->setRelation('empresas', $empresas);
        });
    }

    public function getCreateEmpresaUrl(int $grupoId): string
    {
        return EmpresaResource::getUrl('create', ['grupo_id' => $grupoId]);
    }

    public function getCreateUnidadeUrl(int $grupoId, int $empresaId): string
    {
        return UnidadeResource::getUrl('create', [
            'grupo_id' => $grupoId,
            'empresa_id' => $empresaId,
        ]);
    }

    public function getCreateSetorUrl(int $grupoId, int $empresaId, int $unidadeId): string
    {
        return SetorResource::getUrl('create', [
            'grupo_id' => $grupoId,
            'empresa_id' => $empresaId,
            'unidade_id' => $unidadeId,
        ]);
    }

    public function getCreateFuncaoUrl(int $grupoId, int $empresaId, int $unidadeId, int $setorId): string
    {
        return FuncaoResource::getUrl('create', [
            'grupo_id' => $grupoId,
            'empresa_id' => $empresaId,
            'unidade_id' => $unidadeId,
            'setor_id' => $setorId,
        ]);
    }

    public function getCreateColaboradorUrl(int $grupoId, int $empresaId, int $unidadeId, int $setorId, int $funcaoId): string
    {
        return ColaboradorResource::getUrl('create', [
            'grupo_id' => $grupoId,
            'empresa_id' => $empresaId,
            'unidade_id' => $unidadeId,
            'setor_id' => $setorId,
            'funcao_id' => $funcaoId,
        ]);
    }
}
