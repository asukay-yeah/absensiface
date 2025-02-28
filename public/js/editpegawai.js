// Edit Pegawai JavaScript

// This file handles additional functionality for the employee edit form
// Most of the face recognition functionality is in tambahpegawai.js

document.addEventListener('DOMContentLoaded', function() {
    // Get element for storing face descriptor in edit form
    const faceDescriptorEditInput = document.getElementById('face_descriptor_edit');
    
    // If we have the Ajax edit form setup
    if (window.jQuery && document.getElementById('editForm')) {
        // When loading employee data for editing, make sure to handle face descriptor data
        $(document).on('click', 'a[data-dropdown-id="dropdown1"]', function(e) {
            const id = $(this).data('id');
            
            // Original Ajax call is already in the main data.blade.php
            // We're just making sure we handle the face descriptor if it exists
            $.ajax({
                url: '/data/' + id,
                type: 'GET',
                success: function(data) {
                    // If there's existing face descriptor data, store it in the hidden input
                    if (data.face_descriptor && faceDescriptorEditInput) {
                        faceDescriptorEditInput.value = data.face_descriptor;
                    }
                },
                error: function(error) {
                    console.error('Error loading employee data:', error);
                }
            });
        });
    }
});