<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InfosGeraisController;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\ContratanteController;
use App\Livewire\Auth;
use App\Livewire\Contact;
use App\Livewire\RealtimeMessage;
use App\Http\Controllers\SuporteController;




// rota principal a dashboard
Route::get('/admin/DashboardAdmin', [AdminController::class, 'index'])->name('dashboard');

//todas as rotas para o login funcionar
Route::get('/',[loginController::class, 'index'])->name('login.index');
Route::post('/',[loginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('login.logout');
// Rotas cadastro login
Route::get('/login/register',[loginController::class, 'register'])->name('login.register');
Route::post('/login/auth',[loginController::class, 'authenticate'])->name('login.auth');
Route::post('/login/processo-registro',[loginController::class, 'processoDeRegistro'])->name('login.processoDeRegistro');



// -------------------------------ADD SERVICO----------------------------------------
Route::get('add/servico', [ServicoController::class, 'servico'])->name('add.servico');
Route::get('/servicos', [ServicoController::class, 'index'])->name('servicos.index');
Route::get('/editServico/{idServicos}', [ServicoController::class, 'edit'])->name('edit.servico');
Route::put('/editServico/{idServicos}', [ServicoController::class, 'update'])->name('update.servico');
Route::delete('/editServico/{idServicos}', [ServicoController::class, 'destroy'])->name('delete.servico');

// -------------------------------- CRIAR SERVICO---------------------------------------
Route::get('/criarServico', [ServicoController::class, 'create'])->name('criar.servico');
Route::post('/adicionar', [ServicoController::class, 'store'])->name('inserir.servico');



//--------------------------------- USUARIOS -------------------------------------------
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::delete('/users/contratante/{idContratante}', [UsersController::class, 'destroy'])->name('users.deleteContratante');
Route::delete('/users/contratado/{idContratado}', [UsersController::class, 'destroyContratado'])->name('users.deleteContratado');
Route::delete('/users/admin/{idAdmin}', [UsersController::class, 'destroyAdmin'])->name('users.deleteAdmin');



// --------------------------------USUARIOS PAGINA ADM----------------------------------
Route::get('/adm' ,[UsersController::class, 'userAdm']) ->name('users.admins');
Route::get('/editadmin/{id}' ,[UsersController::class, 'edit']) ->name('edit.admins');
Route::put('/editadmin/{id}' ,[UsersController::class, 'update']) ->name('update.admins');
Route::delete('/editadmin/{id}' ,[UsersController::class, 'delete']) ->name('delete.admins');
Route::put('/users/suspend/{id}', [UsersController::class, 'toggleSuspension'])->name('users.toggleSuspension');


// --------------------------------USUARIOS PAGINA USUARIOS----------------------------------
Route::get('/clientes' ,[UsersController::class, 'clientes']) ->name('users.clientes');




//-----------------------------PAGINA DE INFOS GERAIS ------------------------------------------
Route::get('/infosgerais' ,[InfosGeraisController::class, 'indexInfos']) ->name('financeiro.controle');



Route::get('/atendimentos' ,[AtendimentoController::class, 'index']) ->name('atendimento');
Route::patch('/denuncia/{id}/acao', [AtendimentoController::class, 'acaoAnalise']);
Route::post('/denuncia/{id}/enviar-email', [AtendimentoController::class, 'send'])->name('atendimento.send');
Route::put('/denuncia/{id}/toggle-suspension', [AtendimentoController::class, 'toggleSuspensionFromDenuncia'])->name('denuncia.toggleSuspension');



Route::get('/loginChat', Auth::class)->name('login');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/contact', Contact::class)->name('contact');
    Route::get('/message/{roomId}', RealtimeMessage::class)->name('message');
});



