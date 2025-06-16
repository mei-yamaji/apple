<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-3xl font-bold mb-4">{{ $board->title }}</h1>
            <p class="mb-4">{{ $board->description }}</p>
            <p class="text-sm text-gray-600">閲覧数: {{ $board->view_count }}</p>
            <p class="text-sm text-gray-600">いいね数: {{ $board->like_count }}</p>
            <a href="{{ url()->previous() }}" class="text-blue-500 mt-4 inline-block">戻る</a>
        </div>
    </div>
</x-app-layout>