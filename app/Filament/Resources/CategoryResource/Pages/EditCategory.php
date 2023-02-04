<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn () => $this->data['slug'] === 'uncategorized')
                ->after(function () {
                    $uncategorized = Category::query()->firstWhere('slug', 'uncategorized');

                    Article::query()
                        ->where('category_id', $this->data['id'])
                        ->update([
                            'category_id' => $uncategorized->id,
                        ]);
                })
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
