<?php

namespace App\Http\Controllers;

use App\Models\bedelias;
use Illuminate\Http\Request;
use App\Http\Controllers\usuariosController;
use App\Models\usuarios;
use Illuminate\Support\Facades\DB;
use App\Services\Files;
use Illuminate\Support\Facades\App;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
class BedeliaController extends Controller
{
    public function index(Request $request)
    {
        if($request->eliminados){
            $bedeliasEliminados = DB::table('usuarios')
            ->select('*')
            ->where('deleted_at', '!=', null)
            ->where('ou', 'Bedelias')
            ->get();
            return response()->json($bedeliasEliminados);
        }
        $resultado=DB::table('usuarios')
        ->select('usuarios.id', 'usuarios.nombre', 'usuarios.email', 'usuarios.ou', 'usuarios.genero', 'bedelias.cargo')
        ->join('bedelias', 'usuarios.id', '=', 'bedelias.id')
        ->whereNull('usuarios.deleted_at')
        ->get();
        return response()->json($resultado);
    }
    public function show($id){
        
        $bedelia = bedelias::findOrFail($id)->load('usuario');
        if(App::environment(['production', 'local'])){
            $filesService = new Files();
            $bedelia->usuario['imagen_perfil'] = $filesService->getImage($bedelia->usuario['imagen_perfil']);
        }
      
        return $bedelia;
    }

    public function update(Request $request, $id)
    {
       $request->validate([
            'nombre' => 'string',
            'apellido' => 'string',
            'email' => 'string',
            'genero' => 'string',
            'cargo' => 'string',
       ]);


        $usuarioController = new usuariosController();
        $bedelia = bedelias::find($id);
        if($bedelia->cargo != $request->cargo){
            $usuarioController->eliminarUsuarioGrupoAD($bedelia->id, $bedelia->cargo);
            $usuarioController->agregarUsuarioGrupoAD($bedelia->id, $request->cargo);
        }
        if(empty($bedelia)){
            return response()->json(['error' => 'Usuario no encontrada'], 404);
        }
        $bedelia->update($request->all());
       
        return $usuarioController->update($request, $id);
    }

   

    public function agregarUsuarioGrupoAD($idUsuario, $grupo){

        $group = Group::find('cn='.$grupo.',ou=Grupos,dc=syntech,dc=intra');

        $user = User::find('cn='.$idUsuario.',ou=UsuarioSistema,dc=syntech,dc=intra');
        
        $group->members()->attach($user);

    }

    
}
