<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Description Generator</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .navbar {
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .container {
            margin-top: 20px;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .description-box {
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        .image-container {
            text-align: center;
        }
        .image-container img {
            max-height: 400px;
            width: auto;
            object-fit: contain;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        footer {
            margin-top: 40px;
            padding: 20px 0;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <strong>Image Description Generator</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('image-description.index') }}">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="container">
        <p>Powered by Laravel and Gemini AI</p>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional JavaScript for any custom functionality -->
    <script>
        // Add any custom JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Preview image before upload
            const imageInput = document.getElementById('image');
            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.classList.add('mt-3', 'image-preview');

                            // Remove any existing preview
                            const existingPreview = document.querySelector('.image-preview');
                            if (existingPreview) {
                                existingPreview.remove();
                            }

                            previewDiv.innerHTML = `
                                <p>Image Preview:</p>
                                <img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            `;

                            imageInput.parentNode.appendChild(previewDiv);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        });
    </script>
</body>
</html>
