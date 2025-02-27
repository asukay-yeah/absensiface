<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Screen Admin</title>

    <!-- Styles -->
    <link href="https://fonts.cdnfonts.com/css/sofia-pro" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../index.css">

    <!-- Core Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>

    <!-- Export Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>

<body class="text-neutral-900 bg-slate-100" style="font-family: 'Sofia Pro', sans-serif;">

    @include('component.navbar')
    @include('component.sidebar')

    <div class="p-5 sm:ml-64 text-white">
        <div class="rounded-lg mt-[4.5rem] p-3 flex flex-col items-start justify-center">
            <!-- Report Pegawai -->
            <div id="report-pegawai-content" class="max-w-full">
                <p class="text-[#252C58] text-3xl font-semibold">Data report kehadiran pegawai</p>
                <p class="text-slate-500 mt-2 mb-10">Data data absensi pegawai Balmon Jakarta</p>

                <div class="p-5 bg-white rounded-lg shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-start md:justify-between xl:justify-between mb-4">
                        <!-- Search Bar -->
                        <input class="px-3 py-1.5 border border-neutral-300 text-black rounded-md w-full sm:w-64"
                            type="search" placeholder="Cari nama pegawai..." />

                        <!-- Export Button -->
                        <div class="relative">
                            <button id="exportDropdownButton"
                                class="md:ml-2 md:mt-0 mt-4 flex items-center border border-neutral-300 bg-white px-4 py-2 text-sm font-medium rounded-md hover:bg-gray-100 duration-100 text-neutral-900">
                                Export as
                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="exportDropdown"
                                class="hidden absolute md:right-0 mt-2 w-40 bg-white border border-neutral-300 rounded-md shadow-md">
                                <ul class="py-1 text-sm text-neutral-700">
                                    <li><button id="export-excel"
                                            class="block w-full text-left px-4 py-3 hover:bg-gray-100 duration-100">Export
                                            to
                                            Excel</button></li>
                                    <li><button id="export-pdf"
                                            class="block w-full text-left px-4 py-3 hover:bg-gray-100 duration-100">Export
                                            to
                                            PDF</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <table id="export-table" class="xl:table-fixed md:table-auto table-auto border">
                        <thead>
                            <tr>
                                <th class="border bg-slate-100 text-center">
                                    <span class="flex items-center justify-center">
                                        No
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Nama Pegawai
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Jam Datang
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Jam Pulang
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        No Meja
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Status
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Keterangan
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100" data-type="date" data-format="YYYY/DD/MM">
                                    <span class="flex items-center">
                                        Tanggal
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Menambahkan nomor pada setiap baris -->
                            @foreach ($kehadiran as $absen)
                            <tr class="hover:bg-gray-100 cursor-pointer">
                                <td class="font-medium text-neutral-900 whitespace-nowrap border text-center">
                                    {{ $loop->iteration }}</td>
                                <td class="border">{{ $absen->pegawai->nama }}</td>
                                <td class="border">{{ $absen->jam_masuk ?? '-' }}</td>
                                <td class="border">{{ $absen->jam_pulang ?? '-' }}</td>
                                <td class="border">{{ $absen->nomor_duduk ?? '-' }}</td>
                                <td class="border">{{ $absen->status ?? '-' }}</td>
                                <td class="border">{{ $absen->keterangan ?? '-' }}</td>
                                <td class="border">{{ $absen->tanggal }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/reportpegawai.js') }}"></script>
</body>

</html>