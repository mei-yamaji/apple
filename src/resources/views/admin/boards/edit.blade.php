<x-admin-layout>
<style>
  #imageUpload {
    font-size: 1rem;
    padding: 8px 12px;
    height: auto;
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    box-sizing: border-box;
  }
  #description {
    resize: vertical; /* 縦リサイズのみ */
    max-height: 300px;
    min-height: 150px;
  }
</style>

@section('title', '掲示板編集')

<div class="bg-white w-full min-h-screen py-10">
  <div class="max-w-xl mx-auto px-6">
    <h1 class="text-4xl font-bold mb-8">掲示板編集</h1>

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
        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">タイトル</label>
        <input
          id="title"
          type="text"
          name="title"
          value="{{ old('title', $board->title) }}"
          class="shadow appearance-none border border-slate-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          dusk="edit-board-title-input"
          required
        >
      </div>

      <div>
        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">本文</label>
        <textarea
          id="description"
          name="description"
          rows="10"
          dusk="edit-board-description-input"
          class="shadow appearance-none border border-slate-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        >{{ old('description', $board->description) }}</textarea>
      </div>

      <div>
        <label for="imageUpload" class="block text-gray-700 text-sm font-bold mb-2">画像アップロード</label>
        <input
          type="file"
          id="imageUpload"
          accept="image/*"
          class="border border-gray-300 rounded p-2 w-full"
        >
      </div>

      <div class="flex justify-center">
        <button
          type="submit"
          dusk="edit-board-button"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 text-sm rounded focus:outline-none focus:shadow-outline"
        >
          登録する
        </button>
      </div>
    </form>
  </div>
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
