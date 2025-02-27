<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white shadow-md sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-3 font-medium">
            <li>
                <a href="{{ url('/home') }}"
                    class="sidebar-item flex items-center rounded-lg p-2 {{ Route::is('home') ? 'bg-slate-200 text-blue-800' : 'text-neutral-800  hover:bg-slate-100' }} group mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z"
                            clip-rule="evenodd" />
                        <path fill-rule="evenodd"
                            d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <!-- Dropdown Pegawai -->
            <li>
                <button
                    class="sidebar-item flex items-center justify-between w-full p-2 text-neutral-800 rounded-lg hover:bg-slate-100 group">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path
                                d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                        </svg>
                        <span class="ms-3">Pegawai</span>
                    </div>
                </button>
                <ul id="pegawai-dropdown-menu" class=" pl-12  space-y-2">
                    <li>
                        <a href="{{ url('/data') }}"
                            class="sidebar-item flex items-center p-2 text-[14px] rounded-lg {{ Route::is('data.index') ? 'bg-slate-200 text-blue-800' : 'text-neutral-800  hover:bg-slate-100' }} group">
                            <span class="flex-1">Data Pegawai</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Dropdown Report -->
            <li>
                <button
                    class="sidebar-item flex items-center justify-between w-full p-2 text-neutral-800 rounded-lg hover:bg-slate-100 group">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path
                                d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                        </svg>
                        <span class="ms-3">Report</span>
                    </div>
                    <!-- Icon Panah -->
                </button>
                <ul id="report-dropdown-menu" class="pl-12 mt-1 space-y-2">
                    <li>
                        <a href="{{ url('/report') }}"
                            class="sidebar-item flex text-[14px] items-center p-2 rounded-lg {{ Route::is('report') ? 'bg-slate-200 text-blue-800' : 'text-neutral-800  hover:bg-slate-100' }} group">
                            <span class="flex-1">Report Pegawai</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>