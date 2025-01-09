<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::where('user_id', auth()->user()->id);

            return view('project', ['projects' => $projects->get()]);
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $project = Project::with('tasks')
                ->where('user_id', auth()->user()->id);

            // \Log::info($project->tasks);
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            $projects = Project::where('user_id', $userId);

            $existing = $projects->where('name', $request->name);

            if ($existing->exists()) {
                return redirect()->back()->with('error', 'Project already exists!');
            }

            Project::create([
                'user_id' => $userId,
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Project created successfully!');
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
