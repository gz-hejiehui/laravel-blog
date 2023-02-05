<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $slug = 'config/menu';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Config';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('config.menu.form.name'))
                    ->required()
                    ->maxLength(40)
                    ->disableAutocomplete(),

                Forms\Components\TextInput::make('slug')
                    ->label(__('config.menu.form.slug'))
                    ->required()
                    ->maxLength(32)
                    ->alphaDash()
                    ->unique(Menu::class, 'slug', ignoreRecord: true),

                Forms\Components\TextInput::make('url')
                    ->label(__('config.menu.form.url'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->label(__('config.menu.form.parent'))
                    ->options(function (?Menu $record) {
                        $query = $record ? Menu::query()->where('id', '<>', $record->id) : Menu::all();
                        return $query->pluck('name', 'id');
                    })
                    ->searchable(),

                Forms\Components\TextInput::make('icon')
                    ->label(__('config.menu.form.icon'))
                    ->maxLength(20),

                Forms\Components\TextInput::make('order')
                    ->label(__('config.menu.form.order'))
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('config.menu.table.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('config.menu.table.parent'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('url')
                    ->label(__('config.menu.table.url'))
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label(__('config.menu.table.parent'))
                    ->relationship('parent', 'name',  fn (Builder $query) => $query->has('children'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Menu $record) {
                        Menu::query()
                            ->where('parent_id', $record->id)
                            ->update([
                                'parent_id' => null,
                            ]);
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMenus::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('config.menu.label');
    }
}
