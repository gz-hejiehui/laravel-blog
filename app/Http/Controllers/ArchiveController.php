<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ArchiveController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('pages.archive', [
            'articles' => $this->getArticles(),
        ]);
    }

    /**
     * 获取年度文章
     *
     * @return Collection
     */
    private function getArticles(): Collection
    {
        $articles = Article::query()
            ->with('category')
            ->latest('created_at')
            ->get();

        $articles = $articles->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('Y');
        });

        return $articles;
    }
}
