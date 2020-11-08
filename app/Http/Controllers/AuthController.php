<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\MessageRecover;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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


    public function esconderEmail($email)
    {
        $email_new = $email;
        for ($i=0; $i < strlen($email) ; $i++) { 
            if ( ($i >= 4) && ($i<strlen($email)-10)){
                $email_new[$i] = '*';
            }
        }
        return $email_new;   
    }

    public function recoverPass(Request $request)
    {
        $request->validate([
            'user_email' => 'required|string|email'
        ]);
        
        $user = User::where('user_email','=',$request->user_email)
                ->get();
        if (sizeof($user) == 0 ) {
            
            return response()->json([
                "message" => "No existe usuario con ese email",
                "status" => 500]
                , 500);
        }else{
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $pass = substr(str_shuffle($permitted_chars),0,8);
            $mensaje = array(
                'password' => $pass
            );
            // return response()->json($user[0]->user_email, 200);
            $data = array(
                'user_password' => bcrypt($pass)
            );
            $id = $user[0]->user_id;
            $user = User::find($id);
            // return response()->json($user, 200);
            $updated = $user->update($data);
            if ($updated) {
                Mail::to($user->user_email)->send(new MessageRecover($mensaje));
                return response()->json([
                    "message" => "Se envio una nueva contraseña al correo ".$this->esconderEmail($user->user_email), 
                    "status" => 200], 200);            
            }else{
                return response()->json([
                    "message" => "Hubo en error al actualizar su contraseña. Por favor intente nuevamente.",
                    "status" => 404], 404);
            }
            
            
        }
        
    }

    


}
