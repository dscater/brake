<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
});

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::middleware('auth')->group(function () {
    // INICIO
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');
    Route::get("/inicio/getMaximoImagenes", [InicioController::class, 'getMaximoImagenes'])->name("entrenamientos.getMaximoImagenes");

    // INSTITUCION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("/getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("/permisos", [UserController::class, 'permisos']);
    Route::get("/menu_user", [UserController::class, 'permisos']);

    // USUARIOS
    Route::put("/usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("/usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("/usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("/usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("/usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("/usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("/usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // CATEGORIAS
    Route::get("/categorias/paginado", [CategoriaController::class, 'paginado'])->name("categorias.paginado");
    Route::get("/categorias/listado", [CategoriaController::class, 'listado'])->name("categorias.listado");
    Route::resource("categorias", CategoriaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");
});

require __DIR__ . '/auth.php';
