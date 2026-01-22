<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use Illuminate\Http\Request;

class CauseController extends Controller
{
    public function index()
    {
        $causes = Cause::orderByDesc('created_at')->get();

        return view('admin.causes.index', [
            'causes' => $causes,
        ]);
    }

    public function create()
    {
        return view('admin.causes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Cause::create($validated);

        return redirect()->route('admin.causes.index')
            ->with('status', 'Cause created successfully.');
    }

    public function edit(Cause $cause)
    {
        return view('admin.causes.edit', [
            'cause' => $cause,
        ]);
    }

    public function update(Request $request, Cause $cause)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $cause->update($validated);

        return redirect()->route('admin.causes.index')
            ->with('status', 'Cause updated successfully.');
    }

    public function destroy(Cause $cause)
    {
        $cause->delete();

        return redirect()->route('admin.causes.index')
            ->with('status', 'Cause deleted successfully.');
    }
}
