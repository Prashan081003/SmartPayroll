<div class="image-upload-container" id="image-container-<?= $recordId ?>">
    <style>
        .image-upload-container {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            max-width: 400px;
        }
        .image-preview {
            margin: 15px 0;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin: 10px 5px;
        }
        .upload-btn {
            border: 2px solid #4CAF50;
            color: #4CAF50;
            background-color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-btn:hover {
            background-color: #4CAF50;
            color: white;
        }
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .remove-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .remove-btn:hover {
            background-color: #da190b;
        }
        .upload-message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .upload-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .upload-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .no-image-placeholder {
            padding: 40px;
            color: #999;
            font-size: 16px;
        }
        .spinner {
            display: none;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <?php if ($mode === 'view'): ?>
        <!-- View Only Mode -->
        <?php if ($hasImage): ?>
            <div class="image-preview">
                <img src="<?= $this->Url->build('/files/' . h($imagePath)) ?>" alt="Employee Photo">
            </div>
        <?php else: ?>
            <div class="no-image-placeholder">
                <p>📷 No photo uploaded</p>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Edit/Add Mode with Upload -->
        <div class="image-preview" id="preview-<?= $recordId ?>">
            <?php if ($hasImage): ?>
                <img src="<?= $this->Url->build('/files/' . h($imagePath)) ?>" alt="Employee Photo" id="preview-img-<?= $recordId ?>">
            <?php else: ?>
                <div class="no-image-placeholder" id="no-image-<?= $recordId ?>">
                    <p>📷 No photo uploaded</p>
                    <p style="font-size: 12px; color: #666;">Supported: JPG, PNG, GIF (Max 5MB)</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="spinner" id="spinner-<?= $recordId ?>"></div>
        <div class="upload-message" id="message-<?= $recordId ?>" style="display: none;"></div>

        <?php if (!empty($uploadUrl) && !empty($recordId)): ?>
            <div class="upload-btn-wrapper">
                <button class="upload-btn" type="button">
                    <?= $hasImage ? '🔄 Change Photo' : '📤 Upload Photo' ?>
                </button>
                <input 
                    type="file" 
                    id="photo-upload-<?= $recordId ?>" 
                    accept="image/*"
                    data-upload-url="<?= $this->Url->build($uploadUrl) ?>"
                    data-record-id="<?= $recordId ?>"
                />
            </div>

            <?php if ($hasImage): ?>
                <button 
                    type="button" 
                    class="remove-btn" 
                    id="remove-btn-<?= $recordId ?>"
                    data-remove-url="<?= $this->Url->build(['controller' => 'Employees', 'action' => 'removePhoto', $recordId]) ?>"
                    data-record-id="<?= $recordId ?>"
                >
                    🗑️ Remove Photo
                </button>
            <?php endif; ?>
        <?php else: ?>
            <p style="color: #666; font-size: 14px;">
                <?= empty($recordId) ? 'Save employee first to upload photo' : 'Upload not configured' ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php if ($mode !== 'view' && !empty($uploadUrl) && !empty($recordId)): ?>
<script>
(function() {
    var recordId = <?= json_encode($recordId) ?>;
    var fileInput = document.getElementById('photo-upload-' + recordId);
    var removeBtn = document.getElementById('remove-btn-' + recordId);
    var preview = document.getElementById('preview-' + recordId);
    var previewImg = document.getElementById('preview-img-' + recordId);
    var noImagePlaceholder = document.getElementById('no-image-' + recordId);
    var spinner = document.getElementById('spinner-' + recordId);
    var messageDiv = document.getElementById('message-' + recordId);

    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = 'upload-message ' + type;
        messageDiv.style.display = 'block';
        setTimeout(function() {
            messageDiv.style.display = 'none';
        }, 5000);
    }

    function showSpinner(show) {
        spinner.style.display = show ? 'block' : 'none';
    }

    // Handle file upload
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;

            // Validate file type
            if (!file.type.match('image.*')) {
                showMessage('Please select an image file', 'error');
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showMessage('File size must be less than 5MB', 'error');
                return;
            }

            var formData = new FormData();
            formData.append('photo', file);

            showSpinner(true);

            // Upload via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', fileInput.getAttribute('data-upload-url'), true);
            
            xhr.onload = function() {
                showSpinner(false);
                
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showMessage('Photo uploaded successfully!', 'success');
                            
                            // Update preview
                            if (previewImg) {
                                previewImg.src = response.imageUrl + '?t=' + new Date().getTime();
                            } else {
                                if (noImagePlaceholder) {
                                    noImagePlaceholder.style.display = 'none';
                                }
                                preview.innerHTML = '<img src="' + response.imageUrl + '" alt="Employee Photo" id="preview-img-' + recordId + '">';
                            }
                            
                            // Show remove button if not exists
                            if (!removeBtn) {
                                location.reload();
                            }
                        } else {
                            showMessage(response.message || 'Upload failed', 'error');
                        }
                    } catch (e) {
                        showMessage('Upload failed: Invalid response', 'error');
                    }
                } else {
                    showMessage('Upload failed: Server error', 'error');
                }
                
                fileInput.value = '';
            };

            xhr.onerror = function() {
                showSpinner(false);
                showMessage('Upload failed: Network error', 'error');
                fileInput.value = '';
            };

            xhr.send(formData);
        });
    }

    // Handle remove photo
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to remove this photo?')) {
                return;
            }

            showSpinner(true);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', removeBtn.getAttribute('data-remove-url'), true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                showSpinner(false);
                
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showMessage('Photo removed successfully!', 'success');
                            
                            // Update preview
                            preview.innerHTML = '<div class="no-image-placeholder" id="no-image-' + recordId + '"><p>📷 No photo uploaded</p><p style="font-size: 12px; color: #666;">Supported: JPG, PNG, GIF (Max 5MB)</p></div>';
                            
                            // Reload page to update UI
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            showMessage(response.message || 'Remove failed', 'error');
                        }
                    } catch (e) {
                        showMessage('Remove failed: Invalid response', 'error');
                    }
                } else {
                    showMessage('Remove failed: Server error', 'error');
                }
            };

            xhr.onerror = function() {
                showSpinner(false);
                showMessage('Remove failed: Network error', 'error');
            };

            xhr.send();
        });
    }
})();
</script>
<?php endif; ?>