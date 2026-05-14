<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::all();
        return view('venues.index', compact('venues'));
    }
    public function adminIndex()
    {
        $venues = Venue::latest()->paginate(10);
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('venues', 'public');
        }

        Venue::create($data);

        return redirect()->route('admin.venues.index')->with('success', 'Venue created successfully.');
    }

    public function edit(Venue $venue)
    {
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($venue->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($venue->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($venue->image);
            }
            $data['image'] = $request->file('image')->store('venues', 'public');
        }

        $venue->update($data);

        return redirect()->route('admin.venues.index')->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue)
    {
        if ($venue->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($venue->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($venue->image);
        }
        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Venue deleted successfully.');
    }
