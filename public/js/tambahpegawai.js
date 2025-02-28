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
const faceDescriptorInput = document.getElementById("face_descriptor");
const faceDescriptorEditInput = document.getElementById("face_descriptor_edit");
let stream = null; // Untuk menyimpan stream kamera
let faceDetected = false; // Track if a face has been detected
let faceDetectionInterval = null; // For continuous face detection

// Initialize face-api when the page loads
document.addEventListener('DOMContentLoaded', async () => {
    try {
        console.log('Initializing face recognition system...');
        // Load face-api models
        await initFaceApi();
        console.log('Face recognition system initialized successfully');
        
        // Initially disable capture buttons
        if (captureBtn) {
            captureBtn.disabled = true;
            captureBtn.classList.add("opacity-50", "cursor-not-allowed");
        }
        if (captureEdit) {
            captureEdit.disabled = true;
            captureEdit.classList.add("opacity-50", "cursor-not-allowed");
        }
    } catch (error) {
        console.error('Failed to initialize face recognition:', error);
        alert('Gagal menginisialisasi sistem pengenalan wajah. Silakan refresh halaman dan coba lagi.');
    }
});

// Fungsi untuk menyalakan kamera
async function startCamera() {
    try {
        // Stop any existing stream
        if (stream) {
            stopCamera();
        }
        
        console.log('Requesting camera access...');
        
        // Get camera stream
        const mediaStream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: "user"
            }
        });
        
        console.log('Camera access granted');
        stream = mediaStream;
        
        // Set video source and start playing
        if (video) {
            video.srcObject = mediaStream;
            video.onloadedmetadata = () => {
                video.play();
                video.classList.remove("hidden");
                
                // Start face detection on a timer
                startFaceDetection(video, canvas);
            };
            
            if (registerBtn) {
                registerBtn.innerText = "Matikan Kamera";
                registerBtn.classList.remove("bg-blue-500");
                registerBtn.classList.add("bg-red-500");
            }
        }
        
        // Same for edit form video
        if (video1) {
            video1.srcObject = mediaStream;
            video1.onloadedmetadata = () => {
                video1.play();
                video1.classList.remove("hidden");
                
                // Start face detection for edit form
                startFaceDetection(video1, canvas1);
            };
            
            if (registerEdit) {
                registerEdit.innerText = "Matikan Kamera";
                registerEdit.classList.remove("bg-blue-500");
                registerEdit.classList.add("bg-red-500");
            }
        }
    } catch (error) {
        console.error("Tidak bisa mengakses kamera:", error);
        alert("Tidak dapat mengakses kamera. Pastikan kamera diizinkan dan berfungsi dengan baik.");
    }
}

// Start continuous face detection on video
function startFaceDetection(videoElement, canvasElement) {
    if (!videoElement) {
        console.error('Video element is null or undefined');
        return;
    }
    
    // Clear any existing interval
    if (faceDetectionInterval) {
        clearInterval(faceDetectionInterval);
    }
    
    // Create overlay for face detection visualization
    const faceOverlay = createFaceOverlay(videoElement, videoElement.parentNode);
    
    // Run face detection every 500ms
    faceDetectionInterval = setInterval(async () => {
        if (!stream || !stream.active) {
            clearInterval(faceDetectionInterval);
            return;
        }
        
        try {
            const detection = await detectFace(videoElement);
            
            if (detection) {
                console.log('Face detected in video');
                faceDetected = true;
                
                // Draw face detection on overlay
                drawFaceDetections(faceOverlay, detection, {
                    labelText: 'Wajah Terdeteksi',
                    boxColor: '#00FF00'
                });
                
                // Enable capture button when face is detected
                if (captureBtn && videoElement === video) {
                    captureBtn.disabled = false;
                    captureBtn.classList.remove("opacity-50", "cursor-not-allowed");
                }
                if (captureEdit && videoElement === video1) {
                    captureEdit.disabled = false;
                    captureEdit.classList.remove("opacity-50", "cursor-not-allowed");
                }
            } else {
                // Clear canvas when no face is detected
                const ctx = faceOverlay.getContext('2d');
                ctx.clearRect(0, 0, faceOverlay.width, faceOverlay.height);
                
                // Disable capture button when no face is detected
                if (captureBtn && videoElement === video) {
                    captureBtn.disabled = true;
                    captureBtn.classList.add("opacity-50", "cursor-not-allowed");
                }
                if (captureEdit && videoElement === video1) {
                    captureEdit.disabled = true;
                    captureEdit.classList.add("opacity-50", "cursor-not-allowed");
                }
                
                faceDetected = false;
            }
        } catch (error) {
            console.error("Error during face detection:", error);
        }
    }, 500);
}

// Fungsi untuk mematikan kamera
function stopCamera() {
    // Clear face detection interval
    if (faceDetectionInterval) {
        clearInterval(faceDetectionInterval);
        faceDetectionInterval = null;
    }
    
    // Stop all tracks in the stream
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        
        if (video) {
            video.srcObject = null;
            video.classList.add("hidden");
        }
        
        if (video1) {
            video1.srcObject = null;
            video1.classList.add("hidden");
        }
        
        stream = null;
        faceDetected = false;
        
        // Reset buttons
        if (registerBtn) {
            registerBtn.innerText = "Daftarkan Muka";
            registerBtn.classList.remove("bg-red-500");
            registerBtn.classList.add("bg-blue-500");
        }
        
        if (registerEdit) {
            registerEdit.innerText = "Daftarkan Muka";
            registerEdit.classList.remove("bg-red-500");
            registerEdit.classList.add("bg-blue-500");
        }
        
        // Remove face overlay canvases
        document.querySelectorAll('.face-overlay').forEach(el => el.remove());
    }
}

// Camera toggle event listeners
if (registerBtn) {
    registerBtn.addEventListener("click", () => {
        if (stream) {
            stopCamera();
        } else {
            startCamera();
        }
    });
}

if (registerEdit) {
    registerEdit.addEventListener("click", () => {
        if (stream) {
            stopCamera();
        } else {
            startCamera();
        }
    });
}

// When tab/page is not active, stop the camera
document.addEventListener("visibilitychange", () => {
    if (document.hidden && stream) {
        stopCamera();
    }
});

// Capture face function
async function captureFace(videoElement, canvasElement, faceDescriptorInputElement) {
    if (!stream) {
        alert("Aktifkan kamera terlebih dahulu!");
        return;
    }
    
    if (!faceDetected) {
        alert("Pastikan wajah terdeteksi terlebih dahulu!");
        return;
    }

    // Draw the video frame to canvas
    const context = canvasElement.getContext("2d");
    canvasElement.width = videoElement.videoWidth;
    canvasElement.height = videoElement.videoHeight;

    // Mirror the image horizontally (for selfie view)
    context.save();
    context.scale(-1, 1);
    context.drawImage(videoElement, -canvasElement.width, 0, canvasElement.width, canvasElement.height);
    context.restore();

    // Show the canvas with the captured image
    canvasElement.classList.remove("hidden");

    try {
        console.log('Processing captured face image...');
        // Process the captured image for face recognition
        const detection = await detectFace(canvasElement);
        
        if (!detection) {
            alert("Tidak dapat mendeteksi wajah pada gambar yang diambil. Silakan coba lagi.");
            canvasElement.classList.add("hidden");
            return;
        }
        
        console.log('Face detected in captured image');
        
        // Get face descriptor and store it in the hidden input field
        const faceDescriptor = detection.descriptor;
        const descriptorString = descriptorToString(faceDescriptor);
        
        if (faceDescriptorInputElement) {
            faceDescriptorInputElement.value = descriptorString;
            console.log('Face descriptor saved to input field');
        }
        
        return detection;
    } catch (error) {
        console.error("Error processing face:", error);
        alert("Terjadi kesalahan saat memproses wajah. Silakan coba lagi.");
        return null;
    }
}

// Capture buttons event listeners
if (captureBtn) {
    captureBtn.addEventListener("click", async () => {
        const detection = await captureFace(video, canvas, faceDescriptorInput);
        
        if (detection) {
            // Update button state
            captureBtn.innerText = "Wajah Terdeteksi ✅";
            captureBtn.classList.add("bg-green-500");
            captureBtn.classList.remove("bg-yellow-500");
            
            // Show reset button if it exists
            if (resetBtn) {
                resetBtn.classList.remove("hidden");
                captureBtn.classList.add("hidden");
            }
        }
    });
}

if (captureEdit) {
    captureEdit.addEventListener("click", async () => {
        const detection = await captureFace(video1, canvas1, faceDescriptorEditInput);
        
        if (detection) {
            // Update button state
            captureEdit.innerText = "Wajah Terdeteksi ✅";
            captureEdit.classList.add("bg-green-500");
            captureEdit.classList.remove("bg-yellow-500");
        }
    });
}

// Reset button functionality
if (resetBtn) {
    resetBtn.addEventListener("click", () => {
        // Reset elements
        canvas.classList.add("hidden");
        if (faceDescriptorInput) {
            faceDescriptorInput.value = '';
        }
        
        // Reset capture button
        captureBtn.innerText = "Scan Wajah";
        captureBtn.classList.remove("bg-green-500");
        captureBtn.classList.add("bg-yellow-500");
        captureBtn.classList.remove("hidden");
        
        // Hide reset button
        resetBtn.classList.add("hidden");
    });
}