<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'subcategories.name as category_name',
            'subcategories.alias as category_alias',
        ])
        ->orderBy('publish_date', 'desc')
        ->limit(2)
        ->get();
    }
}
