<?php

declare(strict_types=1);

namespace App\Services\Traveler;

use App\Models\Traveler;
use App\Repositories\TravelerRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class TravelerService
{
    use UploadTrait;

    public function __construct(protected TravelerRepository $repository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->repository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Traveler $traveler): string => tableCheckbox($traveler->id))
            ->addColumn('avatar_preview', fn (Traveler $traveler): string => tableImage($traveler->avatar, $traveler->name))
            ->addColumn('landmark_preview', fn (Traveler $traveler): string => tableImage($traveler->landmark_image, $traveler->location ?? 'معلم إيطالي'))
            ->addColumn('rating_stars', fn (Traveler $traveler): string => str_repeat('<i class="fa fa-star text-warning"></i> ', (int) $traveler->rating))
            ->addColumn('status', fn (Traveler $traveler): string => tableStatus((bool) $traveler->is_active))
            ->addColumn('action', fn (Traveler $traveler): string => tableAction($traveler->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data, ?UploadedFile $landmark = null, ?UploadedFile $avatar = null): Traveler
    {
        if ($landmark) {
            $data['landmark_image'] = $this->uploadImage($landmark, 'travelers');
        }

        if ($avatar) {
            $data['avatar'] = $this->uploadImage($avatar, 'travelers');
        }

        return $this->repository->create($data);
    }

    public function update(Traveler $traveler, array $data, ?UploadedFile $landmark = null, ?UploadedFile $avatar = null): bool
    {
        if ($landmark) {
            $data['landmark_image'] = $this->uploadImage($landmark, 'travelers', $traveler->getRawOriginal('landmark_image') ?: $traveler->landmark_image);
        }

        if ($avatar) {
            $data['avatar'] = $this->uploadImage($avatar, 'travelers', $traveler->getRawOriginal('avatar') ?: $traveler->avatar);
        }

        return $this->repository->update($traveler, $data);
    }

    public function delete(Traveler $traveler): bool
    {
        return $this->repository->delete($traveler);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->repository->deleteMultiple($ids);
    }
}
