<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center">
        <button class="text-gray-500 focus:outline-none lg:hidden mr-4">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <h2 class="text-xl font-semibold text-gray-800">
            @yield('header_title', 'Dashboard')
        </h2>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <button class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
            <i class="bi bi-bell text-xl"></i>
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
        </button>

        <!-- Profile Dropdown (Simplified for now) -->
        <div class="relative group">
            <button class="flex items-center space-x-2 focus:outline-none">
                <img class="w-8 h-8 rounded-full border border-gray-200" 
                     src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Admin').'&background=random' }}" 
                     alt="{{ Auth::user()->name }}">
            </button>
        </div>
    </div>
</header>
