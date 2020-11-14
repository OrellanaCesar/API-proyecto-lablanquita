<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageProduct extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = '';
    public $products;
    public $test ;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        if ($data['type'] == 0) {
            $this->subject = 'Nuestros Productos';
        }else{
            if ($data['type'] == 1) {
                $this->subject = 'Nuestras Ofertas del dia';
            }else{
                $this->subject = 'Nuestros Productos Destacados';
            }
        }
        $this->test = 'hola mundo';
        $this->products = $data['products'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.messageclients')->with('products',$this->products)->with('titulo',$this->subject);
        return $this->view('emails.messageclients');
    }
}