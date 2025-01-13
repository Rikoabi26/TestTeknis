@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Latest Articles</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if($article->image)
                    <img src="{{ Storage::url('articles/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2">{{ $article->title }}</h2>
                    <p class="text-gray-600 text-sm mb-4">
                        By {{ $article->user->name }} on {{ $article->created_at->format('d M Y') }}
                    </p>
                    <p class="text-gray-700 mb-4">{{ Str::limit($article->content, 150) }}</p>
                    <a href="{{ route('articles.show', $article->slug) }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Read More
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</div>
@endsection
