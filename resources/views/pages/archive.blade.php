@extends('base')

@section('main-content')
<main class="container mt-5">
    <div class="row">
        <h1>Archive</h1>
        <p class="lead">A hand-drawn look for mockups and mirth</p>
        <hr>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @foreach ($articles as $year => $items)
            <section class="my-4">
                <h2>{{ $year }}</h2>
                @foreach ($items as $item)
                <li class="my-2 h4">
                    <span title="{{  $item->created_at }}">{{ $item->created_at->format('m / d') }} - </span>
                    <a href="#" class="text-decoration-none">{{ $item->title }}</a>
                </li>
                @endforeach
            </section>
            @endforeach
        </div>
    </div>
</main>
@endsection