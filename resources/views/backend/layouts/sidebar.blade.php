<aside class="w-64 bg-gray-900 text-white flex flex-col transition-all duration-300" id="sidebar">
    <!-- Brand -->
    <div class="h-16 flex items-center justify-center border-b border-gray-800">
        <!-- arrow btn for back to website -->
        <a href="{{ route('frontend.home') }}" class="text-xl  px-2 font-bold tracking-wider leading-none">
            <i class="bi bi-arrow-left"></i>
        </a>
     
        <h1 class="text-xl font-bold tracking-wider leading-none">
            <span class="text-indigo-500">LMS</span> ADMIN
        </h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white border-r-4 border-indigo-500' : '' }}">
                    <i class="bi bi-speedometer2 text-lg mr-3"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                User Management
            </li>

            @can('roles.view')
            <li>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-gray-800 text-white border-r-4 border-indigo-500' : '' }}">
                    <i class="bi bi-shield-lock text-lg mr-3"></i>
                    <span class="font-medium">Roles & Permissions</span>
                </a>
            </li>
            @endcan
            
            @can('users.view')
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
                    <i class="bi bi-people text-lg mr-3"></i>
                    <span class="font-medium">Users</span>
                </a>
            </li>
            @endcan
                <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Course Management
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white border-r-4 border-indigo-500' : '' }}">
                    <i class="bi bi-book text-lg mr-3"></i>
                    <span class="font-medium">Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.index') }}" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('admin.courses.*') ? 'bg-gray-800 text-white border-r-4 border-indigo-500' : '' }}">
                    <i class="bi bi-book text-lg mr-3"></i>
                    <span class="font-medium">Courses</span>
                </a>
            </li>
            
            <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Finance
            </li>
            <li>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-gray-800 text-white border-r-4 border-indigo-500' : '' }}">
                    <i class="bi bi-wallet2 text-lg mr-3"></i>
                    <span class="font-medium">Payment History</span>
                </a>
            </li>
    </nav>
    
    <!-- User Footer -->
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ Auth::user()->name ?? 'Admin' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ Auth::user()->email ?? '' }}
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
