<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        return response()->json(News::latest()->take(10)->get());
    }
    
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён'], 403);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        $news = News::create($validated);
        return response()->json($news);
    }
    public function update(Request $request, News $news)
{
    if (!Auth::user()->is_admin) {
        return response()->json(['error' => 'Доступ запрещён'], 403);
    }
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);
    $news->update($validated);
    return response()->json($news);
}

public function destroy(News $news)
{
    if (!Auth::user()->is_admin) {
        return response()->json(['error' => 'Доступ запрещён'], 403);
    }
    $news->delete();
    return response()->json(['success' => true]);
}
}
