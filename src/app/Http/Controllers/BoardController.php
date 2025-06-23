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

   public function index(Request $request)
{
    $converter = new CommonMarkConverter();

    $query = Board::where('is_published', true)
        ->with(['user', 'category', 'tags', 'likes']);

        //記事検索機能(タイトル、タグ、本文、カテゴリが対象)
    if ($request->filled('keyword')) {
    $keyword = $request->input('keyword');
    $query->where(function ($q) use ($keyword) {
        $q->where('title', 'like', '%' . $keyword . '%')
          ->orWhere('description', 'like', '%' . $keyword . '%')
          ->orWhereHas('tags', function ($tagQuery) use ($keyword) {
              $tagQuery->where('name', 'like', '%' . $keyword . '%');
          })
          ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
              $categoryQuery->where('name', 'like', '%' . $keyword . '%');
          });
    });
}


    // 記事の公開非公開
    $boards = $query->latest()->paginate(10);

    $boards->getCollection()->transform(function ($board) use ($converter) {
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
            'is_published' => 'nullable|boolean', // チェックボックスなのでnullable|booleanでOK
        ]);

        $board = new Board();
        $board->title = $validatedData['title'];
        $board->description = $validatedData['description'];
        $board->category_id = $validatedData['category_id'];
        $board->user_id = auth()->id();
        $board->is_published = $request->has('is_published'); // チェックがあればtrue、なければfalse
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
        $board->timestamps = false; 
        $board->increment('view_count');
        $board->timestamps = true;
        $board->load(['tags', 'category', 'comments.user']);
        return view('boards.show', compact('board'));
    }

    public function fetchRanking($type = 'latest')
    {
        // 公開済みのボードに絞る
        $query = Board::where('is_published', true)->with('user')->withCount('likes');

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
        $tags = $board->tags->pluck('name')->implode(', ');
        return view('boards.edit', compact('board', 'categories', 'tags'));
    }

    public function update(Request $request, Board $board)
    {

        $this->authorize('update', $board);

        $validatedData = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required|max:65535',
            'category_id' => 'required|exists:categories,id',
            'tags'        => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        $board->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'is_published' => $request->has('is_published'),
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

         return redirect()->route('boards.show', $board->id)
                          ->with('success', '投稿を更新しました');
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

    public function preview(Request $request)
    {
        // 投稿時のバリデーションに合わせてvalidate
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:65535',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        // マークダウンをHTMLに変換
        $converter = new CommonMarkConverter();
        $htmlDescription = $converter->convertToHtml($data['description']);

        // カテゴリ名を取得（ビュー表示用）
        $category = \App\Models\Category::find($data['category_id']);

        // タグ配列（カンマ区切りを配列に）
        $tagNames = [];
        if (!empty($data['tags'])) {
            $tagNames = array_filter(array_map('trim', explode(',', $data['tags'])));
        }

        return view('boards.preview', [
            'title' => $data['title'],
            'description' => $data['description'],
            'htmlDescription' => $htmlDescription,
            'category' => $category,
            'tags' => $tagNames,
            'is_published' => $request->has('is_published'),
        ]);
    }
}
