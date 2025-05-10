@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Image Description Result
                    <a href="{{ route('image-description.index') }}" class="btn btn-sm btn-outline-secondary float-end">Upload New Image</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="image-container mb-3">
                                <img src="{{ $imageUrl }}" alt="Analyzed image" class="img-fluid rounded">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>
                                @switch($type)
                                    @case('alt_text')
                                        Accessible Alt Text
                                        @break
                                    @case('product_description')
                                        Product Description
                                        @break
                                    @case('detailed_analysis')
                                        Detailed Analysis
                                        @break
                                    @case('scene_identification')
                                        Scene Identification
                                        @break
                                    @case('categorization')
                                        Image Categorization
                                        @break
                                    @default
                                        Generated Description
                                @endswitch
                            </h5>
                            <div class="description-box p-3 border rounded bg-light">
                                {!! nl2br(e($description)) !!}
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                    onclick="navigator.clipboard.writeText('{{ str_replace("'", "\\'", $description) }}').then(() => alert('Description copied to clipboard!'))">
                                    Copy to Clipboard
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
