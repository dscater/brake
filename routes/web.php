<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\IngresoProductoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SalidaProductoController;
use App\Http\Controllers\TipoProductoController;
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

    // CONCEPTOS
    Route::get("/conceptos/paginado", [ConceptoController::class, 'paginado'])->name("conceptos.paginado");
    Route::get("/conceptos/byCategoria", [ConceptoController::class, 'byCategoria'])->name("conceptos.byCategoria");
    Route::get("/conceptos/listado", [ConceptoController::class, 'listado'])->name("conceptos.listado");
    Route::resource("conceptos", ConceptoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // INGRESOS
    Route::get("/ingresos/paginado", [IngresoController::class, 'paginado'])->name("ingresos.paginado");
    Route::get("/ingresos/listado", [IngresoController::class, 'listado'])->name("ingresos.listado");
    Route::resource("ingresos", IngresoController::class)->only(
        ["index", "create", "store", "edit", "update", "show", "destroy"]
    );

    // EGRESOS
    Route::get("/egresos/paginado", [EgresoController::class, 'paginado'])->name("egresos.paginado");
    Route::get("/egresos/listado", [EgresoController::class, 'listado'])->name("egresos.listado");
    Route::resource("egresos", EgresoController::class)->only(
        ["index", "create", "store", "edit", "update", "show", "destroy"]
    );

    // TIPO DE PRODUCTOS
    Route::get("/tipo_productos/paginado", [TipoProductoController::class, 'paginado'])->name("tipo_productos.paginado");
    Route::get("/tipo_productos/listado", [TipoProductoController::class, 'listado'])->name("tipo_productos.listado");
    Route::resource("tipo_productos", TipoProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // PRODUCTOS
    Route::get("/productos/byTipoProducto", [ProductoController::class, 'byTipoProducto'])->name("productos.byTipoProducto");
    Route::get("/productos/paginado", [ProductoController::class, 'paginado'])->name("productos.paginado");
    Route::get("/productos/listado", [ProductoController::class, 'listado'])->name("productos.listado");
    Route::resource("productos", ProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // INGRESO DE PRODUCTOS
    Route::get("/ingreso_productos/paginado", [IngresoProductoController::class, 'paginado'])->name("ingreso_productos.paginado");
    Route::get("/ingreso_productos/listado", [IngresoProductoController::class, 'listado'])->name("ingreso_productos.listado");
    Route::resource("ingreso_productos", IngresoProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // SALIDA DE PRODUCTOS
    Route::get("/salida_productos/paginado", [SalidaProductoController::class, 'paginado'])->name("salida_productos.paginado");
    Route::get("/salida_productos/listado", [SalidaProductoController::class, 'listado'])->name("salida_productos.listado");
    Route::resource("salida_productos", SalidaProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");

    Route::get('reportes/ingresos', [ReporteController::class, 'ingresos'])->name("reportes.ingresos");
    Route::get('reportes/r_ingresos', [ReporteController::class, 'r_ingresos'])->name("reportes.r_ingresos");

    Route::get('reportes/egresos', [ReporteController::class, 'egresos'])->name("reportes.egresos");
    Route::get('reportes/r_egresos', [ReporteController::class, 'r_egresos'])->name("reportes.r_egresos");

    Route::get('reportes/presupuestos', [ReporteController::class, 'presupuestos'])->name("reportes.presupuestos");
    Route::get('reportes/r_presupuestos', [ReporteController::class, 'r_presupuestos'])->name("reportes.r_presupuestos");

    Route::get('reportes/ganancias', [ReporteController::class, 'ganancias'])->name("reportes.ganancias");
    Route::get('reportes/r_ganancias', [ReporteController::class, 'r_ganancias'])->name("reportes.r_ganancias");

    Route::get('reportes/movimientos', [ReporteController::class, 'movimientos'])->name("reportes.movimientos");
    Route::get('reportes/r_movimientos', [ReporteController::class, 'r_movimientos'])->name("reportes.r_movimientos");
    Route::get('reportes/rg_movimientos', [ReporteController::class, 'rg_movimientos'])->name("reportes.rg_movimientos");
    
    Route::get('reportes/productos', [ReporteController::class, 'productos'])->name("reportes.productos");
    Route::get('reportes/r_productos', [ReporteController::class, 'r_productos'])->name("reportes.r_productos");
    
    Route::get('reportes/ingreso_productos', [ReporteController::class, 'ingreso_productos'])->name("reportes.ingreso_productos");
    Route::get('reportes/r_ingreso_productos', [ReporteController::class, 'r_ingreso_productos'])->name("reportes.r_ingreso_productos");
    
    Route::get('reportes/salida_productos', [ReporteController::class, 'salida_productos'])->name("reportes.salida_productos");
    Route::get('reportes/r_salida_productos', [ReporteController::class, 'r_salida_productos'])->name("reportes.r_salida_productos");
    
    Route::get('reportes/kardex_productos', [ReporteController::class, 'kardex_productos'])->name("reportes.kardex_productos");
    Route::get('reportes/r_kardex_productos', [ReporteController::class, 'r_kardex_productos'])->name("reportes.r_kardex_productos");
    Route::get('reportes/rg_kardex_productos', [ReporteController::class, 'rg_kardex_productos'])->name("reportes.rg_kardex_productos");
});

require __DIR__ . '/auth.php';
