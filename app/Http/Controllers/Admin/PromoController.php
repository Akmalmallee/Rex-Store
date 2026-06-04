<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->paginate(10);
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'discount_percent' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        $data['image'] = $request->file('image')->store('promos', 'public');

        Promo::create($data);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo created successfully.');
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'discount_percent' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promos', 'public');
        }

        $promo->update($data);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo updated successfully.');
    }

    public function destroy($id)
    {
        Promo::findOrFail($id)->delete();
        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo deleted successfully.');
    }
}
