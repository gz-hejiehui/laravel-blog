@extends('base')

@section('main-content')
<main class="container mt-5 pt-4">
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4 fst-italic">{{ config('app.name', 'Laravel') }}</h1>
            <p class="lead my-3">Multiple lines of text that form the lede, informing new readers quickly and
                efficiently
                about what’s most interesting in this post’s contents.</p>
            <p class="lead mb-0"><a href="#" class="text-white fw-bold">Continue reading...</a></p>
        </div>
    </div>

    <div class="row mb-5 gx-4 gy-5">
        @foreach ($articles as $article )
        <div class="col-sm-4">
            <article id="article-{{ $article->id }}" class="article-card">
                <div class="card p-1">
                    <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="article-thumbnail">

                    <div class="card-body p-1">
                        <div class="card-text-fade-out">
                            <h3 class="card-title mt-2">
                                <a href="/" title="{{ $article->title }}" class="article-title">
                                    <i class="bi bi-signpost"></i>
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="card-text article-description">
                                {{ Str::limit($article->content, 150, '...') }}
                            </p>
                            <div class="text-end">
                                <a class="btn btn-success btn-sm" href="#">
                                    阅读全文
                                    <i class="bi bi-eyeglasses"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-muted text-center">
                        <div class="author-posted-date">
                            <a href="#" title="written {{ $article->created_at }}" class="author-link">
                                {{ $article->author->name}}
                            </a>
                            <time itemprop="datePublished" datetime="{{ $article->created_at }}">
                                发布于 {{ $article->created_at->diffForHumans() }}
                            </time>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        @endforeach

        {{ $articles->withQueryString()->links() }}
    </div>

</main>
@endsection