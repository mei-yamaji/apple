<x-app-layout>
    {{-- フラッシュメッセージ --}}
    @if (session('status'))
        <div class="flex justify-center mb-6">
            <div class="flex items-center space-x-2 max-w-lg w-full p-3 bg-green-50 text-green-700 rounded-md border border-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-center text-sm font-medium">
                    {{ session('status') }}
                </p>
            </div>
        </div>
    @endif

    {{-- ヘッダー --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight text-center">
            記事ランキング
        </h2>
    </x-slot>

    {{-- コンテンツ部分 --}}
        <div class="container mx-auto px-4 py-8">
        <!-- 切り替えボタン -->
    <div class="tabs flex justify-center gap-6 mb-6">
        <span class="text-5xl">🍎</span>
        <span class="text-5xl">🍎</span>
        <x-primary-button 
            onclick="loadBoards('latest')" 
            class="tab-button text-xl px-12 py-4"
            id="btn-latest">
            最新
        </x-primary-button>
        <x-primary-button 
            onclick="loadBoards('popular')" 
            class="tab-button text-xl px-12 py-4"
            id="btn-popular">
            人気
        </x-primary-button>
        <x-primary-button 
            onclick="loadBoards('views')" 
            class="tab-button text-xl px-12 py-4"
            id="btn-views">
            閲覧
        </x-primary-button>
        <span class="text-5xl">🍎</span>
        <span class="text-5xl">🍎</span>
    </div>

        <div id="ranking-container"></div>

        <div id="boards-container" class="grid gap-4">
            <!-- ランキング表示領域 -->
        </div>
    </div>

     {{-- JS --}}
    <script>
        function loadBoards(type) {
    fetch(`/boards/ranking/${type}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('boards-container');
            container.innerHTML = ''; // 一旦クリア

            data.forEach((board, index) => {
                let rankMark = '';
                if (index === 0) rankMark = '🥇';
                else if (index === 1) rankMark = '🥈';
                else if (index === 2) rankMark = '🥉';

                let profileImgHtml = '';
                if (board.user.profile_image) {
                    profileImgHtml = `
                         <img src="/storage/${board.user.profile_image}" 
                              alt="Profile Image" 
                              class="w-16 h-16 rounded-full object-cover mr-3" />
                    `;
                } else {
                    profileImgHtml = `
                         <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                            <span class="text-gray-500 text-sm">No Image</span>
                         </div>
                    `;
                }

                container.innerHTML += `
                  <div class="board-item border p-4 rounded shadow bg-white flex items-center justify-between gap-4">
                    <div>
                      <span class="rank-mark text-xl">${rankMark}</span>
                      <h3 class="text-lg font-semibold">
                        <a href="${board.detail_url}" class="text-orange-600 hover:underline">
                          ${board.title}
                        </a>
                      </h3>
                      <p>
                        投稿者: ${board.user.name}
                        ${board.user.is_runteq_student ? '<span>🍎</span>' : ''}
                      </p>
                      <p>いいね: ${board.likes_count} | 閲覧: ${board.view_count}</p>
                      <p>投稿日: ${new Date(board.created_at).toLocaleDateString()}</p>
                    </div>
                    <div class="flex flex-col justify-end items-end">
                      ${profileImgHtml}
                    </div>
                  </div>
                `;
            });

            // ここで active クラスを付ける（色を変える）
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
            });
            const activeBtn = document.getElementById(`btn-${type}`);
            if (activeBtn) {
                activeBtn.classList.add('active', 'bg-blue-600', 'text-white');
            }
        })
        .catch(() => {
            alert('ランキングの読み込みに失敗しました');
        });
    }

    </script>

    @push('styles')
    <style>
        .tab-button {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Tailwindの gap-2 = 0.5rem */
            padding: 1rem 1.75rem; 
            font-size: 1.125rem; 
            font-weight: 600; /* font-semibold */
            color: white;
            background-color: #fb923c; /* Tailwind orange-400 (#fb923c) */
            border-radius: 9999px; /* rounded-full */
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* shadow-md相当 */
            border: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            outline: none;
        }

        .tab-button:hover {
            background-color: #f97316; /* orange-500 */
            transform: scale(1.05);
        }

        .tab-button:focus {
            outline: none;
        }

        .tab-button.active {
            background-color: #ea580c; /* ちょっと濃いオレンジ */
            color: white;
        }
    </style>
    @endpush

    </x-app-layout>
