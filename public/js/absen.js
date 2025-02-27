async function startVideo() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
        video.srcObject = stream;

        video.onloadedmetadata = () => {
            video.play();
            adjustCanvasSize();
            drawFaceGuide();
        };

        window.addEventListener('resize', adjustCanvasSize);
    } catch (error) {
        console.error("Error accessing the camera: ", error);
        alert("Tidak dapat mengakses kamera. Pastikan kamera tersedia dan izin diberikan.");
    }

    function adjustCanvasSize() {
        const videoRect = video.getBoundingClientRect();
        canvas.width = videoRect.width;
        canvas.height = videoRect.height;
    }
}

function drawFaceGuide() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    // Menyesuaikan ukuran canvas
    const videoRect = video.getBoundingClientRect();
    if (canvas.width !== videoRect.width || canvas.height !== videoRect.height) {
        canvas.width = videoRect.width;
        canvas.height = videoRect.height;
    }

    // Bersihkan canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Kompensasi untuk transformasi scale-x-[-1] pada video
    ctx.save();
    ctx.setTransform(-1, 0, 0, 1, canvas.width, 0);


    // Ambil tengah canvas
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    
    // Ukuran oval dalam format portrait
    const faceWidth = canvas.width * 0.40;  // Lebih kecil dari sebelumnya
    const faceHeight = canvas.height * 0.85; // Tetap tinggi
    
    // Membuat oval sebagai mask
    ctx.save();
    ctx.beginPath();
    ctx.ellipse(centerX, centerY, faceWidth / 2, faceHeight / 2, 0, 0, Math.PI * 2);
    ctx.clip();
    ctx.clearRect(0, 0, canvas.width, canvas.height); // Buat area oval transparan
    ctx.restore();

    // Gambar garis putus-putus untuk oval
    ctx.strokeStyle = 'white';
    ctx.lineWidth = 2;
    ctx.setLineDash([8, 4]); // Membuat garis putus-putus
    ctx.beginPath();
    ctx.ellipse(centerX, centerY, faceWidth / 2, faceHeight / 2, 0, 0, Math.PI * 2);
    ctx.stroke();

    ctx.restore(); // Kembalikan transformasi

    requestAnimationFrame(drawFaceGuide);
}

// Jalankan fungsi saat halaman dimuat
window.onload = startVideo;

function closeNotification() {
    document.getElementById('notification').style.display = 'none';
}


let selectedSeat = null;
const bookedSeats = new Set();
const MAX_SEATS = 10;

// Modified seat-related code to handle missing elements
document.addEventListener('DOMContentLoaded', function() {
    // Elements - with null checks to prevent errors if elements don't exist
    const modal = document.getElementById('seatModal');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const seatContainer = document.getElementById('seatContainer');
    const seatCount = document.getElementById('seatCount');
    const maxSeatsWarning = document.getElementById('maxSeatsWarning');
    
    // Check if seat-related elements exist
    if (!modal || !seatContainer) {
        console.log("Seat modal system is not present or has been removed");
        return; // Exit early if required elements don't exist
    }

    // Update seat count display function with null check
    function updateSeatCount() {
        if (seatCount) {
            seatCount.textContent = bookedSeats.size;
        }
    }

    // Generate seats
    function generateSeats() {
        if (!seatContainer) return;
        
        seatContainer.innerHTML = '';
        for (let i = 1; i <= 50; i++) {
            const seatBtn = document.createElement('button');
            seatBtn.className = `
                p-2 rounded-lg relative flex items-center justify-center
                ${bookedSeats.has(i) ? 'bg-gray-200 cursor-not-allowed' : 'bg-white hover:bg-gray-100'}
                border border-gray-200 transition-colors duration-200
            `;
            seatBtn.textContent = i;

            if (bookedSeats.has(i)) {
                const checkmark = document.createElement('div');
                checkmark.innerHTML = `
                    <svg class="w-4 h-4 absolute text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                seatBtn.appendChild(checkmark);
                seatBtn.disabled = true;
            } else {
                seatBtn.onclick = () => selectSeat(i, seatBtn);
            }

            seatContainer.appendChild(seatBtn);
        }
    }

    // Seat selection
    function selectSeat(seatNumber, button) {
        if (!seatContainer) return;
        
        const previousSelection = seatContainer.querySelector('.bg-green-500');
        if (previousSelection) {
            previousSelection.classList.remove('bg-green-500', 'text-white');
            previousSelection.classList.add('bg-white');
        }

        selectedSeat = seatNumber;
        button.classList.remove('bg-white');
        button.classList.add('bg-green-500', 'text-white', 'transform', 'scale-105');
        
        if (confirmBtn) {
            confirmBtn.disabled = false;
        }
    }

    // Modal handlers - removed openModal function
    function closeModal() {
        if (!modal) return;
        
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        selectedSeat = null;
    }

    function confirmSelection() {
        if (!selectedSeat) return;
        
        if (bookedSeats.size < MAX_SEATS) {
            bookedSeats.add(selectedSeat);
            updateSeatCount();
            closeModal();
        } else if (maxSeatsWarning) {
            maxSeatsWarning.classList.remove('hidden');
        }
    }

    // Event listeners with null checks
    if (closeModalBtn) {
        closeModalBtn.onclick = closeModal;
    }
    
    if (cancelBtn) {
        cancelBtn.onclick = closeModal;
    }
    
    if (confirmBtn) {
        confirmBtn.onclick = confirmSelection;
    }

    // Close modal when clicking outside
    if (modal) {
        modal.onclick = (e) => {
            if (e.target === modal) {
                closeModal();
            }
        };
    }

    // Initial setup only if necessary elements exist
    if (seatContainer) {
        generateSeats();
    }
    
    if (seatCount) {
        updateSeatCount();
    }
});

function updateDateTime() {
    const now = new Date();

    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');

    const day = now.getDate().toString().padStart(2, '0');
    const month = now.toLocaleString('en-US', { month: 'long' }); // Nama bulan
    const year = now.getFullYear();

    const timeString = `${hours}:${minutes}:${seconds}`;
    const dateString = `${day} ${month} ${year}`;

    const datetimeElement = document.getElementById("datetime");
    if (datetimeElement) {
        datetimeElement.innerHTML = `${timeString} • ${dateString}`;
    }
}

setInterval(updateDateTime, 1000); // Update setiap 1 detik
updateDateTime(); // Jalankan saat halaman pertama kali dimuat

function showTableNotification() {
    const toast = document.getElementById('toast-default');
    if (!toast) return;
    
    // Show the notification
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');
    
    // Hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove('opacity-100');
        toast.classList.add('opacity-0');
    }, 3000);
}

// Function to close the attendance notification manually
function closeNotification() {
    const notification = document.getElementById('notification');
    if (!notification) return;
    
    notification.classList.remove('opacity-100');
    notification.classList.add('opacity-0');
}

// Function to show and auto-hide the attendance notification
function showAttendanceNotification() {
    const notification = document.getElementById('notification');
    if (!notification) return;
    
    // Show the notification
    notification.classList.remove('opacity-0');
    notification.classList.add('opacity-100');
    
    // Hide after 3 seconds
    setTimeout(() => {
        notification.classList.remove('opacity-100');
        notification.classList.add('opacity-0');
    }, 3000);
}

// Show notifications when the page loads
document.addEventListener('DOMContentLoaded', function() {
    showTableNotification();
    showAttendanceNotification();
});