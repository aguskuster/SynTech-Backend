<?php

use Illuminate\Http\Request;
use App\Models\usuarios;
use Illuminate\Support\Facades\Route;
use LdapRecord\Models\ActiveDirectory\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers;
use Carbon\Carbon;
use App\Models\materia;

use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login','App\Http\Controllers\loginController@connect');

Route::get('/test', function (){
    $materia = User::all();
    return $materia;
});
// FTP TRAER ARCHIVOS
Route::get('/traerArchivo','App\Http\Controllers\MaterialPublicoController@traerArchivo')->middleware('verificar_token');
Route::get('/foto','App\Http\Controllers\loginController@traerImagenPerfil');
Route::get('/historial','App\Http\Controllers\usuariosController@getFullHistory');

//USUARIOS

Route::apiResource('usuario', 'App\Http\Controllers\usuariosController');
/* Route::get('/usuarios','App\Http\Controllers\usuariosController@index');

Route::get('/usuario','App\Http\Controllers\usuariosController@show')->middleware('verificar_token');

Route::post('/usuario','App\Http\Controllers\usuariosController@create');
Route::delete('/usuario','App\Http\Controllers\usuariosController@destroy')->middleware('verificar_token');
Route::put('/usuario','App\Http\Controllers\usuariosController@update')->middleware('verificar_token');
 */
Route::post('/foto','App\Http\Controllers\usuariosController@cambiarFotoUsuario')->middleware('verificar_token');

Route::put('/contrasenia','App\Http\Controllers\usuariosController@cambiarContrasenia')->middleware('verificar_token');

Route::post('/usuariosintoken','App\Http\Controllers\usuariosController@create');

//GRUPOS
Route::get('/grupos','App\Http\Controllers\gruposController@index')->middleware('verificar_token');
Route::get('/grupo','App\Http\Controllers\gruposController@show')->middleware('verificar_token');

Route::post('/grupo','App\Http\Controllers\gruposController@create')->middleware('verificar_token');
Route::delete('/grupo','App\Http\Controllers\gruposController@destroy')->middleware('verificar_token');
Route::put('/grupo','App\Http\Controllers\gruposController@update')->middleware('verificar_token');

Route::get('/materiaSinGrupo','App\Http\Controllers\gruposTienenProfesorController@traerMateriasSinGrupo')->middleware('verificar_token');



//ALUMNOS 
Route::get('/alumnos','App\Http\Controllers\agregarUsuarioGrupoController@index')->middleware('verificar_token');

Route::post('/alumno','App\Http\Controllers\agregarUsuarioGrupoController@store')->middleware('verificar_token');

Route::delete('/alumno','App\Http\Controllers\agregarUsuarioGrupoController@destroy')->middleware('verificar_token');

//MATERIAS 

Route::get('/materias','App\Http\Controllers\agregarMateriaController@index')->middleware('verificar_token');
Route::get('/materia','App\Http\Controllers\agregarMateriaController@show')->middleware('verificar_token');

Route::post('/materia','App\Http\Controllers\agregarMateriaController@store')->middleware('verificar_token');

Route::put('/materia','App\Http\Controllers\agregarMateriaController@update')->middleware('verificar_token');

Route::delete('/materia','App\Http\Controllers\agregarMateriaController@destroy')->middleware('verificar_token');

Route::get('/materia-profesores','App\Http\Controllers\profesorDictaMateriaController@todosProfesorSegunMateria');
Route::get('/profesorMateria','App\Http\Controllers\gruposTienenProfesorController@mostrarProfesorMateria')->middleware('verificar_token');


// PROFESOR
Route::get('/profesor','App\Http\Controllers\profesorDictaMateriaController@index')->middleware('verificar_token');

Route::post('/profesor','App\Http\Controllers\profesorDictaMateriaController@agregarListaDeProfesoresMateria')->middleware('verificar_token');

Route::delete('/profesor','App\Http\Controllers\profesorDictaMateriaController@destroy')->middleware('verificar_token');

Route::get('/profesores','App\Http\Controllers\profesorDictaMateriaController@listarProfesores')->middleware('verificar_token');


// CURSOS 

Route::post('/curso','App\Http\Controllers\gruposTienenProfesorController@store')->middleware('verificar_token');

Route::get('/curso','App\Http\Controllers\gruposTienenProfesorController@show')->middleware('verificar_token');

Route::delete('/curso','App\Http\Controllers\gruposTienenProfesorController@destroy')->middleware('verificar_token');


Route::get('/grupo-materia','App\Http\Controllers\gruposTienenProfesorController@index')->middleware('verificar_token');

//
Route::get('/integrantes-curso','App\Http\Controllers\gruposTienenProfesorController@listarIntegrantesDeUnGrupo')->middleware('verificar_token');


//FORO
Route::delete('/grupoForo','App\Http\Controllers\gruposTienenProfesorController@eliminarProfesorGrupoForo')->middleware('verificar_token');
Route::delete('/foro','App\Http\Controllers\gruposTienenProfesorController@eliminarForo')->middleware('verificar_token');

//NOTICIAS
Route::post('/noticia','App\Http\Controllers\MaterialPublicoController@store')->middleware('verificar_token');
Route::get('/noticia','App\Http\Controllers\MaterialPublicoController@index')->middleware('verificar_token');
Route::delete('/noticia','App\Http\Controllers\MaterialPublicoController@destroy')->middleware('verificar_token');

