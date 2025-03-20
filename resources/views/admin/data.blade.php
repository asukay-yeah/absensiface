<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Screen Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="https://fonts.cdnfonts.com/css/sofia-pro" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../index.css">

    <!-- Core Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="{{ asset('js/face-api/face-api.min.js') }}"></script>
    <script src="{{ asset('js/face-detection.js') }}"></script>

    <!-- Export Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <style>
        #dropdown1 {
            width: 700px;
            height: auto;
            max-height: 500px;
            overflow-y: auto;
            /* Scroll jika konten terlalu panjang */
            border: 1px solid #ccc;
            border-radius: 8px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
    </style>
</head>

<body class="text-neutral-900 bg-slate-100" style="font-family: 'Sofia Pro', sans-serif;">

    @include('component.navbar')
    @include('component.sidebar')



    <div class="p-5 sm:ml-64 text-white">
        <div class="rounded-lg mt-[4.5rem] p-3 flex flex-col items-start justify-center">

            <!-- Pegawai -->
            <div id="pegawai-content" class="max-w-full">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-col">
                        <p class="text-[#252C58] text-3xl font-semibold">Data pegawai</p>
                        <p class="text-slate-500 mt-2 mb-10">Data data pegawai Balmon Jakarta</p>
                    </div>
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-900 font-medium rounded-md text-sm px-4 py-2 text-center inline-flex items-center"
                        type="button">Tambah Pegawai <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                </div>

                <div class="p-5 bg-white rounded-lg shadow-xl">
                    <form>
                        <div class="search flex flex-col sm:flex-row sm:items-center justify-between">
                            <input type="text" placeholder="Cari nama pegawai..." name="search"
                                value="{{ request('search') }}"
                                class="px-3 py-1.5 border border-gray-300 rounded-lg w-64 text-neutral-900">
                        </div>
                    </form>

                    <table id="export-table-pegawai" class="xl:table-fixed md:table-auto table-auto border">
                        <thead>
                            <tr>
                                <th class="border bg-slate-100" style="width: 40px; text-align: center;">
                                    <span class="cursor-pointer flex flex-row justify-center">
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
                                        NIP
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Jabatan
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Tim Kerja
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </span>
                                </th>
                                <th class="border bg-slate-100">
                                    <span class="flex items-center">
                                        Action
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

                            @foreach ($karyawan as $pegawai)
                            <tr class="hover:bg-gray-100 cursor-pointer">
                                <td class="font-medium text-neutral-900 whitespace-nowrap border text-center">
                                    {{ $loop->iteration }}</td>
                                <td class="border capitalize">{{ $pegawai->nama }}</td>
                                <td class="border ">{{ $pegawai->nip }}</td>
                                <td class="border uppercase">{{ $pegawai->jabatan }}</td>
                                <td class="border">
                                    @if($pegawai->tim == null)
                                    Tidak ada Tim
                                    @else
                                    <p class="uppercase">{{ $pegawai->tim }}</p>
                                    @endif
                                </td>
                                <td class="border flex gap-2">
                                    <a href="#" onclick="openEditModal(this, event)" data-id="{{ $pegawai->id }}"
                                        data-dropdown-id="dropdown1"
                                        class="px-5 text-blue-900 border border-neutral-300 rounded-sm hover:bg-blue-400 duration-100 py-2 bg-blue-300">
                                        Edit
                                    </a>

                                    @if(auth()->user()->role == 'super') {{-- Hanya role super yang melihat ini --}}
                                    <a href="#"
                                        onclick="event.preventDefault();document.getElementById('deleteForm{{ $pegawai->id }}').submit();"
                                        class="px-5 border text-red-900 border-neutral-300 rounded-sm hover:bg-red-400 duration-100 py-2 bg-red-300">
                                        Remove
                                    </a>
                                    <form action="{{ route('data.destroy', $pegawai->id) }}" method="POST"
                                        id="deleteForm{{ $pegawai->id }}">{{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="text-black">
                        <nav>
                            {{ $karyawan->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dropdown menu / Nambah Pegawai -->
    <form action="{{ route('data.store') }}" method="POST">
        {{ csrf_field() }}
        <div id="dropdown"
            class="z-10 right-5 border border-neutral-300 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md max-w-4xl">
            <div class="bg-white p-5 rounded-lg shadow-md max-w-full w-full">
                <div class="flex flex-row gap-5">
                    <div class="flex flex-col w-60">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">Nama Lengkap
                            Pegawai</label>
                        <input type="text" id="nama" name="nama"
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900">
                    </div>
                    <div class="flex flex-col w-40">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">NIP</label>
                        <input type="number" id="nip" name="nip"
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900">
                    </div>
                    <div class="flex flex-col w-40">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">Jabatan</label>
                        <select
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900"
                            name="jabatan" id="jabatan">
                            <option value="">Pilih Jabatan</option>
                            <option value="kabalmon">Kabalmon</option>
                            <option value="katim">Katim</option>
                            <option value="ppk">PPK</option>
                            <option value="staff">Staff</option>
                            <option value="staff_pelayanan">Staff Pelayanan</option>
                            <option value="security">Security</option>
                            <option value="cs">CS</option>
                            <option value="driver">Driver</option>
                            <option value="magang">Magang</option>
                        </select>
                    </div>
                    <div class="flex flex-col w-40">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">Tim
                            Kerja</label>
                        <select
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900"
                            name="tim" id="tim_tambah">
                            <option value="">Pilih Tim</option>
                            <option value="monev">Monev</option>
                            <option value="penerbitan">Penerbitan</option>
                            <option value="pkip">PKIP</option>
                            <option value="tu">TU</option>
                        </select>
                    </div>
                </div>
                <!-- button submit form -->
                <button id="submit-btn" type="submit"
                    class="w-full bg-[#198754] text-white duration-150 py-3 rounded-md mt-3 hover:bg-green-600">Tambahkan
                    Data
                </button>
            </div>
        </div>
    </form>

    <!-- In the "Edit Pegawai" form (dropdown1) -->
    <div id="dropdown1"
        class="z-10 right-5 border border-neutral-300 max-w-4xl hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md">
        <div class="bg-white p-5 rounded-lg shadow-md max-w-4xl">
            <form id="editForm" action="{{ route('data.update', ['id' => 1]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <div class="flex flex-row gap-5">
                    <input type="hidden" id="pegawai_id" name="id">
                    <div class="flex flex-col w-60">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">Nama Lengkap
                            Pegawai</label>
                        <input type="text" id="nama_edit" name="nama"
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900">
                    </div>
                    <div class="flex flex-col w-40">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">NIP</label>
                        <input type="number" id="nip_edit" name="nip"
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900">
                    </div>
                    <div class="flex flex-col w-40">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">Jabatan</label>
                        <select
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900"
                            name="jabatan" id="jabatan_edit">
                            <option value="">Pilih Jabatan</option>
                            <option value="kabalmon">Kabalmon</option>
                            <option value="katim">Katim</option>
                            <option value="ppk">PPK</option>
                            <option value="staff">Staff</option>
                            <option value="staff_pelayanan">Staff Pelayanan</option>
                            <option value="security">Security</option>
                            <option value="cs">CS</option>
                            <option value="driver">Driver</option>
                            <option value="magang">Magang</option>
                        </select>
                    </div>
                    <div class="flex flex-col w-40">
                        <label class="block mb-2 font-medium text-slate-500" for="nama">Tim
                            Kerja</label>
                        <select
                            class="w-full p-2 border rounded-md mb-4 focus:outline-none focus:ring-1 focus:ring-blue-900 text-neutral-900"
                            name="tim" id="tim_edit">
                            <option value="">Pilih Tim</option>
                            <option value="monev">Monev</option>
                            <option value="penerbitan">Penerbitan</option>
                            <option value="pkip">PKIP</option>
                            <option value="tu">TU</option>
                        </select>
                    </div>
                </div>

                <!-- Face Detection Section for Edit Form -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-slate-500">Data Wajah</label>
                    <p class="text-sm text-gray-500 mb-2">Perbaharui data wajah pegawai untuk rekognisi</p>
                </div>

                <div class="relative w-full mb-4">
                    <video id="video1" class="w-full rounded-md shadow-md hidden" style="transform: scale(1);"></video>
                    <canvas id="canvas1" class="hidden w-full rounded-md shadow-md"></canvas>
                </div>

                <!-- Hidden input for face descriptor in edit form -->
                <input type="hidden" id="face_descriptor_edit" name="face_descriptor">

                <p id="register-edit"
                    class="cursor-pointer text-center w-full bg-[#252C58] text-white duration-150 py-3 rounded-md hover:bg-blue-800">
                    Buka Kamera
                </p>
                <p id="capture-edit"
                    class="cursor-pointer text-center w-full bg-[#D6A628] text-white duration-150 py-3 rounded-md mt-3 hover:bg-yellow-600">
                    Scan
                    Wajah ( Ambil Gambar )
                </p>

                <!-- tombol submit -->
                <button type="submit"
                    class="w-full bg-[#198754] text-white duration-150 py-3 rounded-md mt-3 hover:bg-green-600">Simpan
                    Data</button>
            </form>
        </div>
    </div>




    <script src="{{ asset('js/datapegawai.js') }}"></script>
    <script src="{{ asset('js/tambahpegawai.js') }}"></script>
    <script src="{{ asset('js/editpegawai.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // ✅ Fungsi untuk menampilkan modal edit di dekat tombol yang diklik
        function openEditModal(element, event) {
            event.preventDefault(); // Mencegah reload halaman

            var id = $(element).data('id'); // Ambil ID dari atribut data-id
            var dropdown = $('#dropdown1'); // Ambil dropdown yang sesuai
            var buttonOffset = $(element).offset(); // Posisi tombol
            var dropdownWidth = dropdown.outerWidth(); // Lebar dropdown
            var windowWidth = $(window).width(); // Lebar jendela browser

            // AJAX untuk memuat data pegawai
            $.ajax({
                url: '/data/' + id,
                type: 'GET',
                success: function (data) {
                    // Masukkan data ke form
                    $('#pegawai_id').val(data.id);
                    $('#nama_edit').val(data.nama);
                    $('#nip_edit').val(data.nip);
                    $('#jabatan_edit').val(data.jabatan);
                    $('#tim_edit').val(data.tim);

                    // Posisi default (di kiri bawah tombol)
                    var leftPosition = buttonOffset.left;

                    // ✅ Cek apakah dropdown melebihi layar di kanan
                    if (leftPosition + dropdownWidth > windowWidth) {
                        leftPosition = windowWidth - dropdownWidth -
                            10; // Geser ke kiri (dengan padding 1px)
                    }

                    // ✅ Set posisi dropdown
                    dropdown.css({
                        position: 'absolute',
                        top: buttonOffset.top + $(element).outerHeight() + 5 +
                            'px', // 5px di bawah tombol
                        left: leftPosition + 'px'
                    });

                    // Tampilkan dropdown
                    dropdown.removeClass('hidden');
                },
                error: function () {
                    alert('Gagal memuat data. Silakan coba lagi.');
                }
            });
        }


        // ✅ Tutup dropdown jika klik di luar area dropdown
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#dropdown1, a[data-dropdown-id="dropdown1"]').length) {
                $('#dropdown1').addClass('hidden');
            }
        });

        // ✅ Submit form menggunakan AJAX (EDIT)
        $('#editForm').on('submit', function (e) {
            e.preventDefault();

            var id = $('#pegawai_id').val();
            var formData = $(this).serialize();

            $.ajax({
                url: '/data/' + id,
                type: 'PUT',
                data: formData,
                success: function (response) {
                    alert(response.success);
                    $('#dropdown1').addClass('hidden');
                    location.reload(); // Reload halaman setelah edit
                },
                error: function () {
                    alert('Terjadi kesalahan saat menyimpan. Silakan coba lagi.');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Function to toggle security shift container based on jabatan selection
            function toggleSecurityShift() {
                const jabatanDropdown = document.getElementById('jabatan');
                const securityShiftContainer = document.getElementById('security-shift-container');

                if (jabatanDropdown && securityShiftContainer) {
                    jabatanDropdown.addEventListener('change', function () {
                        if (this.value === 'security') {
                            securityShiftContainer.classList.remove('hidden');
                        } else {
                            securityShiftContainer.classList.add('hidden');
                        }
                    });
                }

                // For the edit form
                const jabatanEditDropdown = document.getElementById('jabatan_edit');
                const securityShiftEditContainer = document.getElementById('security-shift-edit-container');

                if (jabatanEditDropdown && securityShiftEditContainer) {
                    jabatanEditDropdown.addEventListener('change', function () {
                        if (this.value === 'security') {
                            securityShiftEditContainer.classList.remove('hidden');
                        } else {
                            securityShiftEditContainer.classList.add('hidden');
                        }
                    });
                }
            }

            // Initialize the security shift toggle
            toggleSecurityShift();

            // Handle the edit form when loaded via AJAX
            if (window.jQuery) {
                $(document).on('click', 'a[data-dropdown-id="dropdown1"]', function () {
                    const id = $(this).data('id');

                    // Wait briefly for the AJAX to complete and form to populate
                    setTimeout(function () {
                        const jabatanEditValue = $('#jabatan_edit').val();
                        if (jabatanEditValue === 'security') {
                            $('#security-shift-edit-container').removeClass('hidden');

                            // Get the security shift value via AJAX
                            $.ajax({
                                url: '/get-security-shift/' + id,
                                type: 'GET',
                                success: function (data) {
                                    if (data.shift) {
                                        $('#security_shift_edit').val(data.shift);
                                    }
                                }
                            });
                        } else {
                            $('#security-shift-edit-container').addClass('hidden');
                        }
                    }, 300);
                });
            }
        });
    </script>


</body>

</html>