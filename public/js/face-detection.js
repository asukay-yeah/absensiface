// Initialize face-api.js
let faceDetectionModel = 'ssd'; // Use 'ssd' or 'tiny' to select model

async function initFaceApi() {
    console.log('Initializing face-api.js...');
    
    // Check if face-api is already loaded
    if ((faceDetectionModel === 'ssd' && faceapi.nets.ssdMobilenetv1.isLoaded) || 
        (faceDetectionModel === 'tiny' && faceapi.nets.tinyFaceDetector.isLoaded)) {
        console.log('Models already loaded.');
        return true;
    }

    try {
        // Use absolute paths to models
        const modelPath = '/js/face-api/models';
        
        // Load face landmark and recognition models (required for both methods)
        console.log('Loading face landmark model...');
        await faceapi.nets.faceLandmark68Net.loadFromUri(modelPath);
        
        console.log('Loading face recognition model...');
        await faceapi.nets.faceRecognitionNet.loadFromUri(modelPath);
        
        // Load chosen face detection model
        if (faceDetectionModel === 'ssd') {
            console.log('Loading SSD MobileNet model...');
            await faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath);
        } else {
            console.log('Loading Tiny Face Detector model...');
            await faceapi.nets.tinyFaceDetector.loadFromUri(modelPath);
        }
        
        console.log('All face-api models loaded successfully!');
        return true;
    } catch (error) {
        console.error('Error loading face-api models:', error);
        // Try alternative model if the first one fails
        if (faceDetectionModel === 'ssd') {
            console.log('Trying to load Tiny Face Detector as fallback...');
            faceDetectionModel = 'tiny';
            try {
                await faceapi.nets.tinyFaceDetector.loadFromUri('/js/face-api/models');
                console.log('Fallback to Tiny Face Detector successful');
                return true;
            } catch (fallbackError) {
                console.error('Fallback also failed:', fallbackError);
                throw new Error('Could not load any face detection models');
            }
        }
        throw error;
    }
}

// Detect faces in an image or video element
async function detectFace(imageElement) {
    try {
        // Check if the element is defined
        if (!imageElement) {
            console.error('Image element is undefined');
            return null;
        }
        
        console.log('Detecting faces...');
        let detections;

        if (faceDetectionModel === 'ssd') {
            // SSD MobileNet (more accurate but slower)
            detections = await faceapi.detectAllFaces(
                imageElement, 
                new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 })
            )
            .withFaceLandmarks()
            .withFaceDescriptors();
        } else {
            // Tiny Face Detector (faster but less accurate)
            detections = await faceapi.detectAllFaces(
                imageElement, 
                new faceapi.TinyFaceDetectorOptions({ minConfidence: 0.5 })
            )
            .withFaceLandmarks()
            .withFaceDescriptors();
        }

        console.log(`Face detection found ${detections.length} faces`);

        if (detections.length === 0) {
            console.warn('No faces detected in image');
            return null;
        }

        // Return the first face detection result
        return detections[0];
    } catch (error) {
        console.error('Error during face detection:', error);
        return null;
    }
}

// Convert face descriptor to string for storage
function descriptorToString(descriptor) {
    if (!descriptor) return null;
    return JSON.stringify(Array.from(descriptor));
}

// Convert string back to face descriptor
function stringToDescriptor(str) {
    if (!str) return null;
    try {
        return new Float32Array(JSON.parse(str));
    } catch (e) {
        console.error('Error parsing face descriptor:', e);
        return null;
    }
}

// Find the best match from a list of known faces
async function findBestMatch(faceDescriptor, knownFaces) {
    if (!faceDescriptor || !knownFaces || knownFaces.length === 0) {
        console.log('No face descriptor or known faces provided for matching');
        return null;
    }

    try {
        // Create face matcher with known faces
        const labeledDescriptors = knownFaces.map(face => {
            const descriptor = stringToDescriptor(face.descriptor);
            if (!descriptor) {
                console.warn(`Invalid descriptor for face ID ${face.id}`);
                return null;
            }
            return new faceapi.LabeledFaceDescriptors(
                face.id.toString(), 
                [descriptor]
            );
        }).filter(desc => desc !== null);

        // If we have no valid descriptors, return null
        if (labeledDescriptors.length === 0) {
            console.warn('No valid face descriptors found in known faces');
            return null;
        }

        console.log(`Matching against ${labeledDescriptors.length} known faces`);
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6); // 0.6 is the threshold
        
        // Find the best match
        const match = faceMatcher.findBestMatch(faceDescriptor);
        console.log('Match result:', match.toString());
        
        // If the match is too uncertain (label is 'unknown'), return null
        if (match.label === 'unknown') {
            console.log('No match found - unknown face');
            return null;
        }
        
        // Return the matching face ID and confidence (lower distance = better match)
        return {
            id: parseInt(match.label),
            confidence: 1 - match.distance
        };
    } catch (error) {
        console.error('Error finding best face match:', error);
        return null;
    }
}

// Create a face overlay canvas to show detection results
function createFaceOverlay(videoElement, parentElement) {
    // Create a canvas element
    const canvas = document.createElement('canvas');
    canvas.className = 'face-overlay absolute top-0 left-0 w-full h-full';
    
    // Insert after video element
    videoElement.parentNode.insertBefore(canvas, videoElement.nextSibling);
    
    // Make sure the canvas is the same size as the video
    canvas.width = videoElement.offsetWidth;
    canvas.height = videoElement.offsetHeight;
    
    return canvas;
}

// Draw face detection results on the overlay canvas
function drawFaceDetections(canvas, detections, options = {}) {
    if (!canvas || !detections) return;
    
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Set default options
    const defaultOptions = {
        drawBox: true,
        boxColor: 'blue',
        drawPoints: true,
        pointColor: 'red',
        drawLabel: true,
        labelText: '',
        labelColor: 'white',
        labelBgColor: 'rgba(0,0,0,0.5)'
    };
    
    const opts = { ...defaultOptions, ...options };
    
    // Draw face box
    if (opts.drawBox && detections.detection) {
        const box = detections.detection.box;
        ctx.strokeStyle = opts.boxColor;
        ctx.lineWidth = 2;
        ctx.strokeRect(box.x, box.y, box.width, box.height);
    }
    
    // Draw face landmarks
    if (opts.drawPoints && detections.landmarks) {
        const points = detections.landmarks.positions;
        ctx.fillStyle = opts.pointColor;
        
        for (const point of points) {
            ctx.beginPath();
            ctx.arc(point.x, point.y, 2, 0, 2 * Math.PI);
            ctx.fill();
        }
    }
    
    // Draw label
    if (opts.drawLabel && opts.labelText && detections.detection) {
        const box = detections.detection.box;
        const text = opts.labelText;
        
        ctx.font = '16px Arial';
        const textWidth = ctx.measureText(text).width;
        const textHeight = 20;
        
        ctx.fillStyle = opts.labelBgColor;
        ctx.fillRect(box.x, box.y - textHeight, textWidth + 10, textHeight);
        
        ctx.fillStyle = opts.labelColor;
        ctx.fillText(text, box.x + 5, box.y - 5);
    }
}