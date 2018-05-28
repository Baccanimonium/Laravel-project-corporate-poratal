<?php
namespace Corp\Repositories;
use Corp\Article;
use Illuminate\Auth\Access\Gate;
use Intervention\Image\Facades\Image;

class ArticlesRepository extends Repository
{
    public function __construct(Article $articles)
    {
        $this->model = $articles;
    }
    public function one($alias, $attr = array())
    {
       $article= parent::one($alias, $attr);
        if ($article && !empty($attr)) {
            $article->load('comments');
            $article->comments->load('user');
        }
        return $article;
    }

    public function addArticle($request)
    {


        $data = $request->except('_token', 'image');
        if (empty($data)) {
            return array('error' => 'Нет данных');
        }
        if (empty($data['alias'])) {
            $data['alias']= $this->transliterate($data['title']);
        }
        if ($this->one($data['alias'], false)) {
            $request->merge(array('alias'=>$data['alias']));
            $request->flash();
            return['error'=>'Данный псевдоним уже используется'];
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                $str = str_random(8);
                $obj = new \stdClass();
                $obj->mini = $str . '_mini.jpg';
                $obj->max = $str . '_max.jpg';
                $obj->path = $str . '.jpg';
                $img = Image::make($image);
                $img->fit(\config('settings.image')['width'],
                    \config('settings.image')['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);
                $img->fit(\config('settings.articles_img')['max']['width'],
                    \config('settings.articles_img')['max']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);
                $img->fit(\config('settings.articles_img')['mini']['width'],
                    \config('settings.articles_img')['mini']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);
                $data['img'] = json_encode($obj);

                }
            }
        $this->model->fill($data);
        if ($request->user()->articles()->save($this->model)) {
            return ['status' => 'Материал добавлен'];
        }
    }
    public function updateArticle($request, $adminArticle)
    {

        $data = $request->except('_token', 'image','_method');
        if (empty($data)) {
            return array('error' => 'Нет данных');
        }
        if (empty($data['alias'])) {
            $data['alias']= $this->transliterate($data['title']);
        }
        $result = $this->one($data['alias'], false);
        if (isset($result->id) && $result->id != $adminArticle->id) {
            $request->merge(array('alias'=>$data['alias']));
            $request->flash();
            return['error'=>'Данный псевдоним уже используется'];
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                $str = str_random(8);
                $obj = new \stdClass();
                $obj->mini = $str . '_mini.jpg';
                $obj->max = $str . '_max.jpg';
                $obj->path = $str . '.jpg';
                $img = Image::make($image);
                $img->fit(\config('settings.image')['width'],
                    \config('settings.image')['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);
                $img->fit(\config('settings.articles_img')['max']['width'],
                    \config('settings.articles_img')['max']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);
                $img->fit(\config('settings.articles_img')['mini']['width'],
                    \config('settings.articles_img')['mini']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);
                $data['img'] = json_encode($obj);
                }
            }
        $adminArticle->fill($data);
        if ($adminArticle->update()) {
            return ['status' => 'Материал обновлен'];
        }
    }

    public function deleteArticle($adminArticle)
    {

        $adminArticle->comments()->delete();
        if ($adminArticle->delete()) {
            return ['status' => 'Материал удален'];
        }
    }
}