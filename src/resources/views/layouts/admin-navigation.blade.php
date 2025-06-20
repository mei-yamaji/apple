<header>
    <!--Nav-->
    <nav aria-label="menu nav" class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                <a href="/admin" aria-label="Home">
                    <span class="text-xl pl-2">APPLE BOARD</span>
                </a>
            </div>
            <div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('admin.logout')"
                        onclick="event.preventDefault();
                            this.closest('form').submit();"
                        class="p-2 text-white text-sm no-underline hover:bg-gray-800 hover:text-white hover:border-none hover:outline-none focus:bg-gray-800 focus:text-white focus:border-none focus:outline-none">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </nav>
</header>