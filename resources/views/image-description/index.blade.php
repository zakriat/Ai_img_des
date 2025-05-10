@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Image Description Generator</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('image-description.generate') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="image">Upload Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="description_type">Description Type</label>
                            <select class="form-control @error('description_type') is-invalid @enderror" id="description_type" name="description_type" required>
                                <option value="alt_text">Accessible Alt Text</option>
                                <option value="product_description">Product Description</option>
                                <option value="detailed_analysis">Detailed Analysis</option>
                                <option value="scene_identification">Scene Identification</option>
                                <option value="categorization">Image Categorization</option>
                            </select>
                            @error('description_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Generate Description</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
