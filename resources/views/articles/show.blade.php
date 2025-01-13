@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <article class="max-w-3xl mx-auto">
        <h1 class="text-4xl font-bold mb-4">{{ $article->title }}</h1>
        <div class="text-gray-600 mb-8">
            By {{ $article->user->name }} on {{ $article->created_at->format('d M Y') }}
        </div>

        @if($article->image)
            <img src="{{ Storage::url('articles/' . $article->image) }}" alt="{{ $article->title }}" class="w-full mb-8 rounded-lg shadow-lg">
        @endif

        <div class="prose max-w-none">
            {!! nl2br(e($article->content)) !!}
        </div>
    </article>
</div>
@endsection