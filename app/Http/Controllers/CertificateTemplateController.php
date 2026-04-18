<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Models\CertificateType;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = CertificateTemplate::with('type')->orderBy('is_active', 'desc')->get();
        return view('admin.certificates.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = CertificateType::all();
        return view('admin.certificates.templates.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_id' => 'required|exists:certificate_types,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'footer_left' => 'nullable|string|max:255',
            'footer_right' => 'nullable|string|max:255',
        ]);

        CertificateTemplate::create($request->all());

        return redirect()->route('admin.certificates.templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = CertificateTemplate::findOrFail($id);
        $types = CertificateType::all();
        return view('admin.certificates.templates.edit', compact('template', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type_id' => 'required|exists:certificate_types,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'footer_left' => 'nullable|string|max:255',
            'footer_right' => 'nullable|string|max:255',
        ]);

        $template = CertificateTemplate::findOrFail($id);
        $template->update($request->all());

        return redirect()->route('admin.certificates.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id)
    {
        $template = CertificateTemplate::findOrFail($id);
        $template->is_active = !$template->is_active;
        $template->save();

        return back()->with('success', 'Template status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = CertificateTemplate::findOrFail($id);
        $template->delete();

        return back()->with('success', 'Template deleted successfully.');
    }
}
