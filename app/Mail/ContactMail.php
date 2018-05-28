<?php

namespace Corp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @param Request $request
     * @return $this
     */
    public function build(Request $request)
    {
        $data = $request->all();
        return $this->from($data['email'],$data['name'])
            ->view(env('THEME').'.email',['data'=>$data]);
    }

}
