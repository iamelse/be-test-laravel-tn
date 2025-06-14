<?php

namespace App\Http\Controllers\API;

use App\Exports\TodosExport;
use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Todo::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'assignee' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'nullable|numeric',
            'status' => 'nullable|in:pending,open,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $todo = Todo::create([
            'title' => $request->title,
            'assignee' => $request->assignee,
            'due_date' => $request->due_date,
            'time_tracked' => $request->time_tracked ?? 0,
            'status' => $request->status ?? 'pending',
            'priority' => $request->priority
        ]);

        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Todo::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update($request->all());
        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Todo::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function export(Request $request)
    {
        return Excel::download(new TodosExport($request), 'todos.xlsx');
    }

    public function chart(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'status') {
            return response()->json(['status_summary' => Todo::groupBy('status')->selectRaw('status, count(*) as total')->pluck('total', 'status')]);
        }

        if ($type === 'priority') {
            return response()->json(['priority_summary' => Todo::groupBy('priority')->selectRaw('priority, count(*) as total')->pluck('total', 'priority')]);
        }

        if ($type === 'assignee') {
            $summary = [];
            foreach (Todo::select('assignee')->distinct()->pluck('assignee') as $name) {
                $summary[$name] = [
                    'total_todos' => Todo::where('assignee', $name)->count(),
                    'total_pending_todos' => Todo::where('assignee', $name)->where('status', 'pending')->count(),
                    'total_timetracked_completed_todos' => Todo::where('assignee', $name)->where('status', 'completed')->sum('time_tracked'),
                ];
            }
            return response()->json(['assignee_summary' => $summary]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }
}
