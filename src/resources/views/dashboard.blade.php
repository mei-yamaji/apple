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
        // タグ切り替え・ランキング取得処理
        function loadBoards(type) {
            fetch(`/boards/ranking/${type}`)
            .then(res => {
                if (!res.ok) throw new Error(res.statusText);
                return res.json();
            })
            .then(data => {
                const container = document.getElementById('boards-container');
                if (!container) {
                console.error('容器要素#boards-containerが見つかりません');
                return;
                }

                container.innerHTML = ''; // 内容リセット

                data.forEach((board, idx) => {
                // ランク絵文字
                let mark = ['🥇', '🥈', '🥉'][idx] || '';

                // プロフィール画像 or プレースホルダー
                const imgHtml = board.user.profile_image
                    ? `<img src="/storage/${board.user.profile_image}" class="w-16 h-16 rounded-full object-cover mr-3" alt="Profile Image">`
                    : `<div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                        <span class="text-gray-500 text-sm">No Image</span>
                    </div>`;

                // 投稿日時フォーマット（日本時間）
                const date = new Date(board.created_at);
                const dateStr = date.toLocaleString('ja-JP', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // 説明文（画像タグ除去＋他タグ除去＋長さ制限）
                const descRaw = board.description_html || '';
                const descNoImages = descRaw.replace(/<img[^>]*>/g, '');
                const descText = descNoImages.replace(/<[^>]+>/g, '');
                const shortDesc = descText.length > 100 ? descText.slice(0, 100) + '... ' : descText;

                // 「続きを読む」リンク（100文字以上の場合のみ）
                const moreLink = descText.length > 100
                    ? `<a href="${board.detail_url}" class="text-orange-500 hover:underline ml-1">続きを読む</a>`
                    : '';

                container.innerHTML += `
                    <div class="border rounded-2xl shadow-md p-6 mb-1 bg-white flex">
                    <div class="mr-4 flex-shrink-0 flex flex-col justify-center items-center text-2xl">${mark}</div>
                    <div class="flex-grow">
                        <h2 class="text-2xl font-semibold text-orange-600 hover:underline mb-2">
                        <a href="${board.detail_url}">${board.title}</a>
                        </h2>
                        <div class="text-sm text-gray-500 mb-4">
                        投稿者: ${board.user.name}${board.user.is_runteq_student ? '<span>🍎</span>' : ''}
                        投稿日: ${dateStr}
                        </div>
                        <div class="prose prose-gray max-w-none">${shortDesc}${moreLink}</div>
                        <div class="mt-4 flex items-center gap-4">
                        <span class="text-sm text-gray-600">💖 ${board.likes_count} 件のいいね</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center">
                        ${imgHtml}
                    </div>
                    </div>
                `;
                });

                // タブの active 更新
                document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                });
                const activeBtn = document.querySelector(`#btn-${type}`);
                activeBtn?.classList.add('active', 'bg-blue-600', 'text-white');
            })
            .catch(err => {
                console.error(err);
                alert('ランキングの取得に失敗しました');
            });
        }

        // ページ読み込み時に「最新」を強制読み込み
        document.addEventListener('DOMContentLoaded', () => {
            loadBoards('latest');
        });
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
