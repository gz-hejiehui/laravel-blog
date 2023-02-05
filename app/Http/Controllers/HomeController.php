<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('pages.home', [
            'articles' => $this->getArticles(),
        ]);
    }

    /**
     * 获取文章
     *
     * @return LengthAwarePaginator
     */
    private function getArticles(): LengthAwarePaginator
    {
        return Article::query()
            ->with(['author', 'category'])
            ->latest('id')
            ->paginate(12);
    }
}
