<?php

namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Menu;

class AdminController extends Controller
{
    protected $p_rep;
    protected $s_rep;
    protected $a_rep;
    protected $c_rep;
    protected $m_rep;
    protected $user;
    protected $template;
    protected $content = false;
    protected $title;
    protected $vars;
        public function __construct()
        {
            $user=$this->middleware(function ($request, $next) {
                $this->user = Auth::user();
                return $next($request);
            });


            if(!$user) {
                abort(403);
            }

        }

    public function renderOutPut()
    {
        $this->vars = array_add($this->vars, 'title',$this->title);
        $menu = $this->getMenu();
        $navigation = view(env('THEME').'.admin.navigation')->with('menu',$menu)->render();
        $this->vars = array_add($this->vars, 'navigation',$navigation);
        $footer = view(env('THEME').'.footer')->render();
        $this->vars = array_add($this->vars, 'footer', $footer);
        if ($this->content) {
            $this->vars = array_add($this->vars, 'content',$this->content);

        }
        return view($this->template)->with($this->vars);
    }

    public function getMenu()
    {
        return Menu::make('adminMenu', function($menu){
            $menu->add('Статьи',array('route' =>'adminArticles.index'));
            $menu->add('Портфолио',array('route' =>'adminArticles.index'));
            $menu->add('Меню',array('route' =>'adminMenus.index'));
            $menu->add('Пользователи',array('route' =>'adminUsers.index'));
            $menu->add('Привелегии',array('route' =>'adminPermissions.index'));
        });
    }
}
