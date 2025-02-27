<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen Dummy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-lg">
            <!-- {{-- ✅ Judul Halaman --}} -->
            <h1 class="text-center text-2xl font-bold text-indigo-600 sm:text-3xl">Welcome</h1>
            <p class="mx-auto mt-4 max-w-md text-center text-gray-500">
                Halaman Dummy untuk Tes Kehadiran
            </p>

            <!-- {{-- ✅ Notifikasi Sukses --}} -->
            @if(session('success'))
                <div class="bg-green-300 p-3 mb-4 rounded-lg text-green-900 text-center">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <!-- {{-- ❗ Notifikasi Error --}} -->
            @if(session('error'))
                <div class="bg-red-300 p-3 mb-4 rounded-lg text-red-700 text-center">
                    <strong>{{ session('error') }}</strong>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-300 p-3 mb-4 rounded-lg text-yellow-800 text-center">
                    <strong>{{ session('warning') }}</strong>
                </div>
            @endif

            <!-- {{-- ✅ Form Absen --}} -->
            <form action="{{ route('absen.store') }}" method="POST" 
                class="mt-6 mb-0 space-y-4 rounded-lg p-4 shadow-lg bg-white sm:p-6 lg:p-8">
                
                <!-- {{-- CSRF Token --}} -->
                {{ csrf_field() }}

                <!-- {{-- Input Nama --}} -->
                <div>
                    <label for="nama" class="block text-gray-700 font-medium mb-2">Nama</label>
                    <div class="relative">
                        <input type="text" name="nama" id="nama" required
                            class="w-full rounded-lg border border-gray-300 p-4 pe-12 text-sm shadow-sm"
                            placeholder="Masukkan Nama" />
                    </div>
                </div>

                <!-- {{-- Input NIP --}} -->
                <div>
                    <label for="nip" class="block text-gray-700 font-medium mb-2">NIP</label>
                    <div class="relative">
                        <input type="number" name="nip" id="nip" required
                            class="w-full rounded-lg border border-gray-300 p-4 pe-12 text-sm shadow-sm"
                            placeholder="Masukkan NIP" />
                    </div>
                </div>

                <!-- {{-- ✅ Hidden Input untuk Jenis Absen (datang/pulang) --}} -->
                <input type="hidden" name="absen_type" id="absen_type" value="datang">

                <!-- {{-- ✅ Tombol Submit --}} -->
                <div class="flex items-center justify-between space-x-2">
                    <button type="submit" onclick="setAbsenType('datang')" 
                        class="w-1/2 rounded-lg bg-green-500 hover:bg-green-600 px-5 py-3 text-sm font-medium text-white">
                        Absen Datang
                    </button>

                    <button type="submit" onclick="setAbsenType('pulang')" 
                        class="w-1/2 rounded-lg bg-blue-500 hover:bg-blue-600 px-5 py-3 text-sm font-medium text-white">
                        Absen Pulang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- {{-- ✅ Script untuk Mengatur Jenis Absen --}} -->
    <script>
        function setAbsenType(type) {
            document.getElementById('absen_type').value = type;
        }
    </script>
</body>

</html>
