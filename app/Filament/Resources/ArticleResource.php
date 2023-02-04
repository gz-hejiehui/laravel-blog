<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $slug = 'blog/articles';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('文章')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('fields.title'))
                                    ->required()
                                    ->maxLength(120)
                                    ->disableAutocomplete(),

                                Forms\Components\MarkdownEditor::make('content')
                                    ->label(__('fields.content'))
                                    ->required()
                                    ->maxLength(2000),
                            ]),

                        Forms\Components\Section::make('封面')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disableLabel(),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('相关')
                            ->schema([
                                Forms\Components\Select::make('author_id')
                                    ->label(__('fields.author'))
                                    ->relationship('author', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->default(auth()->user()->id),
                                Forms\Components\Select::make('category_id')
                                    ->label(__('fields.category'))
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->default(Category::query()->firstWhere('slug', 'uncategorized')->id),
                                Forms\Components\TagsInput::make('tags')
                                    ->label(__('fields.tags')),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('fields.published_at')),
                            ]),

                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Created at')
                                    ->content(fn (Article $record): ?string => $record->created_at?->diffForHumans()),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Last modified at')
                                    ->content(fn (Article $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->hidden(fn (?Article $record) => $record === null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('fields.title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TagsColumn::make('tags')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Article $record): string => $record->published_at?->isPast() ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('labels.articles');
    }
}
