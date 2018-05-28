<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;
use Corp\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends SiteController
{
    public function __construct ()
    {
        parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu()));

        $this->bar='left';
        $this->template = env('THEME').'.contacts';
    }

    public function index(Request $request)
    {
        if($request->isMethod('post')) {

            $messages = [

                'required' => "Поле :attribute обязательно к заполнению",
                'email' => "Поле :attribute должно соответствовать email адресу"

            ];

            $this->validate($request,[

                'name' => 'required|max:255',
                'email' => 'required|email',


            ], $messages);


            $result = $this->send();


            if($result) {
                return redirect()->route('contacts')->with('status', 'Email is send');
            }

        }
        $this->title = 'Контакты';
        $content = view(env('THEME').'.contact_content')->render();
        $this->vars = array_add($this->vars, 'content', $content);
        $this->contentLeftBar = view(env('THEME') . '.contactBar')->render();
        return $this->renderOutput();
    }

    protected function send()
    {
        $mail_admin = env('MAIL_ADMIN');
        Mail::to($mail_admin,'Mr. Admin')->send(new ContactMail);
        return true;
    }

}
