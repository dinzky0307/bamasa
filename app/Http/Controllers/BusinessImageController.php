<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessImage;
use Illuminate\Http\Request;

class BusinessImageController extends Controller
{
    protected function getOwnerBusiness(): Business
    {
        return Business::where('user_id', auth()->id())->firstOrFail();
    }

    public function index()
    {
        $business = $this->getOwnerBusiness();
        $images   = $business->images;

        return view('owner.business.images', compact('business', 'images'));
    }

    public function store(Request $request)
    {
        $business = $this->getOwnerBusiness();

        $validated = $request->validate([
            'images.*' => 'required|image|max:4096',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('business-gallery', 'public');

                BusinessImage::create([
                    'business_id' => $business->id,
                    'path'        => $path,
                    'sort_order'  => $business->images()->max('sort_order') + $idx + 1,
                ]);
            }
        }

        return redirect()->route('owner.business.images')
            ->with('success', 'Images uploaded successfully.');
    }

    public function destroy(BusinessImage $image)
    {
        $business = $this->getOwnerBusiness();

        // ensure owner can only delete their own images
        abort_unless($image->business_id === $business->id, 403);

        // optional: delete file from storage
        // \Storage::disk('public')->delete($image->path);

        $image->delete();

        return redirect()->route('owner.business.images')
            ->with('success', 'Image removed.');
    }

    // in BusinessController@show
public function show(Business $business)
{
    if ($business->status !== 'approved') {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(404);
        }
    }

    $business->load('images');

    return view('businesses.show', compact('business'));
}

}
