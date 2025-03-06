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
    
    // Show table notification
});

// Add function to check if face is "live" based on confidence threshold
function isLiveFace(confidence) {
    const LIVENESS_THRESHOLD = 0.62; // High threshold for better security
    return confidence >= LIVENESS_THRESHOLD;
}

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

        // mirror video
        video.style.transform = 'scale(1)';
        
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
                // If we have known faces, try to recognize
                let confidence = 0;
                let matchedFace = null;
                
                if (knownFaces.length > 0) {
                    // Find best match among known faces
                    const match = await findBestMatch(detection.descriptor, knownFaces);
                    
                    if (match) {
                        matchedFace = knownFaces.find(face => face.id === match.id);
                        confidence = match.confidence;
                    }
                }
                
                // Always draw the face detection with confidence
                drawFaceDetections(faceOverlay, detection, {
                    boxColor: confidence > 0 ? (isLiveFace(confidence) ? '#00FF00' : '#FF0000') : '#FFFF00',
                    labelText: confidence > 0 ? 'Wajah Terdeteksi' : 'Mencari Wajah...',
                    confidence: confidence
                });
                
                // If we found a match and face recognition is active, handle it
                if (matchedFace && faceRecognitionActive) {
                    // Stop face recognition loop temporarily
                    faceRecognitionActive = false;
                    
                    // Process the recognized face
                    handleSuccessfulRecognition(matchedFace, confidence);
                    return;
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
    
    // Check if the face meets our liveness criteria
    const isLive = isLiveFace(confidence);
    
    if (!isLive) {
        // Face detected but confidence too low - show warning
        showLivenessWarning(confidence);
        
        // Continue face recognition after a short delay
        setTimeout(() => {
            faceRecognitionActive = true;
            startFaceRecognition();
        }, 2000);
        
        return; // Stop processing - don't set currentUser or allow attendance
    }
    
    // If we get here, the face passed the liveness check
    
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

// Function to show liveness warning
function showLivenessWarning(confidence) {
    // Create warning element
    const warningElement = document.createElement('div');
    warningElement.id = 'liveness-warning';
    warningElement.className = 'fixed top-5 left-5  bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 opacity-0 transition-opacity duration-300';
    warningElement.style.fontSize = '1rem';
    
    warningElement.innerHTML = `
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Verifikasi gagal (${(confidence * 100).toFixed(0)}% < 92%)</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(warningElement);
    
    // Fade in
    setTimeout(() => {
        warningElement.classList.remove('opacity-0');
        warningElement.classList.add('opacity-100');
    }, 10);
    
    // Fade out and remove after 2 seconds
    setTimeout(() => {
        warningElement.classList.remove('opacity-100');
        warningElement.classList.add('opacity-0');
        
        setTimeout(() => {
            warningElement.remove();
        }, 300);
    }, 2000);
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
    
    // Create the greeting element - positioned in top-left
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
    // Create buttons container - make it full width with vertical layout
    const buttonsContainer = document.createElement('div');
    buttonsContainer.id = 'attendance-buttons';
    // Change from space-x-4 (horizontal spacing) to space-y-4 (vertical spacing)
    // Remove flex to stack buttons vertically
    buttonsContainer.className = 'w-full flex items-center justify-center gap-3';
    
    // Clock In button
    const clockInBtn = document.createElement('button');
    clockInBtn.id = 'clock-in-btn';
    // Make button wider
    clockInBtn.className = 'px-6 py-3 w-64 bg-green-600 text-white font-medium rounded-lg shadow-md hover:bg-green-700 transition duration-200';
    clockInBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Absen Datang
    `;
    
    // Clock Out button
    const clockOutBtn = document.createElement('button');
    clockOutBtn.id = 'clock-out-btn';
    // Make button wider
    clockOutBtn.className = 'px-6 py-3 w-64 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition duration-200';
    clockOutBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Absen Pulang
    `;
    
    // Add buttons to container
    buttonsContainer.appendChild(clockInBtn);
    buttonsContainer.appendChild(clockOutBtn);
    
    // Find where to insert the buttons - specifically AFTER the camera container
    const cameraContainer = document.querySelector('.my-6.w-full.items-center.flex.justify-center');
    
    if (cameraContainer) {
        // Insert after the camera container
        cameraContainer.parentNode.insertBefore(buttonsContainer, cameraContainer.nextSibling);
    } else {
        // Fallback options if the main selector fails
        const mainContainer = document.querySelector('.h-screen.w-full.flex.items-center.justify-center.flex-col');
        if (mainContainer) {
            // Find the video element to insert after
            const videoElement = document.querySelector('#video')?.closest('.relative');
            if (videoElement) {
                mainContainer.insertBefore(buttonsContainer, videoElement.nextSibling);
            } else {
                // Last resort - just append to the main container
                mainContainer.appendChild(buttonsContainer);
            }
        }
    }
    
    // Add event listeners
    clockInBtn.addEventListener('click', () => setAttendanceType('datang'));
    clockOutBtn.addEventListener('click', () => {
        // Check if employee has already clocked in before allowing clock out
        checkCanClockOut();
    });
}

// Check if employee can clock out
async function checkCanClockOut() {
    if (!currentUser) {
        // If no user is recognized yet, show error
        showError("Mohon tunggu sampai wajah terdeteksi");
        return;
    }
    
    try {
        // Check if employee has already clocked in today
        const response = await fetch(`/check-attendance?nama=${encodeURIComponent(currentUser.nama)}&nip=${encodeURIComponent(currentUser.nip)}`);
        const data = await response.json();
        
        if (data.can_clock_out) {
            // If they can clock out, proceed
            setAttendanceType('pulang');
        } else {
            // Show error message
            showError("Anda belum absen datang hari ini. Silakan absen datang terlebih dahulu.");
        }
    } catch (error) {
        console.error("Error checking attendance status:", error);
        // Fall back to server-side validation
        setAttendanceType('pulang');
    }
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

// Create a face overlay canvas to show detection results
function createFaceOverlay(videoElement, parentElement) {
    // Create a canvas element
    // const canvas = document.createElement('canvas');
    // canvas.className = 'face-overlay absolute top-0 left-0 w-full h-full';
    
    // Do NOT mirror the canvas with CSS - we'll handle mirroring in the drawing code
    // canvas.style.transform = 'scaleX(-1)';
    
    // Insert after video element
    // videoElement.parentNode.insertBefore(canvas, videoElement.nextSibling);
    
    // Make sure the canvas is the same size as the video
    // canvas.width = videoElement.offsetWidth;
    // canvas.height = videoElement.offsetHeight;
    
    return canvas;
}

// Draw face detection results on the overlay canvas
function drawFaceDetections(canvas, detections, options = {}) {
    if (!canvas || !detections) return;
    
    const ctx = canvas.getContext('2d');
    // Clear the entire canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Get confidence if available (for color coding)
    let confidence = 0;
    if (options.confidence !== undefined) {
        confidence = options.confidence;
    }
    
    // Determine box color based on confidence/liveness
    let boxColor = options.boxColor || 'blue';
    if (confidence > 0) {
        if (isLiveFace(confidence)) {
            boxColor = '#00FF00'; // Green for valid face
        } else {
            boxColor = '#FF0000'; // Red for rejected face
        }
    }
    
    // Set default options
    const defaultOptions = {
        drawBox: true,
        boxColor: boxColor,
        drawPoints: true,
        pointColor: 'red',
        drawLabel: true,
        labelText: '',
        labelColor: 'white',
        labelBgColor: 'rgba(0,0,0,0.5)'
    };
    
    const opts = { ...defaultOptions, ...options };
    
    // Get the detection box if available
    const box = detections.detection ? detections.detection.box : null;
    const landmarks = detections.landmarks ? detections.landmarks.positions : null;
    
    // Save current context state
    ctx.save();
    
    // // Set up the mirroring transformation
    // ctx.scale(-1, 1);
    // ctx.translate(-canvas.width, 0);
    
    // Draw face box
    if (opts.drawBox && box) {
        ctx.strokeStyle = opts.boxColor;
        ctx.lineWidth = 2;
        ctx.strokeRect(box.x, box.y, box.width, box.height);
    }
    
    // Draw face landmarks
    if (opts.drawPoints && landmarks) {
        ctx.fillStyle = opts.pointColor;
        
        for (const point of landmarks) {
            ctx.beginPath();
            ctx.arc(point.x, point.y, 2, 0, 2 * Math.PI);
            ctx.fill();
        }
    }
    
    // Draw label - add confidence level to label text
    if (opts.drawLabel && box) {
        let text = opts.labelText;
        
        // Add confidence to label if available
        if (confidence > 0) {
            text += ` (${(confidence * 100).toFixed(0)}%)`;
        }
        
        ctx.font = '16px Arial';
        const textWidth = ctx.measureText(text).width;
        const textHeight = 20;
        
        ctx.fillStyle = opts.labelBgColor;
        ctx.fillRect(box.x, box.y - textHeight, textWidth + 10, textHeight);
        
        ctx.fillStyle = opts.labelColor;
        ctx.fillText(text, box.x + 5, box.y - 5);
    }
    
    // Restore the context to its original state
    ctx.restore();
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