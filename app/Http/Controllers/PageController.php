<?php
namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{

    protected $reservedSlugs = [
        'pages',
        'dashboard',
        'api',
        'login',
        'register',
        'logout',
        'password',
        'verify-email',
        'confirm-password',
        'settings',
    ];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'seo' => 'nullable|string|max:255',
        ]);

        if ($validated['slug'] === null) {
            $existingFrontPage = Page::where('slug', null)->first();
            if ($existingFrontPage) {
                return response()->json(['error' => 'A front page already exists.'], 422);
            }
        }

        // Check if slug is reserved
        if (in_array($validated['slug'], $this->reservedSlugs)) {
            return response()->json(['error' => 'The slug is reserved and cannot be used.'], 422);
        }

        $page = Page::create($validated);

        return response()->json($page, 201);
    }

    public function show(Page $page)
    {
        return response()->json($page);
    }

    public function index()
    {
        $pages = Page::all();
        return response()->json($pages);
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'seo' => 'sometimes|nullable|string|max:255',
        ]);

        if ($validated['slug'] === null) {
            $existingFrontPage = Page::where('slug', null)->where('id', '!=', $page->id)->first();
            if ($existingFrontPage) {
                return response()->json(['error' => 'A front page already exists.'], 422);
            }
        }

        // Check if slug is reserved
        if (in_array($validated['slug'], $this->reservedSlugs)) {
            return response()->json(['error' => 'The slug is reserved and cannot be used.'], 422);
        }

        $page->update($validated);

        return response()->json($page);
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json(null, 204);
    }

    public function showBySlug($slug)
    {
        // Handle other pages
        $page = Page::where('slug', $slug)->firstOrFail();

        return Inertia::render('Page', [
            'page' => $page,
        ]);
    }

    public function showFrontPage(Request $request)
    {
        // Fetch the front page
        $page = Page::whereNull('slug')->firstOrFail();

        return Inertia::render('Page', [
            'page' => $page,
        ]);
    }

    public function showPageBuilderBySlug($slug = null)
    {
        if ($slug === null || $slug === '') {
            // Handle the front page
            $page = Page::where('slug', '')->orWhereNull('slug')->firstOrFail();
        } else {
            // Handle other pages
            $page = Page::where('slug', $slug)->firstOrFail();
        }

        return Inertia::render('PageBuilder', [
            'page' => $page,
        ]);
    }
}