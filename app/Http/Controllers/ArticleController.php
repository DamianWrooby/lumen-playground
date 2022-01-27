<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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

    /**
     * Get article by ID
     * 
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function get(Request $request, $id)
    {
        $request['id'] = intval($id);

        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);

        return response()->json((new Article())->getArticleById($request->input('id')), Response::HTTP_OK);
        // tutaj jasno określamy co chcemy zwrócić i w jakiej formie, jawnie określamy obiekt w formacie JSON oraz status odpowiedzi
    }

    /**
     * Get articles by category alias
     * 
     * @param $categoryAlias
     * @return JsonResponse
     */
    public function category($categoryAlias)
    {
        // tutaj walidator nie widzi aliasu bo jest dodawany do ścieżki w urlu jako parametr
        // walidować można tylko zawartość body

        return response()->json((new Article())->getArticleByCategory($categoryAlias), Response::HTTP_OK);
    }

    /**
     * Display latest articles
     * 
     * @return JsonResponse
     */
    public function latest(): JsonResponse
    {
        return response()->json((new Article())->getLatestArticles(), Response::HTTP_OK);
    }

    /**
     * Store article
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // tutaj możemy skorzystać z walidatora bo wartości pól są przekazywane w body

        $this->validate($request, [
            'title' => 'required|min:5', // minimum 5 znaków
            'short_text' => 'required|min:10',
            'long_text' => 'required|min:10',
            'publish_date' => 'required|date', // YYY-MM-DD H:min:s
            'image_url' => 'required|min:5'
        ]);



        // return response()->json(['message' => 'Article created'], Response::HTTP_CREATED); // tutaj zwracamy tylko wiadomość i status 201
        // return $this->get($request, (new Article()->storeArticle())); // tutaj zwracamy od razu ten utworzony artykuł
        return response()->json(['message' => 'Article created', 'id' => (new Article())->storeArticle($request)], Response::HTTP_CREATED); // tutaj zwróci ID utworzonego artykułu

    }

    /**
     * Edit article
     * 
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function edit(Request $request, $id)
    {

        $request['id'] = $id; // dobijamy do requesta id z urla, żeby można było zwalidować

        $this->validate($request, [
            'id' => 'required|integer|min:1', // i teraz możemy zwalidować
            'title' => 'required|min:5',
            'short_text' => 'required|min:10',
            'long_text' => 'required|min:10',
            'publish_date' => 'required|date',
            'image_url' => 'required|min:5'
        ]);

        (new Article())->editArticle($request);

        return response()->json(['message' => 'Article updated'], Response::HTTP_OK);
    }

    /**
     * Delete article
     * 
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws  ValidationException
     */
    public function delete(Request $request, $id)
    {

        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);

        (new Article())->deleteArticle($request->input('id'));

        return response()->json(['message' => 'Article removed'], Response::HTTP_OK);
    }
}
