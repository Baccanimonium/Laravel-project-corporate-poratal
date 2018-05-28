<?php

namespace Corp\Http\Controllers\Admin;

use Corp\Category;
use Corp\Filter;
use Corp\Http\Requests\MenuRequest;
use Corp\Portfolio;
use Menu;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;

class MenusController extends AdminController
{
    protected $m_rep;
    public function __construct(MenusRepository $menusRepository, ArticlesRepository $articlesRepository,PortfoliosRepository $portfoliosRepository)
    {
        parent::__construct();
        $this->m_rep = $menusRepository;
        $this->a_rep = $articlesRepository;
        $this->p_rep = $portfoliosRepository;
        $this->template = env('THEME') . '.admin.menus';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = $this->getMenus();
        $this->content = view(env('THEME') . '.admin.menus_content')->with('menu', $menu)->render();
        return $this->renderOutPut();

    }

    public function getMenus()
    {
        $menu = $this->m_rep->get();

        if ($menu->isEmpty()) {
            return false;
        }
       return Menu::make('forMenuPart', function ($m) use($menu) {
            foreach ($menu as $item) {
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)) {
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }

            }
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = 'Новый пункт меню';
        $tmp = $this->getMenus()->roots();
        $menus = $tmp->reduce(function ($returnMenus, $menu){
            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        },['0'=>'Родительский пункт меню']);
        $categories = Category::select(['title','alias','parent_id','id'])->get();
        $list = array();
        $list = array_add($list, '0','Не используется');
        $list = array_add($list, 'parent','Раздел Блог');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title]= array();
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        $articles = $this->a_rep->get(['id','title','alias']);
        $articles=$articles->reduce(function ($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        },[]);
        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilters, $filter){
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        },['parent'=> 'Раздел портфолио']);
        $portfolios = Portfolio::select('id', 'title', 'alias')->get()->reduce(function ($returnPortfolios, $portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },['parent'=> 'Раздел портфолио']);
        $this->content = view(env('THEME') . '.admin.menus_create_content')->with(['menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();
        return $this->renderOutPut();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        $result = $this->m_rep->addMenu($request);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin/adminMenus')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Corp\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(\Corp\Menu $adminMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Corp\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(\Corp\Menu $adminMenu)
    {
        $type=false;
        $option = false;



        $route = app('router')->getRoutes()->match(app('request')->create($adminMenu->path));
        $aliasRoute = $route->getName();
        $parameters = $route->parameters();

        if ($aliasRoute=='articles.index' || $aliasRoute== 'articlesCat') {
            $type = 'blogLink';
            $option = isset($parameters['cat_alias'])? $parameters['cat_alias']: 'parent';
        }else if ($aliasRoute == 'articles.show') {
            $type = 'blogLink';
            $option = isset($parameters['alias'])? $parameters['alias']: '';
        }else if ($aliasRoute == 'portfolios.index') {
            $type = 'portfolioLink';
            $option = 'parent';
        }else if ($aliasRoute == 'portfolios.show') {
            $type = 'portfolioLink';
            $option =  isset($parameters['alias'])? $parameters['alias']: '';
        }else{
            $type = 'customLink';
        }
        $this->title = 'Новый пункт редактирование ссылки '.$this->title;
        $tmp = $this->getMenus()->roots();
        $menus = $tmp->reduce(function ($returnMenus, $menu){
            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        },['0'=>'Родительский пункт меню']);
        $categories = Category::select(['title','alias','parent_id','id'])->get();
        $list = array();
        $list = array_add($list, '0','Не используется');
        $list = array_add($list, 'parent','Раздел Блог');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title]= array();
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        $articles = $this->a_rep->get(['id','title','alias']);
        $articles=$articles->reduce(function ($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        },[]);
        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilters, $filter){
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        },['parent'=> 'Раздел портфолио']);
        $portfolios = Portfolio::select('id', 'title', 'alias')->get()->reduce(function ($returnPortfolios, $portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },['parent'=> 'Раздел портфолио']);
        $this->content = view(env('THEME') . '.admin.menus_create_content')->with(['type'=>$type,'option'=>$option,'menu'=>$adminMenu,'menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();
        return $this->renderOutPut();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Corp\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, \Corp\Menu $adminMenu)
    {
        $result = $this->m_rep->updateMenu($request, $adminMenu);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin/adminMenus')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Corp\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(\Corp\Menu $adminMenu)
    {
        $result = $this->m_rep->deleteMenu($adminMenu);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin/adminMenus')->with($result);
    }
}
