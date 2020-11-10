<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function signupAdministrador(Request $request){
        /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
        /* Esta función crea un usuario administrador */

        $request->validate([
            'user_name' => 'required|string',
            'user_email' => 'required|string|email|unique:users',
            'user_password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => bcrypt($request->user_password),
            'profile_id' => 1
        ]);
        $user->save();
        return response()->json([
            'message' => 'Usuario Administrador creado con éxito!'
        ], 201);
    }

    public function signupCliente(Request $request){
            /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
        /* Esta función crea un usuario cliente */

        $request->validate([
            'user_name' => 'required|string',
            'user_email' => 'required|string|email|unique:users',
            'user_password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password' => bcrypt($request->user_password),
            'profile_id' => 2
        ]);
        $user->save();
        return response()->json([
            'message' => 'Usuario Cliente creado con éxito!'
        ], 201);
    }

    public function login(Request $request){
        /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    
        $request->validate([
            'user_email' => 'required|string|email',
            'user_password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        //$credentials = request(['user_email', 'user_password']);
        $credentials = array(
                            'user_email' => $request->user_email,
                            'password' => $request->user_password );
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'No autorizado'
            ], 401);
        //return response()->json('bien',200);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'profile_id' =>$user->profile_id
        ]);
    }

    public function logout(Request $request)
    {
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Se ha deslogueado con éxito'
        ]);
    }

    
    public function user(Request $request){
        /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
        return response()->json($request->user());
    }

    public function update(Request $request){
         /* Esta función modifica los datos del usuario logueado .
        Parámetros: recibe el 'request' que tendrá los datos que se modificaron.
        Return: Devuelve un mensaje indicando si se pudo realizar la operación de actualización
         */

        $user = $request->user();

        $validaData = $request->validate([
            'user_name' => 'required|string',
            'user_email' => 'required|string|email|unique:users',
            'user_password' => 'required|string|confirmed'
        ]);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar al usuario logueado'
            ], 400);
        }

        $data = array(
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_password'=>bcrypt($request->user_password)
        );  

        $updated = $user->update($data);

        if ($updated)
        return response()->json([
            'success' => true,
            'message' => 'Los datos se han modificado con éxito'

        ],200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Los datos del usuario no pudieron actualizarse'
            ], 500);
    }

}
