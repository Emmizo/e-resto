<?php

namespace App\Http\Controllers;

use App\Models\TermsAndConditions;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    public function show()
    {
        $terms = TermsAndConditions::where('is_active', true)->latest()->first();
        return view('terms-and-conditions', compact('terms'));
    }
}
