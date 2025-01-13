<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/articles', $imageName);
            $data['image'] = $imageName;
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title)
        ];

        if ($request->hasFile('image')) {
            // Pastikan folder articles ada
            if (!Storage::exists('public/articles')) {
                Storage::makeDirectory('public/articles');
            }

            // Hapus gambar lama jika ada
            if ($article->image && Storage::exists('public/articles/' . $article->image)) {
                Storage::delete('public/articles/' . $article->image);
            }
            
            // Upload gambar baru
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($image->getClientOriginalName());
            $image->storeAs('public/articles', $imageName);
            $data['image'] = $imageName;
        }

        // Update artikel
        $article->update($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::delete('public/articles/' . $article->image);
        }
        
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
