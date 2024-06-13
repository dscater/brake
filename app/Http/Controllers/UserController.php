<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Ingreso;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Salida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public static $permisos = [
        "GERENTE" => [
            "usuarios.index",
            "usuarios.create",
            "usuarios.edit",
            "usuarios.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "categorias.index",
            "categorias.create",
            "categorias.edit",
            "categorias.destroy",

            "reportes.usuarios",
        ],
        "OPERADOR" => [],
    ];

    public static function getPermisosUser()
    {
        $array_permisos = self::$permisos;
        if ($array_permisos[Auth::user()->tipo]) {
            return $array_permisos[Auth::user()->tipo];
        }
        return [];
    }


    public static function verificaPermiso($permiso)
    {
        if (in_array($permiso, self::$permisos[Auth::user()->tipo])) {
            return true;
        }
        return false;
    }

    public function permisos(Request $request)
    {
        return response()->JSON([
            "permisos" => $this->permisos[Auth::user()->tipo]
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }

    public static function getInfoBoxUser()
    {
        $tipo = Auth::user()->tipo;
        $array_infos = [];
        if (in_array('usuarios.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Usuarios',
                'cantidad' => count(User::where('id', '!=', 1)->get()),
                'color' => 'bg-blue-darken-2',
                'icon' => asset("imgs/icon_users.png"),
                "url" => "usuarios.index"
            ];
        }
        if (in_array('proveedors.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Proveedores',
                'cantidad' => count(Proveedor::all()),
                'color' => 'bg-orange-darken-3',
                'icon' => asset("imgs/supplier.png"),
                "url" => "proveedors.index"
            ];
        }

        if (in_array('productos.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'productos',
                'cantidad' => count(Producto::where("status", 1)->get()),
                'color' => 'bg-grey-darken-2',
                'icon' => asset("imgs/box.png"),
                "url" => "productos.index"
            ];
        }

        if (in_array('ingresos.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'ingresos',
                'cantidad' => count(Ingreso::all()),
                'color' => 'bg-yellow-accent-3',
                'icon' => asset("imgs/in_stock.png"),
                "url" => "ingresos.index"
            ];
        }

        if (in_array('salidas.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'salidas',
                'cantidad' => count(Salida::all()),
                'color' => 'bg-indigo',
                'icon' => asset("imgs/delivery.png"),
                "url" => "salidas.index"
            ];
        }

        return $array_infos;
    }
}
