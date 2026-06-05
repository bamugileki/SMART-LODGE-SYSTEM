<nav x-data="{ 
    open: false, 
    scrolled: false,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20;
        });
    }
}" 
    x-bind:class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-white shadow-sm'" 
    class="sticky top-0 z-50 transition-all duration-300 ease-out">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        <span class="text-white font-bold text-sm">BS</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900 transition-colors duration-300">Bunge<span class="text-indigo-600">Stay</span></span>
                </a>
                <div class="hidden md:flex ml-10 space-x-1">
                    <a href="{{ route('home') }}" class="relative text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Home</a>
                    <a href="{{ route('rooms.index') }}" class="relative text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Rooms</a>
                    <a href="{{ route('services.index') }}" class="relative text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Services</a>
                    <a href="{{ route('contact') }}" class="relative text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Contact</a>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden md:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Admin</a>
                        <a href="{{ route('admin.payroll.index') }}" class="hidden md:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Payroll</a>
                        <a href="{{ route('admin.audit-logs') }}" class="hidden md:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Audit Logs</a>
                    @endif
                    @if (Auth::user()->isManager())
                        <a href="{{ route('manager.dashboard') }}" class="hidden md:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Manager</a>
                        <a href="{{ route('manager.audit-logs') }}" class="hidden md:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Activity Log</a>
                    @endif
                    @if (Auth::user()->isReceptionist())
                        <a href="{{ route('receptionist.dashboard') }}" class="hidden md:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-indigo-600 after:scale-x-0 after:transition-transform after:duration-300 hover:after:scale-x-100">Reception</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-all duration-200 hover:scale-105 active:scale-95">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 text-sm font-medium transition-all duration-200 hover:scale-105">Log in</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 active:scale-95">Register</a>
                @endauth
                <button @click="open = !open" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200" x-bind:aria-expanded="open">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.outside="open = false"
             class="md:hidden pb-4 space-y-1">
            <a href="{{ route('home') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Home</a>
            <a href="{{ route('rooms.index') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Rooms</a>
            <a href="{{ route('services.index') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Services</a>
            <a href="{{ route('contact') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Contact</a>
            @auth
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Admin Dashboard</a>
                    <a href="{{ route('admin.payroll.index') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Payroll</a>
                    <a href="{{ route('admin.audit-logs') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Audit Logs</a>
                @endif
                @if (Auth::user()->isManager())
                    <a href="{{ route('manager.dashboard') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Manager Dashboard</a>
                    <a href="{{ route('manager.audit-logs') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Activity Log</a>
                @endif
                @if (Auth::user()->isReceptionist())
                    <a href="{{ route('receptionist.dashboard') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Reception Dashboard</a>
                @endif
                <a href="{{ route('wishlist.index') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Wishlist</a>
                <a href="{{ route('dashboard') }}" @click="open = false" class="block px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg text-sm transition-all duration-200 hover:translate-x-1">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" @click="open = false">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg text-sm transition-all duration-200">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
