<?php

namespace App\Exports;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TodosExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request) { $this->request = $request; }

    public function collection()
    {
        $q = Todo::query();
        Log::info('--- Export Todos Requested ---');
        Log::info('Request Data:', $this->request->all());

        // Step 0: Data awal
        Log::info('Initial total data (no filter): ' . Todo::count());

        // Step 1: Filter Title
        if ($t = $this->request->title) {
            $q->where('title', 'like', "%$t%");
            Log::info("Filter Title: %{$t}% → Count: " . $q->count());
        }

        // Step 2: Filter Assignee
        if ($a = $this->request->assignee) {
            $assignees = explode(',', $a);
            $q->whereIn('assignee', $assignees);
            Log::info('Filter Assignee IN: ' . implode(', ', $assignees) . ' → Count: ' . $q->count());
        }

        // Step 3: Filter Date Range
        if ($this->request->start && $this->request->end) {
            $q->whereBetween('due_date', [$this->request->start, $this->request->end]);
            Log::info("Filter Due Date Between: {$this->request->start} - {$this->request->end} → Count: " . $q->count());
        }

        // Step 4: Filter Time Tracked
        if (!is_null($this->request->min) && !is_null($this->request->max)) {
            $q->whereBetween('time_tracked', [$this->request->min, $this->request->max]);
            Log::info("Filter Time Tracked Between: {$this->request->min} - {$this->request->max} → Count: " . $q->count());
        }

        // Step 5: Filter Status
        if ($s = $this->request->status) {
            $statuses = explode(',', $s);
            $q->whereIn('status', $statuses);
            Log::info('Filter Status IN: ' . implode(', ', $statuses) . ' → Count: ' . $q->count());
        }

        // Step 6: Filter Priority
        if ($p = $this->request->priority) {
            $priorities = explode(',', $p);
            $q->whereIn('priority', $priorities);
            Log::info('Filter Priority IN: ' . implode(', ', $priorities) . ' → Count: ' . $q->count());
        }

        Log::info('Final SQL: ' . $q->toSql());
        Log::info('Bindings: ', $q->getBindings());

        $data = $q->get(['title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority']);
        Log::info('Final Result Count: ' . $data->count());

        return $data;
    }

    public function headings(): array
    {
        return ['title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority'];
    }
}
