<?php

namespace App\Http\Controllers\API;

use App\Exports\TodosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\FilterTodoRequest;
use App\Http\Requests\API\StoreTodoRequest;
use App\Http\Requests\API\UpdateTodoRequest;
use App\Services\TodoService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    public function __construct(protected TodoService $service) {}

    public function store(StoreTodoRequest $request) {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'pending';
        $data['time_tracked'] = $data['time_tracked'] ?? 0;
        return response()->json($this->service->createTodo($data), 201);
    }

    public function show($id)
    {
        $todo = $this->service->getById($id);
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }
        return response()->json($todo);
    }

    public function update(UpdateTodoRequest $request, $id)
    {
        $data = $request->validated();
        $updated = $this->service->update($id, $data);

        return $updated
            ? response()->json(['message' => 'Todo updated successfully'])
            : response()->json(['message' => 'Todo not found'], 404);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);
        return $deleted
            ? response()->json(['message' => 'Todo deleted successfully'])
            : response()->json(['message' => 'Todo not found'], 404);
    }

    public function export(FilterTodoRequest $request) {
        return Excel::download(new TodosExport($request), 'todos.xlsx');
    }

    public function chart(Request $request) {
        $type = $request->query('type');
        return response()->json([$type . '_summary' => $this->service->getChart($type)]);
    }
}
