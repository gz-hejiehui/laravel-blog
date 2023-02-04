<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

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
                        Forms\Components\Section::make(__('blog.article.section.article'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('blog.article.form.title'))
                                    ->required()
                                    ->maxLength(120)
                                    ->disableAutocomplete(),

                                Forms\Components\MarkdownEditor::make('content')
                                    ->label(__('blog.article.form.content'))
                                    ->required()
                                    ->maxLength(2000),
                            ]),

                        Forms\Components\Section::make(__('blog.article.section.thumnnail'))
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->label(__('blog.article.form.thumbnail'))
                                    ->disableLabel()
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1280')
                                    ->imageResizeTargetHeight('720')
                                    ->directory('thumbnails')
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('blog.article.section.about'))
                            ->schema([
                                Forms\Components\Select::make('author_id')
                                    ->label(__('blog.article.form.author'))
                                    ->relationship('author', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->default(auth()->user()->id),

                                Forms\Components\Select::make('category_id')
                                    ->label(__('blog.article.form.category'))
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->default(Category::query()->firstWhere('slug', 'uncategorized')->id),

                                Forms\Components\TagsInput::make('tags')
                                    ->label(__('blog.article.form.tag')),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('blog.article.form.published_at')),
                            ]),

                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('blog.common.created_at'))
                                    ->content(fn (Article $record): ?string => $record->created_at?->diffForHumans()),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label(__('blog.common.updated_at'))
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

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label(__('blog.article.table.thumbnail'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('blog.article.table.title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('blog.article.table.author'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('category.name')
                    ->label(__('blog.article.table.category'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TagsColumn::make('tags')
                    ->label(__('blog.article.table.tag'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('blog.article.table.status'))
                    ->getStateUsing(fn (Article $record): string => $record->published_at?->isPast() ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('blog.common.updated_at'))
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
        return __('blog.article.label');
    }
}
