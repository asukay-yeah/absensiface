<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.cdnfonts.com/css/sofia-pro" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../index.css">
    <title>Home Screen Admin</title>
</head>

<body class="text-neutral-900 bg-slate-100" style="font-family: 'Sofia Pro', sans-serif;">

    @include('component.navbar')
    @include('component.sidebar')

    <div class="p-5 sm:ml-64 text-white">
        <div class="rounded-lg mt-[4.5rem] p-3 flex flex-col items-start justify-center">

            <!-- Dashboard Grid -->
            <div id="main-content" class="flex flex-col w-full">
                <p class="text-[#252C58] text-3xl font-semibold">Dashboard</p>
                <p class="text-slate-500 mt-2 mb-10">Hari ini tanggal : <span class="font-semibold" id="localdate"></span></p>
                <div class="flex gap-5 flex-row grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2">
                    <div class="max-w-2xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                        <div
                            class="card flex flex-col bg-white text-black shadow-lg p-4 rounded-md border border-neutral-300">
                            <div class="flex flex-row items-center justify-between">
                                <p class="text-lg font-medium text-slate-500">Total pegawai</p>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-11 p-3 bg-slate-100 rounded-full text-neutral-800">
                                    <path
                                        d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                                </svg>
                            </div>
                            <p class="text-5xl font-semibold mt-2 text-[#252C58]">{{ $karyawan->count() }}</p>
                        </div>
                        <div
                            class="card flex flex-col bg-white text-black shadow-lg p-4 rounded-md border border-neutral-300">
                            <div class="flex flex-row items-center justify-between">
                                <p class="text-lg font-medium text-slate-500">Pegawai magang</p>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-11 p-3 bg-slate-100 rounded-full text-neutral-800">
                                    <path
                                        d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                                </svg>
                            </div>
                            <p class="text-5xl font-semibold mt-2 text-[#252C58]">{{ $magang }}</p>
                        </div>
                        <div
                            class="card flex flex-col bg-white text-black shadow-lg p-4 rounded-md border border-neutral-300">
                            <div class="flex flex-row items-center justify-between">
                                <p class="text-lg font-medium text-slate-500">Kehadiran hari ini</p>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-11 p-3 bg-slate-100 rounded-full text-neutral-800">
                                    <path
                                        d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                                </svg>
                            </div>
                            <p class="text-5xl font-semibold mt-2 text-[#252C58]">{{ $hadir }}</p>
                        </div>
                        <div
                            class="card flex flex-col bg-white text-black shadow-lg p-4 rounded-md border border-neutral-300">
                            <div class="flex flex-row items-center justify-between">
                                <p class="text-lg font-medium text-slate-500">Tidak hadir hari ini</p>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-11 p-3 bg-slate-100 rounded-full text-neutral-800">
                                    <path
                                        d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                                </svg>
                            </div>
                            <p class="text-5xl font-semibold mt-2 text-[#252C58]">{{ $alpha }}</p>
                        </div>

                    </div>


                    <!-- Grafik Kehadiran Harian -->
                    <div class="bg-blue-900 shadow-lg rounded-md max-w-full">
                        <div class="flex justify-between items-center p-5">
                            <div class="flex-col flex justify-start">
                                <p class="font-medium text-xl">Grafik Kehadiran Pegawai Minggu Ini</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-10 p-1 bg-slate-100 rounded-full text-blue-900">
                                <path fill-rule="evenodd"
                                    d="M3 2.25a.75.75 0 0 0 0 1.5v16.5h-.75a.75.75 0 0 0 0 1.5H15v-18a.75.75 0 0 0 0-1.5H3ZM6.75 19.5v-2.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75ZM6 6.75A.75.75 0 0 1 6.75 6h.75a.75.75 0 0 1 0 1.5h-.75A.75.75 0 0 1 6 6.75ZM6.75 9a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM6 12.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 6a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75Zm-.75 3.75A.75.75 0 0 1 10.5 9h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 12a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM16.5 6.75v15h5.25a.75.75 0 0 0 0-1.5H21v-12a.75.75 0 0 0 0-1.5h-4.5Zm1.5 4.5a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 2.25a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75h-.008ZM18 17.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div id="chart-harian" class="mx-5"></div>
                    </div>
                </div>
                 <!-- Chart Tepat Waktu harian -->
                 <div class="max-w-full w-full bg-white rounded-lg shadow-sm mt-5 p-6">
                        <div class="flex justify-between items-center w-full">
                            <div class="flex-col items-center">
                                <div class="flex items-center mb-1">
                                    <h5 class="text-xl font-bold leading-none text-gray-900 me-1">
                                        Presentase Tepat Waktu dan Terlambat Hari Ini</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Line Chart -->
                        <div class="w-full justify-center flex items-center">
                            <div class="py-3 xl:w-[55%] md:w-[55%] w-full" id="pie-chart"></div>
                        </div>
                        <div class="grid grid-cols-1 items-center border-gray-200 border-t justify-start">
                            <div class="flex justify-start items-center pt-5">
                                <!-- Button -->
                                <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                    data-dropdown-placement="bottom"
                                    class="text-sm font-medium text-gray-500 hover:text-gray-900 text-center inline-flex items-center"
                                    type="button">
                                    Today
                                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <div id="lastDaysdropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 border border-neutral-300">
                                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Today</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Yesterday</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Last
                                                7 days</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Last
                                                30 days</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Kehadiran 6 Bulan -->
                    <div class="bg-blue-900 shadow-lg rounded-md max-w-full h-fit mt-5 mb-10">
                        <div class="flex flex-row justify-between items-center p-5">
                            <div class="flex-col flex justify-start">
                                <p class="font-medium text-xl">Grafik Kehadiran Pegawai Selama 1 Tahun</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-10 p-1 bg-slate-100 rounded-full text-blue-900">
                                <path fill-rule="evenodd"
                                    d="M3 2.25a.75.75 0 0 0 0 1.5v16.5h-.75a.75.75 0 0 0 0 1.5H15v-18a.75.75 0 0 0 0-1.5H3ZM6.75 19.5v-2.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75ZM6 6.75A.75.75 0 0 1 6.75 6h.75a.75.75 0 0 1 0 1.5h-.75A.75.75 0 0 1 6 6.75ZM6.75 9a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM6 12.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 6a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75Zm-.75 3.75A.75.75 0 0 1 10.5 9h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 12a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM16.5 6.75v15h5.25a.75.75 0 0 0 0-1.5H21v-12a.75.75 0 0 0 0-1.5h-4.5Zm1.5 4.5a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 2.25a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75h-.008ZM18 17.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div id="chart-mingguan" class="mx-5"></div>
                    </div>
            </div>
        </div>
    </div>


    <script>
        // data chart pie
        const tepat = Number({{$tepat ?? 0}});
        const terlambat = Number({{$terlambat ?? 0}});

        // data chart bar harian 
        const hadirPerHari = @json($hadirPerHari);
        const tidakHadirPerHari = @json($tidakHadirPerHari);

        // data chart bar bulan 
        const monthlyLabels = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // Data dari Laravel
        const hadirPerBulan = @json($hadirPerBulan);
        const tidakHadirPerBulan = @json($tidakHadirPerBulan);



        function showDate() {
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }
            const today = new Date();
            const date = today.toLocaleDateString('id-ID', options);
            document.getElementById('localdate').innerHTML = date;
        }
        window.onload = showDate();
    </script>

    <script src="{{ asset('js/dashboard.js') }}"></script>


</body>

</html>