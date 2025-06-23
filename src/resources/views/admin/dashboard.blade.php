<x-admin-layout>
  <div class="max-w-4xl mx-auto mt-20 p-8 bg-indigo-100 rounded-3xl shadow-lg relative">

    <!-- アイコンと吹き出し -->
    <div class="flex items-center mb-10 space-x-4">
      <!-- 丸いアイコンのコンテナ -->
      <div class="relative flex-shrink-0">
        <div class="w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg text-white text-5xl">
          <i class="ri-nurse-fill"></i>
        </div>
        <!-- 吹き出しの矢印（三角） -->
        <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-0 h-0 border-t-8 border-t-indigo-600 border-x-8 border-x-transparent"></div>
      </div>

      <!-- 吹き出しメッセージ -->
      <div class="bg-white rounded-2xl p-6 shadow-md max-w-xl relative">
        <p class="text-indigo-900 text-3xl font-extrabold mb-2">ようこそ、管理者さま！</p>
        <p class="text-indigo-600 text-lg">今日もお疲れ様です。サイトの状況をこちらでご確認ください。</p>
      </div>
    </div>

    <!-- サマリーカード群 -->
<div class="flex space-x-4 mb-6">

{{-- ユーザー数 --}}
  <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2 bg-green-100 rounded-lg p-3 shadow max-w-xs w-full hover:scale-105 transition-all duration-200">
    <i class="ri-group-fill text-3xl text-green-500"></i>
    <div>
      <p class="text-3xl font-bold text-green-800">{{ $userCount }}</p>
      <p class="text-sm font-semibold text-green-800">ユーザー数</p>
    </div>
  </a>

  {{-- 投稿記事数 --}}
  <a href="{{ route('admin.boards.index') }}" class="flex items-center space-x-2 bg-orange-100 rounded-lg p-3 shadow max-w-xs w-full hover:scale-105 transition-all duration-200">
    <i class="ri-file-list-3-fill text-3xl text-orange-500"></i>
    <div>
      <p class="text-3xl font-bold text-orange-800">{{ $boardCount }}</p>
      <p class="text-sm font-semibold text-orange-800">投稿記事数</p>
    </div>
  </a>


  {{-- コメント数 --}}
  <a href="{{ route('admin.comments.index') }}" class="flex items-center space-x-2 bg-purple-100 rounded-lg p-3 shadow max-w-xs w-full hover:scale-105 transition-all duration-200">
    <i class="ri-chat-1-fill text-3xl text-purple-500"></i>
    <div>
      <p class="text-3xl font-bold text-purple-800">{{ $commentCount }}</p>
      <p class="text-sm font-semibold text-purple-800">コメント数</p>
    </div>
  </a>

  {{-- カテゴリ数 --}}
<a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-2 bg-blue-100 rounded-lg p-3 shadow max-w-xs w-full hover:scale-105 transition-all duration-200">
  <i class="ri-folder-3-fill text-3xl text-blue-500"></i>
  <div>
    <p class="text-3xl font-bold text-blue-800">{{ $categoryCount }}</p>
    <p class="text-sm font-semibold text-blue-800">カテゴリ数</p>
  </div>
</a>

  {{-- タグ数 --}}
<a href="{{ route('admin.tags.index') }}" class="flex items-center space-x-2 bg-pink-100 rounded-lg p-3 shadow max-w-xs w-full hover:scale-105 transition-all duration-200">
  <i class="ri-price-tag-3-fill text-3xl text-pink-500"></i>
  <div>
    <p class="text-3xl font-bold text-pink-800">{{ $tagCount }}</p>
    <p class="text-sm font-semibold text-pink-800">タグ数</p>
  </div>
</a>


</div>

</x-admin-layout>
