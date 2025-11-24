<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\Storage;

class WizardController extends Controller
{
    private function getBusiness()
    {
        return Business::where('user_id', auth()->id())->firstOrFail();
    }

    // STEP 1
    public function step1()
    {
        $business = $this->getBusiness();
        return view('owner.wizard.step1', compact('business'));
    }

    public function step1Save(Request $request)
    {
        $business = $this->getBusiness();

        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'municipality' => 'required',
            'description' => 'required'
        ]);

        $business->update($validated);

        return redirect()->route('owner.wizard.step2');
    }

    // STEP 2
    public function step2()
    {
        $business = $this->getBusiness();
        return view('owner.wizard.step2', compact('business'));
    }

    public function step2Save(Request $request)
    {
        $business = $this->getBusiness();

        $validated = $request->validate([
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'facebook_page' => 'nullable|url',
        ]);

        $business->update($validated);

        return redirect()->route('owner.wizard.step3');
    }

    // STEP 3
    public function step3()
    {
        $business = $this->getBusiness();
        return view('owner.wizard.step3', compact('business'));
    }

    public function step3Save(Request $request)
    {
        $business = $this->getBusiness();

        $validated = $request->validate([
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0|gte:min_price',
        ]);

        $business->update($validated);

        return redirect()->route('owner.wizard.step4');
    }

    // STEP 4
    public function step4()
    {
        $business = $this->getBusiness();
        return view('owner.wizard.step4', compact('business'));
    }

    public function step4Save(Request $request)
    {
        $business = $this->getBusiness();

        $validated = $request->validate([
            'thumbnail' => 'required|image|max:2048',
        ]);

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->thumbnail->store('business-thumbnails', 'public');
            $business->thumbnail = $path;
        }

        $business->wizard_completed = true;
        $business->save();

        return redirect()->route('owner.dashboard')
            ->with('success', 'Your business setup is complete! Pending admin approval.');
    }
}
