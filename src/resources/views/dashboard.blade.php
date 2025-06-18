<x-app-layout>
    {{-- フラッシュメッセージ --}}
    @if (session('status'))
        <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 border border-green-300 text-center">
            {{ session('status') }}
        </div>
    @endif

    {{-- ヘッダー --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-900 leading-tight">
            記事ランキング
        </h2>
    </x-slot>

    {{-- タイトル --}}
    <div class="top-wrapper relative">
        <div class="top-inner-text absolute inset-0 flex items-center justify-center">
            <h1 class="text-6xl text-center font-bold" style="color:rgb(118, 58, 21);">APPLE</h1>
        </div>
    </div>

    {{-- コンテンツ部分 --}}
    <div class="container mx-auto px-4 py-8">
        <!-- 切り替えボタン -->
        <div class="tabs flex gap-4 mb-4 justify-center">
            <button onclick="loadBoards('latest')" class="tab-button active" id="btn-latest">最新</button>
            <button onclick="loadBoards('popular')" class="tab-button" id="btn-popular">人気</button>
            <button onclick="loadBoards('views')" class="tab-button" id="btn-views">閲覧</button>
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
                            if (index === 0) rankMark = '🥇';  // 1位
                            else if (index === 1) rankMark = '🥈';  // 2位
                            else if (index === 2) rankMark = '🥉';  // 3位
                        
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
                                <p>投稿者: ${board.user.name}</p>
                                <p>いいね: ${board.likes_count} | 閲覧: ${board.view_count}</p>
                                <p>投稿日: ${new Date(board.created_at).toLocaleDateString()}</p>
                              </div>
                              <div class="flex flex-col justify-end items-end">
                                ${profileImgHtml}
                              </div>
                            </div>
                        `;
                    });

                    // ボタンの active クラス切り替え
                    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                    document.getElementById(`btn-${type}`).classList.add('active');
                })
                .catch(() => {
                    alert('ランキングの読み込みに失敗しました');
                });
        }

        // ページ読み込み時に最新を表示
        document.addEventListener('DOMContentLoaded', () => loadBoards('latest'));
    </script>

    @push('styles')
    <style>
        .tab-button {
            padding: 10px 20px;
            border: none;
            background: #eee;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .tab-button.active {
            background-color: #f97316;
            color: white;
        }

        .board-card h3 {
            margin: 0 0 5px;
        }
    </style>
    @endpush
</x-app-layout>
