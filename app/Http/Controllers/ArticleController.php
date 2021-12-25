<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function get(Request $request, $id) {
        $request['id'] = intval($id); 

        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);

        

        $getArticle = DB::table('articles')
        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
        ->leftJoin('subcategories', 'subcategories.id', '=', 'articles.subcategory_id')
        ->select([
            'articles.id as article_id',
            'articles.title as article_title',
            'articles.short_text as article_short_text',
            'articles.long_text as article_long_text',
            'articles.publish_date as article_publish_date',
            'articles.image_url as article_image_url',
            'categories.id as category_id',
            'categories.name as category_name',
            'categories.alias as category_alias',
            'subcategories.name as subcategory_name',
            'subcategories.alias as subcategory_alias',
        ])
        ->orderBy('publish_date', 'desc')
        ->where(['articles.id' => $request->input('id')])
        ->first();

        return response()->json($getArticle, Response::HTTP_OK);
        // tutaj jasno określamy co chcemy zwrócić i w jakiej formie, jawnie określamy obiekt w formacie JSON oraz status odpowiedzi
    }

    public function category($categoryAlias) {
        // tutaj walidator nie widzi aliasu bo jest dodawany do ścieżki w urlu jako parametr

        return DB::table('articles')
        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
        ->leftJoin('subcategories', 'subcategories.id', '=', 'articles.subcategory_id')
        ->select([
            'articles.id as article_id',
            'articles.title as article_title',
            'articles.short_text as article_short_text',
            'articles.long_text as article_long_text',
            'articles.publish_date as article_publish_date',
            'articles.image_url as article_image_url',
            'categories.id as category_id',
            'categories.name as category_name',
            'categories.alias as category_alias',
            'subcategories.name as subcategory_name',
            'subcategories.alias as subcategory_alias',
        ])
        ->orderBy('publish_date', 'desc')
        ->where(['categories.alias' => $categoryAlias])
        ->paginate(2);
        // Jeśli mamy paginate Laravel automatycznie przeszukuje request w poszukiwaniu parametru page
    }
    
    public function latest() {
        return DB::table('articles')
        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
        ->leftJoin('subcategories', 'subcategories.id', '=', 'articles.subcategory_id')
        ->select([
            'articles.id as article_id',
            'articles.title as article_title',
            'articles.short_text as article_short_text',
            'articles.long_text as article_long_text',
            'articles.publish_date as article_publish_date',
            'articles.image_url as article_image_url',
            'categories.id as category_id',
            'categories.name as category_name',
            'categories.alias as category_alias',
            'subcategories.name as subcategory_name',
            'subcategories.alias as subcategory_alias',
        ])
        ->orderBy('publish_date', 'desc')
        ->limit(2)
        ->get();
    }

    public function store(Request $request) {
        // tutaj możemy skorzystać z walidatora bo wartości pól są przekazywane w body

        $this->validate($request, [
            'title' => 'required|min:5', // minimum 5 znaków
            'short_text' => 'required|min:10',
            'long_text' => 'required|min:10',
            'publish_date' => 'required|date', // YYY-MM-DD H:min:s
            'image_url' => 'required|min:5'
        ]);

        $newArticleId = DB::table('articles')
            ->insertGetId([
                'category_id' => intval($request->input('category_id')) > 0 ? $request->input('category_id') : null,
                'subcategory_id' => intval($request->input('subcategory_id')) > 0 ? $request->input('subcategory_id') : null,
                'title' => strip_tags($request->input('title')),
                'short_text' => strip_tags($request->input('short_text')),
                'long_text' => strip_tags($request->input('long_text')),
                'publish_date' => strip_tags($request->input('publish_date')),
                'image_url' => strip_tags($request->input('image_url')),
            ]);
        
            // return response()->json(['message' => 'Article created'], Response::HTTP_CREATED); // tutaj zwracamy tylko wiadomość i status 201
            return $this->get($request, $newArticleId); // tutaj zwracamy od razu ten utworzony artykuł
    }

    public function edit(Request $request, $id) {
        
        $request['id'] = $id; // dobijamy do requesta id z urla, żeby można było zwalidować
        
        $this->validate($request, [
            'id' => 'required|integer|min:1', // i teraz możemy zwalidować
            'title' => 'required|min:5',
            'short_text' => 'required|min:10',
            'long_text' => 'required|min:10',
            'publish_date' => 'required|date', 
            'image_url' => 'required|min:5'
        ]);

        DB::table('articles')
            ->where(['id' => $id]) //! tu musi być where, w przeciwnym wypadku poleci update na jakąś tabelę 
            ->update([
                'category_id' => intval($request->input('category_id')) > 0 ? $request->input('category_id') : null,
                'subcategory_id' => intval($request->input('subcategory_id')) > 0 ? $request->input('subcategory_id') : null,
                'title' => strip_tags($request->input('title')),
                'short_text' => strip_tags($request->input('short_text')),
                'long_text' => strip_tags($request->input('long_text')),
                'publish_date' => strip_tags($request->input('publish_date')),
                'image_url' => strip_tags($request->input('image_url')),
            ]);
        
            return response()->json(['message' => 'Article updated'], Response::HTTP_OK);
    }

    public function delete(Request $request, $id) {

        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);

        DB::table('articles')
        ->where(['id' => $id]) 
        ->delete();

        return response()->json(['message' => 'Article removed'], Response::HTTP_OK);
    }
}
