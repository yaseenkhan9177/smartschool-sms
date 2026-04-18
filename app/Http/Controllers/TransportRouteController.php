<?php

namespace App\Http\Controllers;

use App\Models\TransportRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportRouteController extends Controller
{
    public function index()
    {
        $routes = TransportRoute::latest()->get();
        return view('admin.transport.routes.index', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_name' => 'required|string|max:255',
            'monthly_fee' => 'required|numeric|min:0',
        ]);

        TransportRoute::create([
            'route_name' => $request->route_name,
            'monthly_fee' => $request->monthly_fee,
            'school_id' => Auth::user()->school_id ?? 1, // Default or Auth
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Transport route created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'route_name' => 'required|string|max:255',
            'monthly_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $route = TransportRoute::findOrFail($id);
        $route->update([
            'route_name' => $request->route_name,
            'monthly_fee' => $request->monthly_fee,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Transport route updated successfully.');
    }

    public function destroy($id)
    {
        $route = TransportRoute::findOrFail($id);
        $route->delete(); // Or soft delete if model uses SoftDeletes
        return redirect()->back()->with('success', 'Transport route deleted successfully.');
    }
}
