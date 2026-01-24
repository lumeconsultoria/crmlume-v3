<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class HomologacaoSeeder extends Seeder
{
    public function run(): void
    {
        // Garante que o cache de permissões/roles esteja limpo antes de criar/atribuir
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Criar roles primeiro (todas os guards) para evitar RoleDoesNotExist
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'rh', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin_lume', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'administrativo_lume', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'adm_lume', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'vendedor_lume', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'gestor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'colaborador', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'ceo', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Roles para painel OPS (guard ops)
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'ops']);
        Role::firstOrCreate(['name' => 'rh', 'guard_name' => 'ops']);
        Role::firstOrCreate(['name' => 'gestor', 'guard_name' => 'ops']);
        Role::firstOrCreate(['name' => 'vendedor_lume', 'guard_name' => 'ops']);
        Role::firstOrCreate(['name' => 'colaborador', 'guard_name' => 'ops']);

        // Roles para painel Colaborador (guard colaborador)
        Role::firstOrCreate(['name' => 'colaborador', 'guard_name' => 'colaborador']);

        // =============================
        // 3 empresas, 2 colaboradores cada
        // =============================
        for ($i = 1; $i <= 3; $i++) {
            $grupo = Grupo::firstOrCreate(
                ['nome' => "GRUPO TESTE {$i}"],
                ['ativo' => true]
            );
            $empresa = Empresa::firstOrCreate(
                ['nome' => "EMPRESA TESTE {$i}"],
                ['grupo_id' => $grupo->id, 'ativo' => true]
            );
            $unidade = Unidade::firstOrCreate(
                ['empresa_id' => $empresa->id, 'nome' => "FILIAL TESTE {$i}"],
                ['ativo' => true]
            );
            $setor = Setor::firstOrCreate(
                ['unidade_id' => $unidade->id, 'nome' => "SETOR TESTE {$i}"],
                ['ativo' => true]
            );
            $funcao = Funcao::firstOrCreate(
                ['setor_id' => $setor->id, 'nome' => "CARGO TESTE {$i}"],
                ['ativo' => true]
            );

            for ($j = 1; $j <= 2; $j++) {
                $nome = "Colaborador {$j} Empresa {$i}";
                $email = "colab{$j}.empresa{$i}@teste.com";
                $cpf = sprintf('100%d%d%d%d%d%d-%02d', $i, $j, $i, $j, $i, $j, $i*10+$j);
                $cpfHash = hash('sha256', preg_replace('/\D/', '', $cpf));
                $colaborador = Colaborador::firstOrCreate(
                    ['cpf_hash' => $cpfHash],
                    [
                        'funcao_id' => $funcao->id,
                        'unidade_id' => $unidade->id,
                        'empresa_id' => $empresa->id,
                        'nome' => $nome,
                        'data_nascimento' => "1990-0{$i}-1{$j}",
                        'ativo' => true,
                    ]
                );
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'colaborador_id' => $colaborador->id,
                        'name' => $nome,
                        'password' => Hash::make('senha123'),
                        'ativo' => true,
                    ]
                );
                $user->syncRoles(['colaborador']);
                $user->assignRole(Role::findByName('colaborador', 'ops'));
                echo "✓ Usuário {$nome} criado (Email: {$email})\n";
            }
        }

        // ========================================
        // COLABORADOR 1: Anderson (Primeiro Acesso)
        // ========================================
        $cpfAnderson = '203.923.158-74'; // CPF fictício válido
        $cpfAndersonHash = hash('sha256', '20392315874');

        $colaboradorAnderson = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfAndersonHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Anderson Silva',
                'data_nascimento' => Carbon::parse('1977-08-13'),
                'ativo' => true,
            ]
        );

        $this->command->info("✓ Colaborador Anderson criado (ID: {$colaboradorAnderson->id})");
        $this->command->info("  Nome: {$colaboradorAnderson->nome}");
        $this->command->info("  CPF: {$cpfAnderson}");
        $this->command->info("  Data Nascimento: 13/08/1977");

        // ========================================
        // COLABORADOR 2: Maria (Primeiro Acesso)
        // ========================================
        $cpfMaria = '123.456.789-09'; // CPF fictício válido
        $cpfMariaHash = hash('sha256', '12345678909');

        $colaboradorMaria = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfMariaHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Maria Santos',
                'data_nascimento' => Carbon::parse('1990-05-20'),
                'ativo' => true,
            ]
        );

        $this->command->info("✓ Colaborador Maria criado (ID: {$colaboradorMaria->id})");
        $this->command->info("  Nome: {$colaboradorMaria->nome}");
        $this->command->info("  CPF: {$cpfMaria}");
        $this->command->info("  Data Nascimento: 20/05/1990");

        // ========================================
        // USUÁRIO ADMIN/RH: João Admin
        // ========================================
        $cpfJoao = '987.654.321-00'; // CPF fictício válido
        $cpfJoaoHash = hash('sha256', '98765432100');

        $colaboradorAdmin = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfJoaoHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - João Administrador',
                'data_nascimento' => Carbon::parse('1985-03-15'),
                'ativo' => true,
            ]
        );

        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@teste.com'],
            [
                'colaborador_id' => $colaboradorAdmin->id,
                'name' => 'TESTE - João Administrador',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userAdmin->syncRoles(['admin', 'rh']);
        $userAdmin->assignRole(Role::findByName('rh', 'ops'));

        $this->command->info("✓ Usuário Admin criado (ID: {$userAdmin->id})");
        $this->command->info("  Nome: {$userAdmin->name}");
        $this->command->info("  Email: admin@teste.com");
        $this->command->info("  Senha: senha123");

        // ========================================
        // USUÁRIO GESTOR
        // ========================================
        $cpfGestor = '321.654.987-01';
        $cpfGestorHash = hash('sha256', '32165498701');

        $colaboradorGestor = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfGestorHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Carla Gestora',
                'data_nascimento' => Carbon::parse('1988-11-05'),
                'ativo' => true,
            ]
        );

        $userGestor = User::firstOrCreate(
            ['email' => 'gestor@teste.com'],
            [
                'colaborador_id' => $colaboradorGestor->id,
                'name' => 'TESTE - Carla Gestora',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userGestor->syncRoles(['gestor']);
        $userGestor->assignRole(Role::findByName('gestor', 'ops'));

        $this->command->info("✓ Usuário Gestor criado (ID: {$userGestor->id})");
        $this->command->info('  Email: gestor@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // USUÁRIO VENDEDOR
        // ========================================
        $cpfVendedor = '741.852.963-00';
        $cpfVendedorHash = hash('sha256', '74185296300');

        $colaboradorVendedor = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfVendedorHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Paulo Vendedor',
                'data_nascimento' => Carbon::parse('1992-02-18'),
                'ativo' => true,
            ]
        );

        $userVendedor = User::firstOrCreate(
            ['email' => 'vendedor@teste.com'],
            [
                'colaborador_id' => $colaboradorVendedor->id,
                'name' => 'TESTE - Paulo Vendedor',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userVendedor->syncRoles(['vendedor_lume']);
        $userVendedor->assignRole(Role::findByName('vendedor_lume', 'ops'));

        $this->command->info("✓ Usuário Vendedor criado (ID: {$userVendedor->id})");
        $this->command->info('  Email: vendedor@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // USUÁRIO ADMIN LUME
        // ========================================
        $cpfAdminLume = '159.357.456-82';
        $cpfAdminLumeHash = hash('sha256', '15935745682');

        $colaboradorAdminLume = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfAdminLumeHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Ana Admin Lume',
                'data_nascimento' => Carbon::parse('1983-07-22'),
                'ativo' => true,
            ]
        );

        $userAdminLume = User::firstOrCreate(
            ['email' => 'adminlume@teste.com'],
            [
                'colaborador_id' => $colaboradorAdminLume->id,
                'name' => 'TESTE - Ana Admin Lume',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userAdminLume->syncRoles(['admin_lume']);

        $this->command->info("✓ Usuário Admin Lume criado (ID: {$userAdminLume->id})");
        $this->command->info('  Email: adminlume@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // USUÁRIO SUPER ADMIN
        // ========================================
        $cpfSuperAdmin = '852.456.321-19';
        $cpfSuperAdminHash = hash('sha256', '85245632119');

        $colaboradorSuperAdmin = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfSuperAdminHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Sérgio Super Admin',
                'data_nascimento' => Carbon::parse('1979-12-01'),
                'ativo' => true,
            ]
        );

        $userSuperAdmin = User::firstOrCreate(
            ['email' => 'super@teste.com'],
            [
                'colaborador_id' => $colaboradorSuperAdmin->id,
                'name' => 'TESTE - Sérgio Super Admin',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userSuperAdmin->syncRoles(['super_admin']);
        $userSuperAdmin->assignRole(Role::findByName('super_admin', 'ops'));

        $this->command->info("✓ Usuário Super Admin criado (ID: {$userSuperAdmin->id})");
        $this->command->info('  Email: super@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // USUÁRIO ADMINISTRATIVO LUME
        // ========================================
        $cpfAdministrativo = '456.789.123-55';
        $cpfAdministrativoHash = hash('sha256', '45678912355');

        $colaboradorAdministrativo = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfAdministrativoHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Beatriz Administrativo',
                'data_nascimento' => Carbon::parse('1986-04-09'),
                'ativo' => true,
            ]
        );

        $userAdministrativo = User::firstOrCreate(
            ['email' => 'administrativo@teste.com'],
            [
                'colaborador_id' => $colaboradorAdministrativo->id,
                'name' => 'TESTE - Beatriz Administrativo',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userAdministrativo->syncRoles(['administrativo_lume']);

        $this->command->info("✓ Usuário Administrativo criado (ID: {$userAdministrativo->id})");
        $this->command->info('  Email: administrativo@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // USUÁRIO ADM LUME
        // ========================================
        $cpfAdmLume = '963.258.741-33';
        $cpfAdmLumeHash = hash('sha256', '96325874133');

        $colaboradorAdmLume = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfAdmLumeHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Ricardo Adm Lume',
                'data_nascimento' => Carbon::parse('1981-10-27'),
                'ativo' => true,
            ]
        );

        $userAdmLume = User::firstOrCreate(
            ['email' => 'admlume@teste.com'],
            [
                'colaborador_id' => $colaboradorAdmLume->id,
                'name' => 'TESTE - Ricardo Adm Lume',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userAdmLume->syncRoles(['adm_lume']);

        $this->command->info("✓ Usuário Adm Lume criado (ID: {$userAdmLume->id})");
        $this->command->info('  Email: admlume@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // USUÁRIO CEO
        // ========================================
        $cpfCeo = '741.369.258-77';
        $cpfCeoHash = hash('sha256', '74136925877');

        $colaboradorCeo = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfCeoHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Helena CEO',
                'data_nascimento' => Carbon::parse('1976-06-14'),
                'ativo' => true,
            ]
        );

        $userCeo = User::firstOrCreate(
            ['email' => 'ceo@teste.com'],
            [
                'colaborador_id' => $colaboradorCeo->id,
                'name' => 'TESTE - Helena CEO',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userCeo->syncRoles(['ceo']);
        // ========================================
        // USUÁRIO COLABORADOR (OPS + COLABORADOR)
        // ========================================
        $cpfColaborador = '593.271.846-05';
        $cpfColaboradorHash = hash('sha256', '59327184605');

        $colaboradorOps = Colaborador::firstOrCreate(
            ['cpf_hash' => $cpfColaboradorHash],
            [
                'funcao_id' => $funcao->id,
                'unidade_id' => $unidade->id,
                'empresa_id' => $empresa->id,
                'nome' => 'TESTE - Joana Colaboradora',
                'data_nascimento' => Carbon::parse('1994-09-02'),
                'ativo' => true,
            ]
        );

        $userColaborador = User::firstOrCreate(
            ['email' => 'colaborador.ops@teste.com'],
            [
                'colaborador_id' => $colaboradorOps->id,
                'name' => 'TESTE - Joana Colaboradora',
                'password' => Hash::make('senha123'),
                'ativo' => true,
            ]
        );

        $userColaborador->assignRole(Role::findByName('colaborador', 'ops'));
        $userColaborador->assignRole(Role::findByName('colaborador', 'colaborador'));

        $this->command->info("✓ Usuário Colaborador criado (ID: {$userColaborador->id})");
        $this->command->info('  Email: colaborador.ops@teste.com');
        $this->command->info('  Senha: senha123');

        $this->command->info("✓ Usuário CEO criado (ID: {$userCeo->id})");
        $this->command->info('  Email: ceo@teste.com');
        $this->command->info('  Senha: senha123');

        // ========================================
        // RESUMO
        // ========================================
        $this->command->newLine();
        $this->command->info('==========================================');
        $this->command->info('DADOS DE HOMOLOGAÇÃO CRIADOS COM SUCESSO!');
        $this->command->info('==========================================');
        $this->command->newLine();

        $this->command->info('📋 PARA TESTAR PRIMEIRO ACESSO:');
        $this->command->info('  URL: http://127.0.0.1:8000/primeiro-acesso');
        $this->command->newLine();

        $this->command->info('  👤 Colaborador 1 - Anderson:');
        $this->command->info("     CPF: {$cpfAnderson}");
        $this->command->info('     Data Nascimento: 13/08/1977');
        $this->command->newLine();

        $this->command->info('  👤 Colaborador 2 - Maria:');
        $this->command->info("     CPF: {$cpfMaria}");
        $this->command->info('     Data Nascimento: 20/05/1990');
        $this->command->newLine();

        $this->command->info('🔐 PARA TESTAR LOGIN ADMIN:');
        $this->command->info('  URL: http://127.0.0.1:8000/admin/login');
        $this->command->info('  Email: admin@teste.com');
        $this->command->info('  Senha: senha123');
        $this->command->newLine();
    }
}
