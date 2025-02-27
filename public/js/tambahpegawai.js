// Ambil elemen dari HTML
const video = document.getElementById("video");
const video1 = document.getElementById("video1");
const canvas = document.getElementById("canvas");
const canvas1 = document.getElementById("canvas1");
const registerBtn = document.getElementById("register-btn");
const registerEdit = document.getElementById("register-edit");
const captureBtn = document.getElementById("capture-btn");
const captureEdit = document.getElementById("capture-edit");
const resetBtn = document.getElementById("reset-btn");
let stream = null; // Untuk menyimpan stream kamera

// Fungsi untuk menyalakan kamera
function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(mediaStream => {
            stream = mediaStream;
            video.srcObject = mediaStream;
            video.play();
            video.classList.remove("hidden");
            registerBtn.innerText = "Matikan Kamera";
            registerBtn.classList.remove("bg-blue-500");
            registerBtn.classList.add("bg-red-500");

            registerEdit.innerText = "Matikan Kamera";
            registerEdit.classList.remove("bg-blue-500");
            registerEdit.classList.add("bg-red-500");
        })
        .catch(error => console.error("Tidak bisa mengakses kamera:", error));
}

// Fungsi untuk mematikan kamera
function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        video.srcObject = null;
        video.classList.add("hidden");
        stream = null;
        registerBtn.innerText = "Daftarkan Muka";
        registerBtn.classList.remove("bg-red-500");
        registerBtn.classList.add("bg-blue-500");

        registerEdit.innerText = "Daftarkan Muka";
        registerEdit.classList.remove("bg-red-500");
        registerEdit.classList.add("bg-blue-500");
    }
}

// Saat tombol "Daftarkan Muka" diklik, kamera menyala/mati
registerBtn.addEventListener("click", () => {
    if (stream) {
        stopCamera();
    } else {
        startCamera();
    }
});

registerEdit.addEventListener("click", () => {
    if (stream) {
        stopCamera();
    } else {
        startCamera();
    }
});

// Saat tab tidak aktif, kamera otomatis mati
document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        stopCamera();
    }
});

// Fungsi untuk menangkap wajah dari video
captureBtn.addEventListener("click", () => {
    if (!stream) {
        alert("Aktifkan kamera terlebih dahulu!");
        return;
    }

    const context = canvas.getContext("2d");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Simpan status canvas saat ini
    context.save(); 

    // Terapkan transformasi untuk membalikkan gambar secara horizontal
    context.scale(-1, 1);  
    // Gambarkan gambar dengan posisi x yang telah disesuaikan agar tetap pas
    context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height); 

    // Kembalikan status canvas ke keadaan semula
    context.restore(); 

    // Tampilkan hasil tangkapan di halaman
    canvas.classList.remove("hidden");

    // Ubah tombol setelah menangkap gambar
    captureBtn.innerText = "Wajah Terdeteksi ✅";
    captureBtn.classList.add("bg-yellow-500");

    // Tampilkan tombol "Ambil Gambar Ulang"
    resetBtn.classList.remove("hidden");
    captureBtn.classList.add("hidden");
});

captureEdit.addEventListener("click", () => {
    if (!stream) {
        alert("Aktifkan kamera terlebih dahulu!");
        return;
    }

    const context = canvas.getContext("2d");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Simpan status canvas saat ini
    context.save(); 

    // Terapkan transformasi untuk membalikkan gambar secara horizontal
    context.scale(-1, 1);  
    // Gambarkan gambar dengan posisi x yang telah disesuaikan agar tetap pas
    context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height); 

    // Kembalikan status canvas ke keadaan semula
    context.restore(); 

    // Tampilkan hasil tangkapan di halaman
    canvas.classList.remove("hidden");

    // Ubah tombol setelah menangkap gambar
    captureEdit.innerText = "Wajah Terdeteksi ✅";
    captureEdit.classList.add("bg-yellow-500");

    // Tampilkan tombol "Ambil Gambar Ulang"
    resetBtn.classList.remove("hidden");
    captureEdit.classList.add("hidden");
});

// Fungsi untuk mereset gambar dan tombol untuk mengambil gambar ulang
resetBtn.addEventListener("click", () => {
    // Resetkan elemen-elemen
    canvas.classList.add("hidden");
    captureBtn.innerText = "Scan Wajah";
    captureBtn.classList.remove("bg-yellow-500");
    captureBtn.classList.remove("hidden");
    resetBtn.classList.add("hidden");
});
