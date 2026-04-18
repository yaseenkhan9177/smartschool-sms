<nav class="flex-1 overflow-y-auto py-6 space-y-1">
    <div class="px-6 mb-2">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Super Admin Panel</p>
    </div>

    <a href="{{ route('super_admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('super_admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-all">
        <i class="fa-solid fa-chart-pie w-5"></i>
        Dashboard
    </a>

    <div class="px-6 mt-8 mb-2">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">School Management</p>
    </div>

    <a href="{{ route('super_admin.create_school') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('super_admin.create_school') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
        <i class="fa-solid fa-school w-5 group-hover:text-blue-400 transition-colors"></i>
        Add New School
    </a>

    <a href="{{ route('super_admin.requests.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('super_admin.requests.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
        <i class="fa-solid fa-envelope-open-text w-5 group-hover:text-purple-400 transition-colors"></i>
        Registration Requests
    </a>

    <a href="{{ route('super_admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition-colors group">
        <i class="fa-solid fa-list w-5 group-hover:text-indigo-400 transition-colors"></i>
        View All Schools
    </a>

    <div class="px-6 mt-8 mb-2">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">License Keys</p>
    </div>

    <a href="{{ route('super_admin.licenses.create') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('super_admin.licenses.create') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
        <i class="fa-solid fa-key w-5 group-hover:text-yellow-400 transition-colors"></i>
        Generate New Key
    </a>

    <a href="{{ route('super_admin.licenses.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('super_admin.licenses.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
        <i class="fa-solid fa-certificate w-5 group-hover:text-emerald-400 transition-colors"></i>
        Active Licenses
    </a>



    <div class="px-6 mt-8 mb-2">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">System</p>
    </div>

    <a href="{{ route('super_admin.settings') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('super_admin.settings') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
        <i class="fa-solid fa-gear w-5 group-hover:text-white transition-colors"></i>
        Settings
    </a>
</nav>