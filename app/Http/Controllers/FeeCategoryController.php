<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    public function index()
    {
        $categories = FeeCategory::all();
        if (request()->routeIs('admin.*')) {
            return view('school admin.fees.categories.index', compact('categories'));
        }
        return view('accountant.fees.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if (auth()->guard('web')->check()) {
            $data['school_id'] = auth()->guard('web')->user()->role === 'admin' ? auth()->guard('web')->id() : auth()->guard('web')->user()->school_id;
        } elseif (auth()->guard('accountant')->check()) {
            $data['school_id'] = auth()->guard('accountant')->user()->school_id;
        }

        FeeCategory::create($data);

        if ($request->routeIs('admin.*')) {
            return redirect()->route('admin.fees.categories.index')->with('success', 'Fee Category created successfully.');
        }
        return redirect()->route('accountant.fees.categories.index')->with('success', 'Fee Category created successfully.');
    }

    public function update(Request $request, FeeCategory $feeCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $feeCategory->update($request->all());

        if ($request->routeIs('admin.*')) {
            return redirect()->route('admin.fees.categories.index')->with('success', 'Fee Category updated successfully.');
        }
        return redirect()->route('accountant.fees.categories.index')->with('success', 'Fee Category updated successfully.');
    }

    public function destroy(Request $request, FeeCategory $feeCategory)
    {
        $feeCategory->delete();
        if ($request->routeIs('admin.*')) {
            return redirect()->route('admin.fees.categories.index')->with('success', 'Fee Category deleted successfully.');
        }
        return redirect()->route('accountant.fees.categories.index')->with('success', 'Fee Category deleted successfully.');
    }
}
