<?php

namespace App\Exports;

use App\Models\Todo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class TodosExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request) { $this->request = $request; }

    public function collection()
    {
        $q = Todo::query();

        if ($t = $this->request->title) $q->where('title', 'like', "%$t%");
        if ($a = $this->request->assignee) $q->whereIn('assignee', explode(',', $a));
        if ($start = $this->request->start && $end = $this->request->end) $q->whereBetween('due_date', [$this->request->start, $this->request->end]);
        if ($min = $this->request->min && $max = $this->request->max) $q->whereBetween('time_tracked', [$this->request->min, $this->request->max]);
        if ($s = $this->request->status) $q->whereIn('status', explode(',', $s));
        if ($p = $this->request->priority) $q->whereIn('priority', explode(',', $p));

        return $q->get(['title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority']);
    }

    public function headings(): array
    {
        return ['title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority'];
    }
}
