<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $municipality = $request->query('municipality');
        $category     = $request->query('category');
        $search       = $request->query('q');

        $query = Attraction::query()
            ->where('status', 'published');

        if ($municipality) {
            $query->where('municipality', $municipality);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $attractions = $query->orderBy('name')->paginate(9)->withQueryString();

        $municipalities = ['Bantayan', 'Santa Fe', 'Madridejos'];
        $categories     = ['beach', 'church', 'landmark', 'park', 'food', 'activity'];

        return view('attractions.index', compact(
            'attractions',
            'municipalities',
            'categories',
            'municipality',
            'category',
            'search'
        ));
    }

    public function show(Attraction $attraction)
    {
        abort_unless($attraction->status === 'published', 404);

        return view('attractions.show', compact('attraction'));
    }
}
