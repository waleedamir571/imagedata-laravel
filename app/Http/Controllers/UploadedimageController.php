<?php

namespace App\Http\Controllers;

use App\Models\ImageData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadedimageController extends Controller
{
    public function showImages(Request $request)
    {
        if ($request->ajax()) {
            $country = $request->input('country');
            $images = ImageData::where('country_code', $country)->pluck('image_paths');

            $imagePaths = collect($images)->flatMap(fn($paths) => json_decode($paths, true))->toArray();

            return response()->json(['images' => $imagePaths]);
        }

        $countries = ImageData::select('country_code','country')->distinct()->get();
        return view('uploaded-images', compact('countries'));
    }

}
