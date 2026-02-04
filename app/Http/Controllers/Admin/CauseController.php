<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use Illuminate\Http\Request;

class CauseController extends Controller
{
    public function index()
    {
        return redirect()->route('causes.index');
    }

    public function create()
    {
        return redirect()->route('causes.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Cause::create($validated);

        return redirect()->route('causes.index')
            ->with('status', 'Cause created successfully.');
    }

    public function edit(Cause $cause)
    {
        return redirect()->route('causes.index');
    }

    public function update(Request $request, Cause $cause)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $cause->update($validated);

        return redirect()->route('causes.index')
            ->with('status', 'Cause updated successfully.');
    }

    public function destroy(Cause $cause)
    {
        $cause->delete();

        return redirect()->route('causes.index')
            ->with('status', 'Cause deleted successfully.');
    }
}
