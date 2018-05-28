<?php

namespace Corp\Http\Controllers\Admin;

use Corp\Article;
use Corp\Category;
use Corp\Http\Requests\ArticleRequest;
use Corp\Repositories\ArticlesRepository;
use Corp\User;
use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;

class ArticlesController extends AdminController
{

    public function __construct(ArticlesRepository $a_rep)
    {
        parent::__construct();
        $this->a_rep = $a_rep;
        $this->template = env('THEME').'.admin.articles';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->title = 'Менеджер статей';
        $articles = $this->getArticles();
        $this->content = view(env('THEME').'.admin.articles_content')->with('articles',$articles)->render();
        return $this->renderOutPut();
    }

    public function getArticles()
    {
        return $this->a_rep->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $this->authorize('ADD_ARTICLES', $user);
        $this->title = "Добавить новый материал";
        $categories = Category::select(['title','alias','parent_id','id'])->get();
        $lists = array();
        foreach ($categories as $category) {
            if($category->parent_id == 0) {
                $lists[$category->title] = array();
            }
            else {
                $lists[$categories->where('id',$category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->content = view(env('THEME').'.admin.articles_create_content')->with('categories',$lists)->render();


        return $this->renderOutPut();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        //
        $result = $this->a_rep->addArticle($request);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin/adminArticles')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $alias
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user,Article $adminArticle)
    {
        $this->authorize('UPDATE_ARTICLES', $user);
        $adminArticle->img = json_decode($adminArticle->img);
        $categories = Category::select(['title','alias','parent_id','id'])->get();
        $lists = array();
        foreach ($categories as $category) {
            if($category->parent_id == 0) {
                $lists[$category->title] = array();
            }
            else {
                $lists[$categories->where('id',$category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->title = 'Редактирование материала'.$adminArticle->title;
        $this->content = view(env('THEME').'.admin.articles_create_content')->with(['categories'=>$lists,'article'=>$adminArticle])->render();


        return $this->renderOutPut();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request,Article $adminArticle)
    {

        $result = $this->a_rep->updateArticle($request, $adminArticle);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin/adminArticles')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $adminArticle)
    {
        $result = $this->a_rep->deleteArticle($adminArticle);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin/adminArticles')->with($result);
    }
}
