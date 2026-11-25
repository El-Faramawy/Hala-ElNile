<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Conversion;
use Illuminate\Database\Eloquent\Builder;

class ConversionRepository
{
    public function __construct(protected Conversion $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->with('toTreasury')->latest('id');
    }

    public function findById(int $id): ?Conversion
    {
        return $this->model->with(['toTreasury', 'revenues', 'expenses'])->find($id);
    }

    public function create(array $data): Conversion
    {
        return $this->model->create($data);
    }

    public function delete(Conversion $conversion): bool
    {
        return (bool) $conversion->delete();
    }
}
