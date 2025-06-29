<?php

use App\Models\MetaFinanceira;
use App\Models\MovimentacaoMeta;
use App\Models\User;

it('retorna zero quando valor objetivo for zero', function () {
    $meta = new MetaFinanceira();
    $meta->valor_atual = 500;
    $meta->valor_objetivo = 0;

    expect($meta->progresso)->toBe(0.0);
});

it('retorna zero quando valor objetivo for negativo', function () {
    $meta = new MetaFinanceira();
    $meta->valor_atual = 500;
    $meta->valor_objetivo = -100;

    expect($meta->progresso)->toBe(0.0);
});

it('calcula progresso corretamente quando valor atual for menor que objetivo', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 250,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(25.0);
});
it('calcula progresso corretamente quando valor atual for igual ao objetivo', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 1000,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(100.0);
});

it('calcula progresso corretamente quando valor atual for maior que objetivo', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test2@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 1500,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(150.0);
});

it('retorna zero quando valor atual for zero', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test3@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    // Não criar movimentação para valor atual = 0

    expect($meta->progresso)->toBe(0.0);
});

it('calcula progresso negativo quando valor atual for negativo', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test4@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 100,
        'tipo' => 'retirada',
        'descricao' => 'Retirada teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(-10.0);
});

it('arredonda resultado para duas casas decimais', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test5@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 333.333,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(33.33);
});

it('calcula progresso com valores decimais', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test6@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 500.50,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 123.45,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(24.67);
});

it('funciona com valores muito pequenos', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test7@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 0.10,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 0.01,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(10.0);
});

it('funciona com valores muito grandes', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test8@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 5000000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 1000000,
        'tipo' => 'deposito',
        'descricao' => 'Depósito teste',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(20.0);
});

it('calcula 50% de progresso', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test_50@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 500,
        'tipo' => 'deposito',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(50.0);
});

it('calcula 75% de progresso', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test_75@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 750,
        'tipo' => 'deposito',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(75.0);
});

it('calcula 25% de progresso com objetivo diferente', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test_25@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 800,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 200,
        'tipo' => 'deposito',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(25.0);
});

it('calcula 120% quando ultrapassa objetivo', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test_120@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $meta = MetaFinanceira::create([
        'usuario_id' => $user->id,
        'titulo' => 'Meta Teste',
        'valor_objetivo' => 1000,
        'data_inicio' => now(),
        'data_objetivo' => now()->addDays(30),
        'categoria' => 'outros',
        'status' => 'ativo',
    ]);
    
    MovimentacaoMeta::create([
        'meta_id' => $meta->id,
        'valor' => 1200,
        'tipo' => 'deposito',
        'data_movimentacao' => now(),
    ]);

    expect($meta->progresso)->toBe(120.0);
});