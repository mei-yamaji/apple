<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-orange-900">
      {{ __('マイページ') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto space-y-6 mt-6 px-4">

      <!-- プロフィール表示エリア -->
      <div class="flex items-center space-x-6 bg-white p-6 rounded-2xl shadow-lg">

        {{-- プロフィール画像 --}}
        @if (Auth::user()->profile_image)
          <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像" class="w-20 h-20 rounded-full object-cover ml-4">
        @else
          <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 ">
            No Image
          </div>
        @endif

        {{-- 名前・ひとこと・メールアドレス --}}
        <div class="flex-1 pl-4">
          {{-- 名前 --}}
          <div class="flex items-center">
            <i class="ri-user-line text-orange-500 text-3xl "></i>
            <h3 class="text-2xl font-semibold text-gray-800">
            <a href="{{ route('user.show', Auth::user()->id) }}" class="hover:underline hover:text-orange-600">
              {{ Auth::user()->name }}
            </a>
            @if (Auth::user()->is_runteq_student)
              <span class="text-yellow-400">🍎</span>
            @endif
          </h3>


            {{-- フォロー・フォロワーボタン --}}
            <div class="flex space-x-3 ml-6">
              {{-- フォロー中 --}}
              <a href="{{ route('profile.followings', Auth::id()) }}" 
                class="flex items-center gap-1 px-3 py-1 text-xs bg-orange-100 text-orange-600 rounded-full shadow hover:bg-orange-200 hover:scale-105 transition-all duration-200">
                  <i class="ri-user-follow-line text-base"></i>
                  <span>フォロー中：{{ Auth::user()->followings()->count() }}人</span>
              </a>

              {{-- フォロワー --}}
              <a href="{{ route('profile.followers', Auth::id()) }}" 
                class="flex items-center gap-1 px-3 py-1 text-xs bg-orange-100 text-orange-600 rounded-full shadow hover:bg-orange-200 hover:scale-105 transition-all duration-200">
                  <i class="ri-group-line text-base"></i>
                  <span>フォロワー：{{ Auth::user()->followers()->count() }}人</span>
              </a>
            </div>
          </div>

          {{-- ひとこと --}}
          @if (Auth::user()->bio)
            <p class="mt-2 text-gray-600 flex items-center">
              <i class="ri-chat-smile-2-line mr-2 text-orange-500 text-3xl"></i>
              {{ Auth::user()->bio }}
            </p>
          @else
            <p class="mt-2 text-gray-400 italic">ひとことが設定されていません</p>
          @endif

          {{-- メールアドレス --}}
          <p class="mt-2 text-gray-600 flex items-center">
            <i class="ri-mail-line mr-2 text-orange-500 text-3xl"></i>
            {{ Auth::user()->email }}
          </p>
        </div>

        {{-- プロフィール編集ボタン --}}
        <div>
          <x-primary-button>
            <a href="{{ route('profile.edit') }}">
              プロフィール編集
            </a>
          </x-primary-button>
        </div>
      </div>

      {{-- 切り替えボタン --}}
      <div class="tabs flex justify-center gap-6 mb-6">
        <span class="text-5xl">🍎</span>
        <span class="text-5xl">🍎</span>

        <x-primary-button 
          class="tab-button" id="ownTabButton">
          自分の投稿
        </x-primary-button>

        <x-primary-button id="likesTabButton"
          class="text-xl px-12 py-4 border transition-all tab-button {{ $viewMode === 'likes' ? 'bg-blue-600 text-white' : '' }}">
          いいねした記事
        </x-primary-button>

        <span class="text-5xl">🍎</span>
        <span class="text-5xl">🍎</span>
      </div>

          {{-- 記事検索機能 --}}
        <form action="{{ route('mypage') }}" method="GET" class="mb-6">
      <input type="hidden" name="view" value="likes" />
      <div class="flex space-x-2">
        <input type="text" name="keyword" placeholder="いいねした記事で検索"
              value="{{ request('keyword') ?? '' }}"
              class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500" />
        <button type="submit"
                class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-orange-400 rounded-full shadow-md hover:bg-orange-500 hover:scale-105 transition-all duration-200 focus:outline-none">
          <i class="ri-search-line text-base"></i> 
        </button>
      </div>
    </form>

    

      {{-- 自分の投稿一覧 --}}
      <div id="ownPosts" style="{{ $viewMode === 'own' ? '' : 'display:none;' }}">
        @if ($boards->isNotEmpty())
          @foreach ($boards as $board)
            <div class="border rounded-2xl shadow-md p-6 mb-6 bg-white">
              <h2 class="text-2xl font-semibold text-orange-600 hover:underline mb-2">
                <a href="{{ route('boards.show', $board->id) }}">
                  {{ $board->title }}
                </a>
              </h2>
              <div class="text-sm text-gray-500 mb-4">
                投稿者: {{ $board->user->name ?? '不明' }}
                @if (!empty($board->user->is_runteq_student) && $board->user->is_runteq_student)
                  <span>🍎</span>
                @endif
                投稿日: {{ $board->created_at->format('Y/m/d H:i') }}

                <span class="ml-4 font-semibold">
                  ステータス: 
                  @if($board->is_published)
                    <span class="text-green-600">公開中</span>
                  @else
                    <span class="text-red-600">非公開中</span>
                  @endif
                </span>
              </div>
              <div class="prose prose-gray max-w-none">
                @php
                  $htmlWithoutImages = preg_replace('/<img[^>]*>/', '', $board->description_html ?? '');
                  $plainDescription = strip_tags($htmlWithoutImages);
                  $maxLength = 100;
                  $shortDescription = Str::limit($plainDescription, $maxLength);
                @endphp

                {{ $shortDescription }}

                @if (Str::length($plainDescription) > $maxLength)
                  <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
                @endif
              </div>

              <div class="mt-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">💖 {{ $board->likes_count ?? 0 }} 件のいいね</span>
              </div>
            </div>
          @endforeach
          <div class="mt-4">
            {{ $boards->appends(['view' => 'own'])->links() }}
          </div>
        @else
          <p class="text-gray-500 text-center">あなたの投稿はまだありません。</p>
        @endif
      </div>

    


      {{-- いいねした記事一覧 --}}
      <div id="likedPosts" style="{{ $viewMode === 'likes' ? '' : 'display:none;' }}">
        @if ($likedBoards->isNotEmpty())
          @foreach ($likedBoards as $board)
            <div class="border rounded-2xl shadow-md p-6 mb-6 bg-white">
              <h2 class="text-2xl font-semibold text-orange-600 hover:underline mb-2">
                <a href="{{ route('boards.show', $board->id) }}">
                  {{ $board->title }}
                </a>
              </h2>
              <div class="text-sm text-gray-500 mb-4">
                投稿者: {{ $board->user->name ?? '不明' }}
                @if (!empty($board->user->is_runteq_student) && $board->user->is_runteq_student)
                  <span>🍎</span>
                @endif
                投稿日: {{ $board->created_at->format('Y/m/d H:i') }}
              </div>
              <div class="prose prose-gray max-w-none">
                @php
                  $htmlWithoutImages = preg_replace('/<img[^>]*>/', '', $board->description_html ?? '');
                  $plainDescription = strip_tags($htmlWithoutImages);
                  $maxLength = 100;
                  $shortDescription = Str::limit($plainDescription, $maxLength);
                @endphp

                {{ $shortDescription }}

                @if (Str::length($plainDescription) > $maxLength)
                  <a href="{{ route('boards.show', $board->id) }}" class="text-orange-500 hover:underline ml-1">続きを読む</a>
                @endif
              </div>

              <div class="mt-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">💖 {{ $board->likes_count ?? 0 }} 件のいいね</span>
              </div>
            </div>
          @endforeach
          <div class="mt-4">
            {{ $likedBoards->appends(['view' => 'likes'])->links() }}
          </div>
        @else
          <p class="text-gray-500 text-center">いいねした投稿はまだありません。</p>
        @endif
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const ownButton = document.getElementById('ownTabButton');
      const likesButton = document.getElementById('likesTabButton');
      const ownPosts = document.getElementById('ownPosts');
      const likedPosts = document.getElementById('likedPosts');

      function setActive(button) {
        ownPosts.style.display = 'none';
        likedPosts.style.display = 'none';

        ownButton.classList.remove('active', 'bg-blue-600', 'text-white');
        likesButton.classList.remove('active', 'bg-blue-600', 'text-white');

        button.classList.add('active', 'bg-blue-600', 'text-white');
        if (button === ownButton) {
          ownPosts.style.display = 'block';
        } else {
          likedPosts.style.display = 'block';
        }
      }

      // 初期表示はviewModeで判断（もしくは自分の投稿）
      setActive({{ $viewMode === 'likes' ? 'likesButton' : 'ownButton' }});

      ownButton.addEventListener('click', () => setActive(ownButton));
      likesButton.addEventListener('click', () => setActive(likesButton));
    });
  </script>

  @push('styles')
    <style>
      .tab-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: orange;
        background-color:rgb(255, 255, 255);
        border-radius: 9999px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        outline: none;
      }

      .tab-button:hover {
        background-color:rgb(255, 255, 255);
        transform: scale(1.05);
      }

      .tab-button:focus {
        outline: none;
      }

      .tab-button.active {
        background-color: #ea580c;
        color: white;
      }
    </style>
  @endpush
</x-app-layout>
