<?php

namespace Corp\Http\Controllers;

use Corp\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    public function __construct (PortfoliosRepository $p_rep)
    {
        parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu()));

        $this->p_rep = $p_rep;
        $this->template = env('THEME').'.portfolios';
    }
    public function index()
    {
        $this->title = 'Портфолио';
        $this->meta_description = 'краткоек описание';
        $this->keywords = 'ключевые слова';
        $portfolios = $this->getPortfolios();

        $content = view(env('THEME').'.portfolios_content')->with('portfolios',$portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();

    }

    public function getPortfolios($take = false, $paginate = true)
    {
        $portfolios = $this->p_rep->get(['title','text','alias','customer','img','filter_alias','keywords','meta_desc'], $take, $paginate);
        if ($portfolios) {
            $portfolios->load('filter');
        }
        return $portfolios;
    }
    public function show ($alias = false)
    {

        $portfolio = $this->p_rep->one($alias);

        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_description = $portfolio->meta_desc;
        $portfolios = $this->getPortfolios(\config('settings.other_portfolios'), false);
        $content = view((env('THEME') . '.portfolio_content'))->
            with(['portfolio'=> $portfolio,'portfolios'=>$portfolios])->render();
        $this->vars = array_add($this->vars, 'content', $content);


        return $this->renderOutput();
    }
}
