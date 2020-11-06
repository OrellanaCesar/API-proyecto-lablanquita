<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use DataTables;

class UserController extends Controller
{
    //



	public function dataTableUsers()
	{  

        /*Esta funcion devuelve un DataTable con los datos de los usarios y sus perfiles
        Parametros : no se le pasa ningun parametro
        Return : una tabla con los atributos de las marcas ordenadas por nombres */
        $u = User::select('users.user_id','users.user_name','users.user_email',
        	'users.user_create_date','user_change_date','profiles.profile_name')
        ->join('profiles','users.profile_id','=','profiles.profile_id')
        ->orderby('user_create_date')                
        ->get();
        return DataTables::of($u)->addColumn('accion', function($b){
        	return '';
        })->make(true);
    }
}
