<?php

namespace App\Services;

use App\Models\Todo;
use App\Repositories\TodoRepositoryInterface;

class TodoService {
    public function __construct(protected TodoRepositoryInterface $repository) {}

    public function createTodo(array $data): Todo {
        return $this->repository->create($data);
    }

    public function getById(int $id): ?Todo
    {
        return $this->repository->findById($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTodos(array $filters) {
        return $this->repository->getFiltered($filters);
    }

    public function getChart(string $type): array {
        return $this->repository->getChartSummary($type);
    }
}
