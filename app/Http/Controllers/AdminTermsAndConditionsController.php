<?php

namespace App\Http\Controllers;

use App\Models\TermsAndConditions;
use Illuminate\Http\Request;

class AdminTermsAndConditionsController extends Controller
{
    public function index()
    {
        $terms = TermsAndConditions::orderByDesc('created_at')->get();
        return view('admin.terms.index', compact('terms'));
    }

    public function create()
    {
        return view('admin.terms.create');
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required']);
        TermsAndConditions::create([
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.terms.index')->with('success', 'Terms added!');
    }

    public function edit($id)
    {
        $term = TermsAndConditions::findOrFail($id);
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['content' => 'required']);
        $term = TermsAndConditions::findOrFail($id);
        $term->update([
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.terms.index')->with('success', 'Terms updated!');
    }
}
