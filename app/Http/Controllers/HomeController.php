<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            'menuItems' => $this->getMenuItems(),
            'articles' => $this->getArticles(),
        ]);
    }

    /**
     * 获取菜单
     *
     * @return Collection
     */
    private function getMenuItems(): Collection
    {
        return Menu::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
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
