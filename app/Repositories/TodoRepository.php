<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Support\Facades\DB;

class TodoRepository implements TodoRepositoryInterface {
    public function create(array $data): Todo {
        return Todo::create($data);
    }

    public function findById(int $id): ?Todo
    {
        return Todo::find($id);
    }

    public function update(int $id, array $data): bool
    {
        return Todo::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Todo::destroy($id) > 0;
    }

    public function getFiltered(array $filters) {
        return Todo::query()
            ->when($filters['title'] ?? null, fn($q, $v) => $q->where('title', 'like', "%$v%"))
            ->when($filters['assignee'] ?? null, fn($q, $v) => $q->whereIn('assignee', explode(',', $v)))
            ->when(($filters['start'] ?? null) && ($filters['end'] ?? null), fn($q) =>
            $q->whereBetween('due_date', [$filters['start'], $filters['end']]))
            ->when(($filters['min'] ?? null) && ($filters['max'] ?? null), fn($q) =>
            $q->whereBetween('time_tracked', [$filters['min'], $filters['max']]))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->whereIn('status', explode(',', $v)))
            ->when($filters['priority'] ?? null, fn($q, $v) => $q->whereIn('priority', explode(',', $v)))
            ->get();
    }

    public function getChartSummary(string $type): array {
        return match ($type) {
            'status' => Todo::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status')->toArray(),
            'priority' => Todo::select('priority', DB::raw('count(*) as total'))->groupBy('priority')->pluck('total', 'priority')->toArray(),
            'assignee' => Todo::select('assignee')->distinct()->get()->mapWithKeys(function ($item) {
                $todos = Todo::where('assignee', $item->assignee);
                return [$item->assignee => [
                    'total_todos' => $todos->count(),
                    'total_pending_todos' => $todos->where('status', 'pending')->count(),
                    'total_timetracked_completed_todos' => $todos->where('status', 'completed')->sum('time_tracked'),
                ]];
            })->toArray(),
        };
    }
}
