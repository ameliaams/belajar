<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function create()
    {
        return view('photos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photos' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $photo = Photo::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        //simpan semua foto (multiple)
        foreach ($request->file('photos') as $file) {
            $path = $file->store('public/photos');

            PhotoFile::create([
                'photo_id' => $photo->id,
                'file_path' => str_replace('public/', '', $path)
            ]);
        }

        return redirect()->route('photos.index')->with('success', 'Photos uploaded successfully!');
    }

    public function index()
    {
        $photos = Photo::with('files')->get();
        return view('photos.index', compact('photos'));
    }
}
