<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ImageData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class imagedataController extends Controller
{
    //
    public function index()
    {
        $title = "image-data";
        return view('image-data' , compact('title'));
        
      
    }

    public function uploadImages(Request $request)
    {
        $user = Auth::user();
        $country = $request->country;
        $city = $request->city;
        $countryCode = $request->country_code;

        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = [];

        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('uploads', 'public');
            $imagePaths[] = $imagePath;
        }

        // Create a new record for each batch of images
        ImageData::create([
            'user_id' => $user->id,
            'image_paths' => json_encode($imagePaths),
            'country' => $country,
            'city' => $city,
            'country_code' => $countryCode,
        ]);

        return response()->json(['success' => true, 'message' => 'Images uploaded successfully']);
    }

    // ImageController.php
    public function showImages(Request $request)
    {
        if ($request->ajax()) {
            $country = $request->input('country');
            $images = ImageData::where('country', $country)->pluck('image_paths');

            // Flatten the JSON array of image paths
            $imagePaths = collect($images)->flatMap(fn($paths) => json_decode($paths, true))->toArray();

            return response()->json(['images' => $imagePaths]);
        }

        // Default response (for the initial page load)
        $countries = ImageData::select('country_code','country')->distinct()->get();
        return view('countries_images', compact('countries'));
    }

}
