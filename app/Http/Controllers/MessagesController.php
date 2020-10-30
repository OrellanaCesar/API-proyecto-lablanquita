<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MessageReceived;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
	public function store(Request $request){
      // return response()->json(request(),200);
		$mensaje = request()->validate([
			'nombre' =>'required',
			'apellido' => 'required',
			'email' => 'required|email',
			'provincia' => 'required',
			'comentario' => 'required']);


		Mail::to('pablofacundoorellana@gmail.com')->send(new MessageReceived($mensaje));

	}
}

