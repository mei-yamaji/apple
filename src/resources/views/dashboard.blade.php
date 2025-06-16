<x-app-layout>
    {{-- フラッシュメッセージ --}}
    @if (session('status'))
        <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 border border-green-300 text-center">
            {{ session('status') }}
        </div>
    @endif

    {{-- ヘッダー --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

    <!-- 記事リスト -->
    <div id="board-list" class="mt-4">
        読み込み中...
    </div>
</div>

    {{-- CSS --}}
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

    <!-- 記事リスト 
    {{-- JS --}}
@push('scripts')
<script>
    function setActiveButton(type) {
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById('btn-' + type).classList.add('active');
    }

    function loadBoards(type) {
        setActiveButton(type);

        fetch(`/boards/${type}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('board-list');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = '<p>ボードが見つかりません。</p>';
                    return;
                }

                data.forEach(board => {
                    container.innerHTML += `
                        <div class="board-card border p-4 mb-4 bg-white rounded shadow">
                            <h3 class="text-lg font-bold mb-2">${board.title}</h3>
                            <p class="mb-1">${board.summary ?? ''}</p>
                            <small class="text-gray-600">閲覧数: ${board.view_count ?? 0}</small>
                        </div>
                    `;
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadBoards('latest');
    });
</script>
@endpush　-->
</x-app-layout>
