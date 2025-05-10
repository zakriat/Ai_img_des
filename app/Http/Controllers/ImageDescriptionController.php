<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageDescriptionController extends Controller
{
    protected $geminiApiKey;
    protected $geminiEndpoint;

    public function __construct()
    {
        // Load API key from .env file
        $this->geminiApiKey = env('GEMINI_API_KEY');
        $this->geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    }

    public function index()
    {
        return view('image-description.index');
    }

    public function generateDescription(Request $request)
    {
        // Validate request
        $request->validate([
            'image' => 'required|image|max:5000', // Max 5MB
            'description_type' => 'required|in:alt_text,product_description,detailed_analysis,scene_identification,categorization',
        ]);

        try {
            // Process uploaded image
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image->path()));
            $mimeType = $image->getMimeType();

            // Create prompt based on description type
            $prompt = $this->createPrompt($request->description_type);

            // Prepare data for Gemini API
            $data = [
                'contents' => [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'maxOutputTokens' => 800,
                ]
            ];

            // Make API request to Gemini
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("{$this->geminiEndpoint}?key={$this->geminiApiKey}", $data);

            // Process response
            if ($response->successful()) {
                $responseData = $response->json();

                // Extract the description from Gemini's response
                $description = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No description generated.';

                // Create a unique filename
                $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();

                // FIXED: Directly store to the public disk to ensure proper path
                // This will save to storage/app/public/analyzed_images/
                $image->storeAs('analyzed_images', $filename, 'public');

                // Generate the correct URL for accessing the image
                $imageUrl = asset('storage/analyzed_images/' . $filename);

                return view('image-description.result', [
                    'description' => $description,
                    'imageUrl' => $imageUrl,
                    'type' => $request->description_type
                ]);
            } else {
                return back()->with('error', 'Failed to generate description: ' . ($response->json()['error']['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    private function createPrompt($type)
    {
        switch ($type) {
            case 'alt_text':
                return 'Generate a concise, accessible alt text for this image that would be useful for screen readers. Keep it under 125 characters while capturing the essential visual information.';

            case 'product_description':
                return 'Analyze this product image and create a detailed product description including visual features, apparent functionality, and potential benefits. Format it in a way suitable for an e-commerce listing.';

            case 'detailed_analysis':
                return 'Provide a comprehensive analysis of this image, including main subjects, background elements, colors, composition, mood, and any notable features. Be thorough but clear.';

            case 'scene_identification':
                return 'Identify the type of scene shown in this image. Describe the setting, time of day, apparent location, and any activities taking place. Also note any distinct objects or elements.';

            case 'categorization':
                return 'Categorize this image into appropriate taxonomies. Suggest 5-7 categories or tags that would help organize this image in a database. Format as a list.';

            default:
                return 'Describe what you see in this image with detail and accuracy.';
        }
    }
}
