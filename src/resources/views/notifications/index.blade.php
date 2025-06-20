
<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
      通知一覧
</h2>
</x-slot>
 
  <div class="max-w-3xl mx-auto py-8 px-4">
    @if($notifications->count() > 0)
<ul>
        @foreach($notifications as $notification)
<li class="mb-4 p-4 rounded border {{ $notification->read_at ? 'bg-white' : 'bg-blue-100' }}">
            {{-- 例: 通知の種類で表示内容変えたい場合 --}}
            @if($notification->type === 'App\Notifications\LikedNotification')
<p>
<strong>{{ $notification->data['liker_name'] ?? '誰か' }}</strong> が
<a href="{{ route('boards.show', $notification->data['board_id']) }}" class="text-orange-600 underline">
                  あなたの記事
</a> にいいねしました。
</p>
<small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
            @elseif($notification->type === 'App\Notifications\CommentedNotification')
<p>
<strong>{{ $notification->data['commenter_name'] ?? '誰か' }}</strong> が
<a href="{{ route('boards.show', $notification->data['board_id']) }}" class="text-orange-600 underline">
                  あなたの記事
</a> にコメントしました。
</p>
<small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
            @else
<p>新しい通知があります。</p>
            @endif
</li>
        @endforeach
</ul>
 
      {{-- ページネーション --}}
<div class="mt-6">
        {{ $notifications->links() }}
</div>
    @else
<p class="text-center text-gray-500">通知はありません。</p>
    @endif
</div>
</x-app-layout>