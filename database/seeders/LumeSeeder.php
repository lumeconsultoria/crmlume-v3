<?php

namespace Database\Seeders;

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class LumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar grupo LUME
        $grupo = Grupo::create([
            'nome' => 'LUME',
            'ativo' => true,
        ]);

        // Criar empresa LUME
        $empresa = Empresa::create([
            'grupo_id' => $grupo->id,
            'nome' => 'LUME',
            'ativo' => true,
        ]);

        // Criar unidade Matriz
        $unidade = Unidade::create([
            'empresa_id' => $empresa->id,
            'nome' => 'Matriz',
            'ativo' => true,
        ]);

        // Criar setor Administrativo
        $setor = Setor::create([
            'unidade_id' => $unidade->id,
            'nome' => 'Administrativo',
            'ativo' => true,
        ]);

        // Criar funções
        $funcaoAdministrativo = Funcao::create([
            'setor_id' => $setor->id,
            'nome' => 'Administrativo',
            'ativo' => true,
        ]);

        $funcaoRH = Funcao::create([
            'setor_id' => $setor->id,
            'nome' => 'RH',
            'ativo' => true,
        ]);

        $funcaoComercial = Funcao::create([
            'setor_id' => $setor->id,
            'nome' => 'Comercial',
            'ativo' => true,
        ]);

        // Criar roles
        $roleCEO = Role::firstOrCreate(['name' => 'ceo', 'guard_name' => 'web']);
        $roleRH = Role::firstOrCreate(['name' => 'rh', 'guard_name' => 'web']);

        // Criar colaborador CEO
        $colaboradorCEO = Colaborador::create([
            'funcao_id' => $funcaoAdministrativo->id,
            'unidade_id' => $unidade->id,
            'empresa_id' => $empresa->id,
            'nome' => 'CEO LUME',
            'ativo' => true,
        ]);

        // Criar user CEO
        $userCEO = User::create([
            'colaborador_id' => $colaboradorCEO->id,
            'name' => 'CEO LUME',
            'email' => 'ceo@lume.com.br',
            'password' => Hash::make('password'),
            'ativo' => true,
        ]);
        $userCEO->assignRole($roleCEO);

        // Criar colaborador RH
        $colaboradorRH = Colaborador::create([
            'funcao_id' => $funcaoRH->id,
            'unidade_id' => $unidade->id,
            'empresa_id' => $empresa->id,
            'nome' => 'RH LUME',
            'ativo' => true,
        ]);

        // Criar user RH
        $userRH = User::create([
            'colaborador_id' => $colaboradorRH->id,
            'name' => 'RH LUME',
            'email' => 'rh@lume.com.br',
            'password' => Hash::make('password'),
            'ativo' => true,
        ]);
        $userRH->assignRole($roleRH);
    }
}
