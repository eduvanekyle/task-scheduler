<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Project\StoreRequest;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::where('user_id', auth()->user()->id);

            return view('project', ['projects' => $projects->get()]);
        } catch (\Exception $e) {
            \Log::info($e);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $project = Project::with('tasks')
                ->where('id', $id)
                ->first();

            Gate::authorize('view', $project); // Use policies for added authorization

            return view('task', ['tasks' => $project->tasks, 'project' => $project]);
        } catch (\Exception $e) {
            \Log::info($e);

            return redirect()->back()->with('error', $e->getMessage());
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

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
