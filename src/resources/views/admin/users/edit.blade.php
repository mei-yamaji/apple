<x-admin-layout>
    @section('title', '掲示板編集')
    <div class="bg-white w-full h-screen">
        <div class="w-1/2 mx-auto pt-6 px-8 pb-8">
            <h1 class="text-4xl">ユーザー編集</h1>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
            <form class="rounded mb-4" method="POST" action="{{ route('admin.users.update', $user) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        名前
                    </label>
                    <input
                        class="shadow appearance-none border border-slate-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" type="text" name="name" value={{ $user->name }}
                        dusk="edit-user-name-input">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        メールアドレス
                    </label>
                    <input
                        class="shadow appearance-none border border-slate-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email" type="text" name="email" value={{ $user->email }}
                        dusk="edit-user-email-input">
                </div>
                <div class="flex items-center justify-between">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit" dusk="edit-user-button">
                        登録する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>