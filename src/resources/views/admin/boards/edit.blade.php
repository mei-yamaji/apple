<x-admin-layout>
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">掲示板編集</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.boards.update', $board) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-gray-700 text-sm font-medium mb-2">タイトル</label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title', $board->title) }}"
                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition"
                    dusk="edit-board-title-input"
                    required
                >
            </div>

            <div>
                <label for="description" class="block text-gray-700 text-sm font-medium mb-2">本文</label>
                <textarea
                    id="description"
                    name="description"
                    rows="10"
                    dusk="edit-board-description-input"
                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition resize-y max-h-72 min-h-[150px]"
                    required
                >{{ old('description', $board->description) }}</textarea>
            </div>

            <div>
                <label for="imageUpload" class="block text-gray-700 text-sm font-medium mb-2">画像アップロード</label>
                <input
                    type="file"
                    id="imageUpload"
                    accept="image/*"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition"
                >
            </div>

            <div class="flex items-center justify-between">
                <button
                    type="submit"
                    dusk="edit-board-button"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline text-sm"
                >
                    <i class="ri-refresh-line"></i> 更新する
                </button>
                <a href="{{ route('admin.boards.index') }}" class="text-gray-600 hover:text-gray-800 text-sm underline">一覧に戻る</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('imageUpload').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("boards.image-upload") }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.url) {
                    const textarea = document.getElementById('description');
                    const markdown = `![画像](${data.url})\n`;
                    textarea.value += markdown;
                    alert('画像をアップロードしました');
                    this.value = '';
                } else {
                    alert('画像アップロードに失敗しました');
                }
            })
            .catch(() => alert('通信エラーが発生しました'));
        });
    </script>
</x-admin-layout>
