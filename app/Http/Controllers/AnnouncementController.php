<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    /* ===========================
     *  PUBLIC (TOURIST PORTAL)
     * =========================== */

    // List for tourists
    public function index()
    {
        $announcements = Announcement::where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(6);

        return view('announcements.index', compact('announcements'));
    }

    // Show one announcement
    public function show(Announcement $announcement)
    {
        if ($announcement->status !== 'published' && ! (auth()->check() && auth()->user()->role === 'admin')) {
            abort(404);
        }

        return view('announcements.show', compact('announcement'));
    }

    /* ===========================
     *  ADMIN (LGU PORTAL)
     * =========================== */

    // Admin list
    public function adminIndex()
    {
        $announcements = Announcement::orderByDesc('created_at')->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    // Show create form
    public function create()
    {
        return view('admin.announcements.create');
    }

    // Store
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'body'               => 'required|string',
            'municipality_scope' => 'nullable|string|max:50',
            'status'             => 'required|in:draft,published',
            'image'              => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($data['title']);
if (Announcement::where('slug', $slug)->exists()) {
    $slug .= '-' . time();
}

// handle image upload
$imagePath = null;
if ($request->hasFile('image')) {
    $imagePath = $request->file('image')->store('announcements', 'public');
}

Announcement::create([
    'user_id'            => auth()->id(),
    'title'              => $data['title'],
    'slug'               => $slug,
    'excerpt'            => Str::limit(strip_tags($data['body']), 180),
    'body'               => $data['body'],
    'municipality_scope' => $data['municipality_scope'] ?: null,
    'status'             => $data['status'],
    'published_at'       => $data['status'] === 'published' ? Carbon::now() : null,
    'image_path'         => $imagePath,
]);


        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created.');
    }

    // Edit form
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    // Update
    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'body'               => 'required|string',
            'municipality_scope' => 'nullable|string|max:50',
            'status'             => 'required|in:draft,published',
            'image'              => 'nullable|image|max:2048',
        ]);

        $announcement->title              = $data['title'];
$announcement->excerpt            = Str::limit(strip_tags($data['body']), 180);
$announcement->body               = $data['body'];
$announcement->municipality_scope = $data['municipality_scope'] ?: null;

// status / published_at logic stays

// handle optional new image
if ($request->hasFile('image')) {
    $imagePath = $request->file('image')->store('announcements', 'public');
    $announcement->image_path = $imagePath;
}

if ($announcement->status !== 'published' && $data['status'] === 'published') {
    $announcement->published_at = Carbon::now();
} elseif ($data['status'] === 'draft') {
    $announcement->published_at = null;
}

$announcement->status = $data['status'];
$announcement->save();


        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated.');
    }

    // Delete
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted.');
    }
}
