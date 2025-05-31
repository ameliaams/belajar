<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            color: #2c3e50;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        .upload-box {
            width: 100%;
            padding: 40px 20px;
            border: 2px dashed #bdc3c7;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }
        .upload-box:hover {
            border-color: #3498db;
            background-color: #e8f4fd;
        }
        .upload-icon {
            font-size: 48px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        .upload-text {
            font-size: 18px;
            color: #7f8c8d;
        }
        #fileInput {
            display: none;
        }
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(231, 76, 60, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        .remove-btn:hover {
            background: rgba(192, 57, 43, 1);
            transform: scale(1.1);
        }
        .submit-btn {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            background-color: #3498db;
            border: none;
            transition: all 0.3s;
        }
        .submit-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .uploaded-count {
            margin-top: 10px;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
        .form-divider {
            border-top: 1px solid #e0e0e0;
            margin: 25px 0;
        }
        .hidden-input-container {
            display: none;
        }
    </style>
</head>
<body style="background-color: #f5f7fa; padding: 40px 0;">
    <div class="upload-container">
        <div class="form-header">
            <h2>Upload Foto Baru</h2>
            <p class="text-muted">Isi detail dan unggah foto Anda</p>
        </div>

        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Judul Foto</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul foto" required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tambahkan deskripsi foto (opsional)"></textarea>
            </div>

            <div class="form-divider"></div>
            <div class="form-group">
                <label class="form-label">Unggah Foto</label>

                <!-- Input file utama untuk menambah file baru -->
                <input type="file" id="fileInput" accept="image/*">

                <!-- Container untuk input file tersembunyi -->
                <div id="hiddenInputsContainer" class="hidden-input-container"></div>

                <!-- Kotak upload yang bisa diklik -->
                <div class="upload-box" id="uploadBox">
                    <div class="upload-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div class="upload-text">Klik untuk menambah foto</div>
                </div>

                <!-- Info jumlah foto yang sudah dipilih -->
                <div class="uploaded-count" id="uploadedCount">0 foto terpilih</div>

                <!-- Container untuk preview gambar -->
                <div class="preview-container" id="previewContainer"></div>
            </div>

            <!-- Tombol submit -->
            <button type="submit" class="btn btn-primary submit-btn">
                <i class="bi bi-upload"></i> Upload Semua Foto
            </button>
        </form>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadBox = document.getElementById('uploadBox');
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');
            const uploadedCount = document.getElementById('uploadedCount');
            const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
            let fileCounter = 0;

            // Handle klik pada kotak upload
            uploadBox.addEventListener('click', function() {
                fileInput.click();
            });

            // Handle perubahan pada input file
            fileInput.addEventListener('change', function() {
                if (this.files.length) {
                    for (let i = 0; i < this.files.length; i++) {
                        addFileToForm(this.files[i]);
                    }
                    // Reset input file untuk memungkinkan pemilihan file yang sama lagi
                    this.value = '';
                }
            });

            // Fungsi untuk menambahkan file ke form
            function addFileToForm(file) {
                const fileId = `file-${fileCounter++}`;

                // Buat input file tersembunyi
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = 'photos[]';
                hiddenInput.id = fileId;
                hiddenInput.classList.add('hidden-file-input');

                // Buat container untuk data file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                hiddenInput.files = dataTransfer.files;

                // Tambahkan ke form
                hiddenInputsContainer.appendChild(hiddenInput);

                // Tambahkan preview
                addPreview(file, fileId);

                // Update counter
                updateCount();
            }

            // Fungsi untuk menambah preview gambar
            function addPreview(file, fileId) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.dataset.fileId = fileId;

                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn">&times;</button>
                    `;

                    previewContainer.appendChild(previewItem);

                    // Tambahkan event listener untuk tombol hapus
                    previewItem.querySelector('.remove-btn').addEventListener('click', function() {
                        removeFile(fileId);
                    });
                };

                reader.readAsDataURL(file);
            }

            // Fungsi untuk menghapus file
            function removeFile(fileId) {
                // Hapus input file tersembunyi
                const inputToRemove = document.getElementById(fileId);
                if (inputToRemove) {
                    hiddenInputsContainer.removeChild(inputToRemove);
                }

                // Hapus preview
                const previewToRemove = document.querySelector(`.preview-item[data-file-id="${fileId}"]`);
                if (previewToRemove) {
                    previewContainer.removeChild(previewToRemove);
                }

                // Update counter
                updateCount();
            }

            // Fungsi untuk update counter
            function updateCount() {
                const count = document.querySelectorAll('.hidden-file-input').length;
                uploadedCount.textContent = `${count} foto terpilih`;
                uploadedCount.style.color = count > 0 ? '#27ae60' : '#7f8c8d';
            }

            // Validasi form sebelum submit
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                const fileCount = document.querySelectorAll('.hidden-file-input').length;
                const titleValue = document.getElementById('title').value.trim();

                if (fileCount === 0) {
                    e.preventDefault();
                    alert('Silakan pilih minimal satu foto!');
                } else if (!titleValue) {
                    e.preventDefault();
                    alert('Judul foto harus diisi!');
                }
            });
        });
    </script>
</body>
</html>
