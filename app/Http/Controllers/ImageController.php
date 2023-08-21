<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file'
        ]);

        $image = Image::create();

        $image->addMedia($request->image)->toMediaCollection();

        return response()->json(["message" => "Image successfully uploaded", "image" => $image]);
    }

    public function show($id)
    {
        $image = Image::find($id);

        $data = $image->getMedia();

        return response()->json(["image" => $data]);
    }
}
