<?php

namespace App\Models;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Article
{   
    /**
     * 
     * tu opisy funkcji też powinny się znaleźć
     * 
     */
    public function getLatestArticles(): Collection
    {
        return $this->getArticleQueryBuilder()
            ->orderBy('publish_date', 'desc')
            ->limit(2)
            ->get();
    }

    public function getArticleByCategory($categoryAlias): LengthAwarePaginator
    {
        return $this->getArticleQueryBuilder()
            ->orderBy('publish_date', 'desc')
            ->where(['categories.alias' => $categoryAlias])
            ->paginate(2); // zwraca po 2 art. z danej kategorii
        // Jeśli mamy paginate Laravel automatycznie przeszukuje request w poszukiwaniu parametru page
    }

    public function getArticleById(int $id): Object | null
    {
        return $this->getArticleQueryBuilder()
            ->orderBy('publish_date', 'desc')
            ->where(['articles.id' => $id])
            ->first();
    }

    public function storeArticle(Request $request): int
    {

        return DB::table('articles')
            ->insertGetId([
                'category_id' => intval($request->input('category_id')) > 0 ? $request->input('category_id') : null,
                'subcategory_id' => intval($request->input('subcategory_id')) > 0 ? $request->input('subcategory_id') : null,
                'title' => strip_tags($request->input('title')),
                'short_text' => strip_tags($request->input('short_text')),
                'long_text' => strip_tags($request->input('long_text')),
                'publish_date' => strip_tags($request->input('publish_date')),
                'image_url' => strip_tags($request->input('image_url')),
            ]);
    }

    public function editArticle(Request $request): int
    {
        return
            DB::table('articles')
            ->where(['id' => $request->input('id')]) //! tu musi być where, w przeciwnym wypadku poleci update na jakąś tabelę 
            ->update([
                'category_id' => intval($request->input('category_id')) > 0 ? $request->input('category_id') : null,
                'subcategory_id' => intval($request->input('subcategory_id')) > 0 ? $request->input('subcategory_id') : null,
                'title' => strip_tags($request->input('title')),
                'short_text' => strip_tags($request->input('short_text')),
                'long_text' => strip_tags($request->input('long_text')),
                'publish_date' => strip_tags($request->input('publish_date')),
                'image_url' => strip_tags($request->input('image_url')),
            ]);
    }

    public function deleteArticle($id): int
    {
        return
            DB::table('articles')
            ->where(['id' => $id])
            ->delete();
    }

    private function getArticleQueryBuilder()
    {
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
            ]);
    }
}
