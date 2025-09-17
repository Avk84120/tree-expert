<!-- Add once near top of your layout (head or before closing body) -->
<style>[x-cloak]{ display: none !important; }</style>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Replace your previous small user block with this -->
<div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
    <div x-data="{ open: false }" class="relative inline-block text-left">
        <!-- Trigger -->
        <button
            @click="open = !open"
            @keydown.escape.window="open = false"
            aria-haspopup="true"
            :aria-expanded="open"
            class="flex items-center gap-2 text-gray-700 dark:text-gray-200 font-medium focus:outline-none px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
            <span>{{ Auth::user()->name }}</span>
            <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown -->
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.away="open = false"
            class="origin-top-right absolute right-15 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50 max-h-96 overflow-auto"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="user-menu-button"
        >
            <div class="py-1">
                <!-- Profile -->
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700" role="menuitem">
                    👤 Profile
                </a>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <!-- Project Management -->
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Project Management</div>
                <a href="{{ route('admin.projects.create') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">➕ Create Project</a>
                <!-- <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">✏️ Update Project</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">🗑 Delete Project</a> -->
<a href="{{ route('admin.projects.index') }}"
   class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
   role="menuitem">
   👥 Assign User
</a>
              <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">📋 Project List</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">⚙️ Project Settings</a>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <!-- Tree Survey -->
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Tree Survey</div>
                <a href="{{ route('admin.trees.index')}}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">🌳 Add Tree Data</a>
                <!-- <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">✏️ Update Tree Data</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">🗑 Delete Tree Data</a> -->
                <!-- <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">📂 Tree Data List</a> -->
                <a href="{{ route('admin.trees.export') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">📥 Import via Excel</a>
                <a href="{{route('admin.trees.geotag') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">📍 Geo-Tag Tree</a>
                <a href="{{ route('admin.trees.search') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">🔍 Tree Search</a>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <!-- More groups (Plantation, Media, Reports, Utility) -->
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Miyawaki Plantation</div>
                <a href="{{ route('plantations.index')}}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">🌱 Create Plantation Project</a>
<a href="{{ route('plantation.select') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
    📍 Select Plantation Land
</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">🌳 Add Plantation Tree</a>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Media & Files</div>
                <a href="{{ route('media.upload.tree') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">📸 Upload Tree Photos</a>
                <a href="{{ route('media.tree_photos.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">🌳 View Tree Photos</a>
                <a href="{{ route('media.upload.aadhaar') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">🆔 Upload Aadhaar</a>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Reports</div>
                <a href="{{route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">📊 Dashboard Data</a>
                <a href="{{ route('admin.media.reports') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">📘 Master Report</a>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Utility & Settings</div>
               <a href="{{ route('tree.names.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">🌲 Tree Name Master</a>
               <a href="{{ route('map.trees.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">🗺 Map Tree</a>


                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-red-600 hover:bg-gray-100 py-2 rounded dark:hover:bg-gray-700">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
