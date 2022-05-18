<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestArticle;
use App\Models\Article;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with('menu:id,mn_name');
        if ($name = $request->name) $articles->where('a_name', 'like', '%' . $name . '%');

        if ($id = $request->id) $articles->where('id', $id);
        if ($menuID = $request->menu) $articles->where('a_menu_id', $id);

        $articles = $articles->paginate(10);

        $menus    = Menu::all();
        $viewData = [
            'articles' => $articles,
            'menus'    => $menus,
            'query'    => $request->query()
        ];

        return view('admin.article.index', $viewData);
    }

    public function create()
    {
        $menus = Menu::all();

        return view('admin.article.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $data               = $request->except('_token', 'a_avatar','file');
        $data['a_slug']     = Str::slug($request->a_name);
        $data['created_at'] = Carbon::now();

        if ($request->a_avatar) {
            $image = upload_image('a_avatar');
            if ($image['code'] == 1)
                $data['a_avatar'] = $image['name'];
        }

        $id = Article::insertGetId($data);

        if ($id && $request->file) {
            $this->syncAlbumImageAndProduct($request->file, $id);
        }

        Cache::forget('HOME.ARTICLE_HOT');

        return redirect()->route('admin.article.index');
    }

    public function edit($id)
    {
        $article = Article::find($id);
        $menus   = Menu::all();
        $images = \DB::table('articles_images')
            ->where("article_id", $id)
            ->get();


        return view('admin.article.update', compact('menus', 'article','images'));
    }

    public function update(Request $request, $id)
    {
        $article            = Article::find($id);
        $data               = $request->except('_token', 'a_avatar','file');
        $data['a_slug']     = Str::slug($request->a_name);
        $data['updated_at'] = Carbon::now();

        if ($request->a_avatar) {
            $image = upload_image('a_avatar');
            if ($image['code'] == 1)
                $data['a_avatar'] = $image['name'];
        }

        $id = $article->update($data);

        if ($request->file) {
            $this->syncAlbumImageAndProduct($request->file, $id);
        }

        Cache::forget('HOME.ARTICLE_HOT');

        return redirect()->route('admin.article.index');
    }

    public function active($id)
    {
        $article           = Article::find($id);
        $article->a_active = !$article->a_active;
        $article->save();
        Cache::forget('HOME.ARTICLE_HOT');

        return redirect()->back();
    }

    public function hot($id)
    {
        $article        = Article::find($id);
        $article->a_hot = !$article->a_hot;
        $article->save();
        Cache::forget('HOME.ARTICLE_HOT');

        return redirect()->back();
    }

    public function syncAlbumImageAndProduct($files, $productID)
    {
        foreach ($files as $key => $fileImage) {
            $ext    = $fileImage->getClientOriginalExtension();
            $extend = [
                'png', 'jpg', 'jpeg', 'PNG', 'JPG'
            ];

            if (!in_array($ext, $extend)) return false;

            $filename = date('Y-m-d__') . Str::slug($fileImage->getClientOriginalName()) . '.' . $ext;
            $path     = public_path() . '/uploads/' . date('Y/m/d/');
            if (!\File::exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileImage->move($path, $filename);
            \DB::table('articles_images')
                ->insert([
                    'name'       => $fileImage->getClientOriginalName(),
                    'slug'       => $filename,
                    'article_id' => $productID,
                    'created_at' => Carbon::now()
                ]);
        }
    }

    public function delete($id)
    {
        $article = Article::find($id);
        if ($article) $article->delete();

        return redirect()->route('admin.article.index');
    }

    public function deleteImage(Request $request, $id)
    {
        \DB::table('articles_images')->where('id', $id)->delete();
        return redirect()->back();
    }

}
