<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <x-nav-link :href="route('norms.index')" :active="request()->routeIs('norms.*')">
                    {{ __('Нормы') }}
                </x-nav-link>

                <x-nav-link :href="route('access.index')" :active="request()->routeIs('access.*')">
                    {{ __('Управление доступами') }}
                </x-nav-link>
            </div>

            @if(auth()->user()->is_admin)
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <x-nav-link :href="route('access.index')" :active="request()->routeIs('access.index')">
                    {{ __('Управление доступами') }}
                </x-nav-link>
            </div>
            @endif
        </div>
    </div>
</nav> 