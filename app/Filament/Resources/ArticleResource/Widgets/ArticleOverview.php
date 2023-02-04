<?php

namespace App\Filament\Resources\ArticleResource\Widgets;

use App\Models\Article;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ArticleOverview extends StatsOverviewWidget 
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Articles', Article::count()),
        ];
    }
}
