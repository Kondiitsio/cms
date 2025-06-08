<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{

    public function showAll()
    {
        $images = Image::all();
        return response()->json($images);
    }

    public function getImageById($id)
    {
        $image = Image::findOrFail($id);
        return response()->json($image);
    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Create an ImageManager instance with the desired driver
        $manager = new ImageManager(new Driver());

        $file = $request->file('image');

        // Generate a unique file name
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store the original image
        $filePath = $file->storeAs('images', $fileName, 'public');

        // Initialize thumbnail path as null
        $thumbnailPath = null;

        // Handle SVG files separately
        if ($file->getClientOriginalExtension() === 'svg') {
            $thumbnailPath = null; // No thumbnail for SVG
        } else {
            // Generate and store thumbnail using Intervention Image
            $thumbnailPath = 'thumbnails/' . $fileName;
            $image = $manager->read($file->getRealPath());
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(storage_path('app/public/' . $thumbnailPath));
        }

        // Save the image data to the database
        $image = Image::create([
            'name' => null, // User can fill this later
            'description' => null, // User can fill this later
            'path' => $filePath,
            'thumbnail_path' => $thumbnailPath,
            'type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $image = Image::findOrFail($id);
        $image->name = $request->input('name', $image->name);
        $image->description = $request->input('description', $image->description);
        $image->save();

        return redirect()->back()->with('success', 'Image updated successfully!');
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Delete the image file from storage
        Storage::disk('public')->delete($image->path);

        // Delete the thumbnail if it exists
        if ($image->thumbnail_path) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }

        // Delete the image record from the database
        $image->delete();

        return response()->json(['success' => 'image deleted successfully!']);
    }
}