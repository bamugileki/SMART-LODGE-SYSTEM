<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use Illuminate\Http\Request;

class QuickLinkController extends Controller
{
    public function index()
    {
        $links = QuickLink::orderBy('section')->orderBy('group')->orderBy('order')->get();
        $sections = QuickLink::select('section')->distinct()->pluck('section');
        return view('admin.quick-links.index', compact('links', 'sections'));
    }

    public function create()
    {
        return view('admin.quick-links.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',
            'section' => 'required|string|max:100',
            'roles' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        QuickLink::create($validated);

        return redirect()->route('admin.quick-links.index')
            ->with('success', 'Quick link created.');
    }

    public function edit(QuickLink $quickLink)
    {
        return view('admin.quick-links.edit', compact('quickLink'));
    }

    public function update(Request $request, QuickLink $quickLink)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',
            'section' => 'required|string|max:100',
            'roles' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $quickLink->update($validated);

        return redirect()->route('admin.quick-links.index')
            ->with('success', 'Quick link updated.');
    }

    public function destroy(QuickLink $quickLink)
    {
        $quickLink->delete();

        return redirect()->route('admin.quick-links.index')
            ->with('success', 'Quick link deleted.');
    }
}
