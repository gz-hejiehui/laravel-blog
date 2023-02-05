<?php

namespace App\Http\Controllers;

use App\Models\Menu;
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
        $menuItems = Menu::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('home', ['menuItems' => $menuItems]);
    }
}
