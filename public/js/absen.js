// ===== Face Recognition for Attendance =====
let faceRecognitionActive = false;
let knownFaces = [];
let currentUser = null;
let attendanceType = null; // 'datang' or 'pulang'
let recognizedEmployees = new Set(); // Track employees who have already been greeted

// Initialize the system
document.addEventListener('DOMContentLoaded', async function() {
    // Initialize face-api
    try {
        await initFaceApi();
        console.log('Face API initialized successfully');
        
        // Fetch known faces from the server
        await fetchKnownFaces();
        
        // Start the video with face detection
        await startVideo();
        
        // Setup attendance buttons after video is set up
        setupAttendanceButtons();
    } catch (error) {
        console.error('Error initializing face recognition:', error);
        showError('Could not initialize face recognition system. Please refresh and try again.');
    }
    
    // Update datetime display
    updateDateTime();
    setInterval(updateDateTime, 1000);
});

// Fetch all employee face data from the server
async function fetchKnownFaces() {
    try {
        const response = await fetch('/api/faces');
        const data = await response.json();
        
        if (data.success && data.faces) {
            knownFaces = data.faces.filter(face => face.descriptor);
            console.log(`Loaded ${knownFaces.length} known faces`);
        } else {
            console.warn('No faces loaded:', data.message || 'Unknown error');
        }
    } catch (error) {
        console.error('Error fetching known faces:', error);
        showError('Failed to load employee data. Please try again later.');
    }
}

// Start video and face recognition
async function startVideo() {
    console.log('Starting video...');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    
    if (!video || !canvas) {
        console.error('Video or canvas element not found');
        return;
    }
    
    try {
        // Get user's camera stream
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: "user"
            }
        });
        
        video.srcObject = stream;
        
        // Wait for video to be loaded
        video.onloadedmetadata = () => {
            video.play();
            adjustCanvasSize();
            drawFaceGuide();
            
            // Start face recognition
            startFaceRecognition();
        };
        
        // Handle resizing
        window.addEventListener('resize', adjustCanvasSize);
    } catch (error) {
        console.error("Error accessing the camera: ", error);
        showError("Cannot access camera. Please ensure camera is available and permission is granted.");
    }
}

// Adjust canvas size to match video display size
function adjustCanvasSize() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    
    if (!video || !canvas) return;
    
    const videoRect = video.getBoundingClientRect();
    canvas.width = videoRect.width;
    canvas.height = videoRect.height;
}

// Draw face guide oval
function drawFaceGuide() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    
    if (!video || !canvas) return;
    
    // Adjust canvas size
    const videoRect = video.getBoundingClientRect();
    if (canvas.width !== videoRect.width || canvas.height !== videoRect.height) {
        canvas.width = videoRect.width;
        canvas.height = videoRect.height;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Compensate for mirror effect
    ctx.save();
    ctx.setTransform(-1, 0, 0, 1, canvas.width, 0);
    
    // Draw oval face guide
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const faceWidth = canvas.width * 0.40;
    const faceHeight = canvas.height * 0.85;
    
    // Create oval mask
    ctx.save();
    ctx.beginPath();
    ctx.ellipse(centerX, centerY, faceWidth / 2, faceHeight / 2, 0, 0, Math.PI * 2);
    ctx.clip();
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.restore();
    
    // Draw dashed oval outline
    ctx.strokeStyle = 'white';
    ctx.lineWidth = 2;
    ctx.setLineDash([8, 4]);
    ctx.beginPath();
    ctx.ellipse(centerX, centerY, faceWidth / 2, faceHeight / 2, 0, 0, Math.PI * 2);
    ctx.stroke();
    
    ctx.restore();
    
    // Only continue animation loop if face recognition is not active
    if (!faceRecognitionActive) {
        requestAnimationFrame(drawFaceGuide);
    }
}

// Start face recognition process
function startFaceRecognition() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    
    if (!video || !canvas) return;
    
    faceRecognitionActive = true;
    
    // Create overlay for showing face detection
    const faceOverlay = createFaceOverlay(video, video.parentNode);
    
    // Recognition loop
    async function recognizeFace() {
        if (!faceRecognitionActive) return;
        
        try {
            // Detect face in current video frame
            const detection = await detectFace(video);
            
            if (detection) {
                // Draw face detection on overlay
                drawFaceDetections(faceOverlay, detection, {
                    boxColor: '#00FF00',
                    labelText: 'Wajah Terdeteksi'
                });
                
                // Check if we have enough known faces to attempt recognition
                if (knownFaces.length > 0) {
                    // Find best match among known faces
                    const match = await findBestMatch(detection.descriptor, knownFaces);
                    
                    if (match) {
                        // We found a matching employee!
                        const matchedFace = knownFaces.find(face => face.id === match.id);
                        
                        if (matchedFace) {
                            // Stop recognition loop
                            faceRecognitionActive = false;
                            
                            // Show success message
                            handleSuccessfulRecognition(matchedFace, match.confidence);
                            return;
                        }
                    }
                }
            } else {
                // Clear overlay when no face is detected
                const ctx = faceOverlay.getContext('2d');
                ctx.clearRect(0, 0, faceOverlay.width, faceOverlay.height);
            }
        } catch (error) {
            console.error('Error during face recognition:', error);
        }
        
        // Continue recognition loop
        requestAnimationFrame(recognizeFace);
    }
    
    // Start the recognition loop
    recognizeFace();
}

// Handle successful face recognition
function handleSuccessfulRecognition(employee, confidence) {
    console.log(`Employee recognized: ${employee.nama} (ID: ${employee.id}) with confidence: ${confidence.toFixed(2)}`);
    
    // Set current user
    currentUser = employee;
    
    // Show greeting notification only if this employee hasn't been greeted yet
    if (!recognizedEmployees.has(employee.id)) {
        showBriefGreeting(employee);
        recognizedEmployees.add(employee.id); // Mark this employee as greeted
    }
    
    // If attendance type is already selected, submit attendance
    if (attendanceType) {
        submitAttendance(employee.id, employee.nama, employee.nip, attendanceType);
    } else {
        // Highlight attendance buttons
        highlightAttendanceButtons();
    }
    
    // Continue face recognition after a short delay
    setTimeout(() => {
        faceRecognitionActive = true;
        startFaceRecognition();
    }, 5000);
}

// Show a brief greeting based on time of day
function showBriefGreeting(employee) {
    // Get the current hour to determine greeting
    const currentHour = new Date().getHours();
    let greeting = "Selamat ";
    
    if (currentHour >= 3 && currentHour < 11) {
        greeting += "pagi";
    } else if (currentHour >= 11 && currentHour < 15) {
        greeting += "siang";
    } else if (currentHour >= 15 && currentHour < 19) {
        greeting += "sore";
    } else {
        greeting += "malam";
    }
    
    // Create the greeting element
    const greetingElement = document.createElement('div');
    greetingElement.id = 'greeting-notification';
    greetingElement.className = 'fixed top-5 left-5 bg-blue-900 text-white px-4 py-2 rounded-lg shadow-lg z-50 opacity-0 transition-opacity duration-300';
    greetingElement.style.fontSize = '1rem';
    
    greetingElement.innerHTML = `${greeting}, ${employee.nama}!`;
    
    // Add to page
    document.body.appendChild(greetingElement);
    
    // Fade in
    setTimeout(() => {
        greetingElement.classList.remove('opacity-0');
        greetingElement.classList.add('opacity-100');
    }, 10);
    
    // Fade out and remove after 2 seconds
    setTimeout(() => {
        greetingElement.classList.remove('opacity-100');
        greetingElement.classList.add('opacity-0');
        
        setTimeout(() => {
            greetingElement.remove();
        }, 300);
    }, 2000);
}

// Highlight attendance buttons to draw attention to them
function highlightAttendanceButtons() {
    const buttonsContainer = document.getElementById('attendance-buttons');
    if (buttonsContainer) {
        buttonsContainer.classList.add('animate-pulse');
        setTimeout(() => {
            buttonsContainer.classList.remove('animate-pulse');
        }, 2000);
    }
}

// Setup attendance type buttons (Clock In/Out)
function setupAttendanceButtons() {
    // Create buttons if they don't exist
    const buttonsContainer = document.createElement('div');
    buttonsContainer.id = 'attendance-buttons';
    buttonsContainer.className = 'flex justify-center space-x-4 mt-6';
    
    // Clock In button
    const clockInBtn = document.createElement('button');
    clockInBtn.id = 'clock-in-btn';
    clockInBtn.className = 'px-6 py-3 bg-green-600 text-white font-medium rounded-lg shadow-md hover:bg-green-700 transition duration-200';
    clockInBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Absen Datang
    `;
    
    // Clock Out button
    const clockOutBtn = document.createElement('button');
    clockOutBtn.id = 'clock-out-btn';
    clockOutBtn.className = 'px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition duration-200';
    clockOutBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Absen Pulang
    `;
    
    // Add buttons to container
    buttonsContainer.appendChild(clockInBtn);
    buttonsContainer.appendChild(clockOutBtn);
    
    // Find the video container to place buttons BELOW it
    const videoContainer = document.querySelector('.relative.aspect-video.mb-4');
    
    if (videoContainer) {
        // Insert after the video container
        videoContainer.parentNode.insertBefore(buttonsContainer, videoContainer.nextSibling);
    } else {
        // Fallback if the expected container is not found
        const fallbackContainer = document.querySelector('.h-screen.w-full.flex.items-center.justify-center.flex-col');
        if (fallbackContainer) {
            // Add to the bottom part of the main container
            fallbackContainer.appendChild(buttonsContainer);
        }
    }
    
    // Add event listeners
    clockInBtn.addEventListener('click', () => setAttendanceType('datang'));
    clockOutBtn.addEventListener('click', () => setAttendanceType('pulang'));
}

// Set attendance type and update UI
function setAttendanceType(type) {
    attendanceType = type;
    
    // Update button styles
    const clockInBtn = document.getElementById('clock-in-btn');
    const clockOutBtn = document.getElementById('clock-out-btn');
    
    if (clockInBtn && clockOutBtn) {
        if (type === 'datang') {
            clockInBtn.classList.add('ring-4', 'ring-green-300');
            clockOutBtn.classList.remove('ring-4', 'ring-blue-300');
        } else {
            clockInBtn.classList.remove('ring-4', 'ring-green-300');
            clockOutBtn.classList.add('ring-4', 'ring-blue-300');
        }
    }
    
    // If we already have a recognized user, submit attendance
    if (currentUser) {
        submitAttendance(currentUser.id, currentUser.nama, currentUser.nip, type);
    }
}

// Submit attendance to server
function submitAttendance(employeeId, nama, nip, type) {
    // Create a form to submit attendance
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/absen';
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Add employee name
    const namaInput = document.createElement('input');
    namaInput.type = 'hidden';
    namaInput.name = 'nama';
    namaInput.value = nama;
    form.appendChild(namaInput);
    
    // Add employee NIP
    const nipInput = document.createElement('input');
    nipInput.type = 'hidden';
    nipInput.name = 'nip';
    nipInput.value = nip;
    form.appendChild(nipInput);
    
    // Add attendance type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'absen_type';
    typeInput.value = type;
    form.appendChild(typeInput);
    
    // Add form to document and submit it
    document.body.appendChild(form);
    form.submit();
}

// Show error message
function showError(message) {
    // Create error notification if it doesn't exist
    const errorNotification = document.createElement('div');
    errorNotification.className = 'fixed top-5 right-5 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md transition-opacity duration-300 opacity-0';
    errorNotification.innerHTML = `
        <div class="flex items-center">
            <div class="py-1">
                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                </svg>
            </div>
            <div>
                <p>${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(errorNotification);
    
    // Fade in
    setTimeout(() => {
        errorNotification.classList.remove('opacity-0');
        errorNotification.classList.add('opacity-100');
    }, 10);
    
    // Remove after 5 seconds
    setTimeout(() => {
        errorNotification.classList.remove('opacity-100');
        errorNotification.classList.add('opacity-0');
        setTimeout(() => {
            errorNotification.remove();
        }, 300);
    }, 5000);
}

// Update date and time display
function updateDateTime() {
    const now = new Date();

    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');

    const day = now.getDate().toString().padStart(2, '0');
    const month = now.toLocaleString('en-US', { month: 'long' }); // Month name
    const year = now.getFullYear();

    const timeString = `${hours}:${minutes}:${seconds}`;
    const dateString = `${day} ${month} ${year}`;

    const datetimeElement = document.getElementById("datetime");
    if (datetimeElement) {
        datetimeElement.innerHTML = `${timeString} • ${dateString}`;
    }
}

// Function to close notifications manually
function closeNotification() {
    const notifications = document.querySelectorAll('.notification, #notification');
    notifications.forEach(notification => {
        notification.classList.remove('opacity-100');
        notification.classList.add('opacity-0');
    });
}