<?php
// app/Http/Controllers/Admin/StreamController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stream;
use App\Models\Subject;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function index()
    {
        $streams = Stream::withCount('classGroups')->get();
        $subjects = Subject::all();
        
        return view('admin.streams.index', compact('streams', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.streams.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:streams',
            'description' => 'nullable|string',
            'core_subjects' => 'required|array',
            'core_subjects.*' => 'exists:subjects,id',
            'optional_subjects' => 'nullable|array',
            'optional_subjects.*' => 'exists:subjects,id',
        ]);

        Stream::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'core_subjects' => $request->core_subjects,
            'optional_subjects' => $request->optional_subjects ?? [],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream created successfully.');
    }

    public function show(Stream $stream)
    {
        $stream->load(['classGroups.gradeLevel', 'classGroups.students']);
        return view('admin.streams.show', compact('stream'));
    }

    public function edit(Stream $stream)
    {
        $subjects = Subject::all();
        return view('admin.streams.edit', compact('stream', 'subjects'));
    }

    public function update(Request $request, Stream $stream)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:streams,code,' . $stream->id,
            'description' => 'nullable|string',
            'core_subjects' => 'required|array',
            'core_subjects.*' => 'exists:subjects,id',
            'optional_subjects' => 'nullable|array',
            'optional_subjects.*' => 'exists:subjects,id',
        ]);

        $stream->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'core_subjects' => $request->core_subjects,
            'optional_subjects' => $request->optional_subjects ?? [],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream updated successfully.');
    }

    public function destroy(Stream $stream)
    {
        if ($stream->classGroups()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete stream that has classes assigned.');
        }

        $stream->delete();

        return redirect()->route('admin.streams.index')
            ->with('success', 'Stream deleted successfully.');
    }
}