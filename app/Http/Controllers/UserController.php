<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use DataTables;
use App\Mail\MessageProduct;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

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

    public function usersClients()
	{  

        /*Esta funcion devuelve un DataTable con los datos de los usarios  cliente
        Parametros : no se le pasa ningun parametro
        Return : una tabla con los atributos de las marcas ordenadas por nombres */
        
        $u = User::orderby('user_create_date')
                ->where('profile_id','=',2)                
                ->get();
        return DataTables::of($u)->addColumn('accion', function($b){
        	return '';
        })->make(true);
    }


    public function sendMailClients(Request $request, $type)
    {
        $data = array();
        $data['type'] = $type;
        if ($type == 0) {
            $products = Product::all();
            $data['products'] = $products;
        } else {
            if ($type == 1) {
                $products = Product::where('product_offer_day','=',true)
                            ->with('category:category_id,category_name','brand:brand_id,brand_name')
                            ->orderby('product_offer_day_order','asc')
                            ->get();
                $data['products'] = $products;
            } else {
                $products = Product::where('product_best_seller','=',true)
                            ->with('category:category_id,category_name','brand:brand_id,brand_name')
                            ->orderby('product_best_seller_order','asc')
                            ->get();

                $data['products'] = $products;
            }
            
        }
        Mail::to($request->user_email)->send(new MessageProduct($data));
    }


    public function destroy($id)
    {

        $user = User::find($id);
        if ($user->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'La Marca no pudo ser borrada'
            ], 500);
        }
    }

    public function getUsers(){

        $u = User::select('users.user_id','users.user_name','users.user_email',
            'users.user_create_date','user_change_date','profiles.profile_name')
        ->join('profiles','users.profile_id','=','profiles.profile_id')
        ->orderby('user_create_date')                
        ->get();
        return response()->json($u, 200);
        
    }



}
