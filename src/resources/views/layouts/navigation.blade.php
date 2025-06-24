<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Logo + Dashboard -->
            <div class="flex items-center space-x-4">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                <!-- Dashboard Button -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1 px-3 py-1 text-xs font-semibold text-white bg-orange-400 rounded-full shadow-md hover:bg-orange-500 hover:scale-105 transition-all duration-200 focus:outline-none">
                    <i class="ri-home-4-line text-base"></i>
                    <span>ホーム</span>
                </a>
            </div>

            <!-- Right Side: Notification + Post + User -->
<div class="hidden sm:flex sm:items-center sm:space-x-4">
 
    <!-- Notification Button -->
<div class="relative">
<button id="notificationButton" class="relative text-gray-600 hover:text-orange-500">
<i class="ri-notification-3-line text-2xl"></i>

            @if (auth()->user()->unreadNotifications->count() > 0)
<!-- 通知件数バッジ -->
<span id="notificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5" 
      style="{{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'display: none;' }}">
    {{ Auth::user()->unreadNotifications->count() }}
</span>

            @endif
</button>
 
    <!-- 通知ドロップダウン -->
    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white border rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto p-4">

        <!-- 選択既読フォーム -->
        <form id="markReadForm">
            @csrf

            <!-- ボタンエリア -->
            <div class="flex justify-between items-center mb-4">
                <!-- 選択した通知を既読にするボタン -->
                <button type="submit"
                    class="px-3 py-1 bg-orange-400 hover:bg-orange-500 text-white rounded-full shadow hover:scale-105 transition-all duration-200">
                    選択した通知を既読にする
                </button>

                <!-- すべて既読にするボタン -->
                <button type="button" id="markAllReadBtn"
                    class="px-3 py-1 bg-gray-400 hover:bg-gray-500 text-white rounded-full shadow hover:scale-105 transition-all duration-200">
                    すべて既読にする
                </button>
            </div>

            <!-- 通知リスト -->
            @forelse ($notifications as $notification)
                @php
                    $type = class_basename($notification->type);
                    $message = $notification->data['message'] ?? '新しい通知があります';
                    $url = $notification->data['url'] ?? '#';
                    $isUnread = is_null($notification->read_at);

                    // 色分け
                    if ($type === 'LikeNotification') {
                        $bgColor = $isUnread ? 'bg-orange-100' : 'bg-gray-100';
                        $icon = '<i class="ri-heart-fill text-red-500 text-2xl"></i>';
                    } elseif ($type === 'CommentNotification') {
                        $bgColor = $isUnread ? 'bg-blue-100' : 'bg-gray-100';
                        $icon = '<i class="ri-chat-3-fill text-blue-500 text-2xl"></i>';
                    } elseif ($type === 'FavoriteNotification') {
                        $bgColor = $isUnread ? 'bg-yellow-100' : 'bg-gray-100';
                        $icon = '<i class="ri-star-fill text-yellow-500 text-2xl"></i>';
                    } elseif ($type === 'FollowNotification') {
                        $bgColor = $isUnread ? 'bg-green-100' : 'bg-gray-100';
                        $icon = '<i class="ri-user-follow-fill text-green-500 text-2xl"></i>';
                    } else {
                        $bgColor = 'bg-gray-100';
                        $icon = '<i class="ri-notification-3-fill text-gray-500 text-2xl"></i>';
                    }
                @endphp

                <div class="flex items-center justify-between p-3 mb-2 rounded {{ $bgColor }}">
                    <div class="flex items-center gap-3">
                        {!! $icon !!}
                        <div>
                            <a href="{{ $url }}" class="text-sm text-gray-800 hover:underline">
                                {{ $message }}
                            </a>
                            <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="w-5 flex justify-center">
                        @if ($isUnread)
                            <input type="checkbox" name="notification_ids[]" value="{{ $notification->id }}"
                                class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 mt-4">通知はありません。</p>
            @endforelse
        </form>
    </div>
</div>
 
                <!-- Post Dropdown -->
                <x-dropdown width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-1 px-3 py-1 text-xs font-semibold text-white bg-orange-400 rounded-full shadow-md hover:bg-orange-500 hover:scale-105 transition-all duration-200 focus:outline-none">
                            <i class="ri-pencil-line text-base"></i>
                            <span>記事ナビ</span>
                            <svg class="ml-1 h-3 w-3 fill-current text-white transition-transform duration-200" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" :class="{ 'rotate-180': open }">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('boards.index')">
                            {{ __('記事一覧') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('boards.create')">
                            {{ __('記事作成') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-1 px-3 py-1 text-xs font-semibold text-white bg-orange-400 rounded-full shadow-md hover:bg-orange-500 hover:scale-105 transition-all duration-200 focus:outline-none">
                            <i class="ri-user-3-line text-base"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ml-1 h-3 w-3 fill-current text-white transition-transform duration-200" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" :class="{ 'rotate-180': open }">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('mypage')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile Menu) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('notificationButton');
        const dropdown = document.getElementById('notificationDropdown');
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        const markReadForm = document.getElementById('markReadForm');

        button.addEventListener('click', function () {
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // すべて既読にする
        markAllReadBtn.addEventListener('click', function () {
            fetch('{{ route('notifications.markAsRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                location.reload(); // 再読み込みで反映
            })
            .catch(error => {
                alert('エラーが発生しました');
                console.error(error);
            });
        });

        // 選択した通知を既読にする
        markReadForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(markReadForm);
            const ids = formData.getAll('notification_ids[]');

            if (ids.length === 0) {
                alert('通知を選択してください。');
                return;
            }

            fetch('{{ route('notifications.markSelectedAsRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    ids.forEach(id => {
                        const checkbox = document.querySelector(`input[value="${id}"]`);
                        if (checkbox) {
                            checkbox.classList.add('hidden'); // チェックボックスを非表示に
                            const parentDiv = checkbox.closest('div.p-3');
                            parentDiv.classList.remove('bg-orange-100', 'bg-blue-100', 'bg-yellow-100', 'bg-green-100');
                            parentDiv.classList.add('bg-gray-100');
                        }
                    });
                } else {
                    alert('処理に失敗しました。');
                }
            })
            .catch(error => {
                alert('エラーが発生しました');
                console.error(error);
            });
        });
    });
</script>