<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Requests\Task\ReorderRequest;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::where('user_id', auth()->user()->id);

            return view('task', ['tasks' => $tasks->orderBy('priority', 'asc')->get()]);
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreRequest $request, $projectId)
    {
        try {
            $userId = auth()->user()->id;

            $project = Project::find($projectId);

            Gate::authorize('create', $project);

            $tasks = Task::where('project_id', $project->id);

            $latest = $tasks->max('priority') ?? 0; // latest is zero if there are no tasks

            $existing = $tasks->where('name', $request->name);

            if ($existing->exists()) {
                return redirect()->back()->with('error', 'Task already exists!');
            }

            Task::create([
                'project_id' => $project->id,
                'name' => $request->name,
                'priority' => $latest + 1
            ]);

            return redirect()->back()->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $task = Task::find($id);

            if (!isset($task)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task not found!'
                ], 404);
            }

            $project = Project::find($request->project_id);

            Gate::authorize('update', $project);

            $tasks = Task::where('project_id', $project->id)
                ->whereNot('id', $task->id);

            $existing = $tasks->where('name', $request->name);

            if ($existing->exists()) {
                return redirect()->back()->with('error', 'Task already exists!');
            }

            $task->update([
                'name' => $request->name
            ]);

            return redirect()->back()->with('success', 'Task updated successfully!');
        } catch (\Exception $e) {
            \Log::info($e);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $task = Task::find($id);

            if (!isset($task)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task not found!'
                ], 404);
            }

            $project = Project::find($request->project_id);

            Gate::authorize('update', $project);

            Task::where('project_id', $project->id)
                ->where('priority', '>', $task->priority)
                ->decrement('priority', 1);

            $task->delete();

            return redirect()->back()->with('success', 'Task deleted successfully!');
        } catch (\Exception $e) {
            \Log::info($e);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reorder(ReorderRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            $project = Project::find($request->project_id);

            Gate::authorize('update', $project);

            $latest = Task::where('project_id', $project->id)->max('priority');

            // Get selected and targeted tasks
            $selected = Task::where('project_id', $project->id)
                ->where('priority', $request->selected_priority)
                ->first();

            $targeted = Task::where('project_id', $project->id)
                ->where('priority', $request->target_priority)
                ->first();

            if (!isset($selected) || !isset($targeted)) {
                return redirect()->back()->with('error', 'Invalid order.');
            }

            // Algorithm for modifying priority
            if ($selected->priority == 1 && $targeted->priority == $latest) {
                Task::where('project_id', $project->id)
                    ->whereBetween('priority', [1, $latest])
                    ->decrement('priority', 1);

                $selected->update([
                    'priority' => $latest
                ]);
            } else if ($selected->priority == $latest && $targeted->priority == 1) {
                Task::where('project_id', $project->id)
                    ->whereBetween('priority', [2, $latest])
                    ->increment('priority', 1);

                $selected->update([
                    'priority' => 1
                ]);
            } else {
                if ($selected->priority < $targeted->priority) {
                    $operation = 'decrement';
                    $higher = $targeted->priority;
                    $lower = $selected->priority;
                } else {
                    $operation = 'increment';
                    $higher = $selected->priority;
                    $lower = $targeted->priority;
                }

                Task::where('project_id', $project->id)
                    ->whereBetween('priority', [$lower, $higher])
                    ->$operation('priority', 1); // Increment or decrement

                $selected->update([
                    'priority' => $targeted->priority
                ]);
            }

            return redirect()->back()->with('success', 'Reorder done.');
        } catch (\Exception $e) {
            \Log::info($e);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
