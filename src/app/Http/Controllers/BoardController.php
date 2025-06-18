<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use League\CommonMark\CommonMarkConverter;

class BoardController extends Controller
{
    use AuthorizesRequests;

   public function index()
{
    $boards = Board::latest()->get();
    $converter = new CommonMarkConverter();

    $boards->map(function ($board) use ($converter) {
        $board->description_html = $converter->convert($board->description ?? '')->getContent();
        return $board;
    });

    return view('boards.index', compact('boards'));
}

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('boards.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required|max:65535',
            'category_id' => 'required|exists:categories,id',
            'tags'        => 'nullable|string', // カンマ区切りで受け取る
        ]);

        $board = new Board();
        $board->title = $validatedData['title'];
        $board->description = $validatedData['description'];
        $board->category_id = $validatedData['category_id'];
        $board->user_id = auth()->id();
        $board->save();

        if (!empty($validatedData['tags'])) {
            // 入力されたタグをカンマで分割し、前後の空白を除去
            $tagNames = array_filter(array_map('trim', explode(',', $validatedData['tags'])));

            $tagIds = [];
            foreach ($tagNames as $tagName) {
                // タグが存在しなければ作成
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            // 中間テーブルにタグを同期
            $board->tags()->sync($tagIds);
        }

        return redirect()->route('boards.index')->with('success', '投稿が完了しました');
    }

    public function show(Board $board)
    {
        $board->increment('view_count');
        $board->load(['tags', 'category', 'comments.user']);
        return view('boards.show', compact('board'));
    }

    public function fetchRanking($type = 'latest')
    {
        $query = Board::with('user')->withCount('likes');

        switch ($type) {
            case 'popular':
                $query = $query->popularBoards();
                break;
            case 'views':
                $query = $query->mostViewedBoards();
                break;
            case 'latest':
            default:
                $query = $query->latestBoards();
                break;
        }

        $boards = $query->take(5)->get();

        $boards->transform(function ($board) {
            $board->detail_url = route('boards.show', $board->id);
            return $board;
        });

        return response()->json($boards);
    }

    public function edit(Board $board)
    {
        $this->authorize('update', $board);
        $categories = \App\Models\Category::all();
        return view('boards.edit', compact('board', 'categories'));
    }

    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $validatedData = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required|max:65535',
            'category_id' => 'required|exists:categories,id',
            'tags'        => 'nullable|string',
        ]);

        $board->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
        ]);

        if (!empty($validatedData['tags'])) {
            $tagNames = array_filter(array_map('trim', explode(',', $validatedData['tags'])));

            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            $board->tags()->sync($tagIds);
        } else {
            // タグが空なら全て解除
            $board->tags()->detach();
        }

        return redirect()->route('boards.show', $board)->with('success', '投稿を更新しました');
    }

    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);

        $board->delete();
        return redirect()->route('boards.index')->with('success', '投稿を削除しました');
    }

    // app/Http/Controllers/BoardController.php

    public function uploadImage(Request $request)
    {
        $request->validate([
    'image' => 'required|image|mimes:jpeg,png,gif|max:2048',
]);


         $path = $request->file('image')->store('board_images', 'public');

         $url = asset('storage/' . $path);

         return response()->json(['url' => $url]);
    }


}
