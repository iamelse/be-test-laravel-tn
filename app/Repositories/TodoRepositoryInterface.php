<?php

namespace App\Repositories;

use App\Models\Todo;

interface TodoRepositoryInterface {
    public function create(array $data): Todo;
    public function findById(int $id): ?Todo;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getFiltered(array $filters);
    public function getChartSummary(string $type): array;
}
