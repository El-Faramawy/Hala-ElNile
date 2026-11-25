<?php

declare(strict_types=1);

namespace App\Services\Traveler;

use App\Models\Traveler;
use App\Repositories\TravelerRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
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
            ->addColumn('landmark_preview', function (Traveler $traveler): string {
                $img = $traveler->effective_landmark_image;
                $url = (file_exists(public_path($img)) || str_starts_with($img, 'Site/')) ? asset($img) : asset('storage/'.$img);

                return '<a href="'.$url.'" target="_blank" class="table-img-link">
                            <img src="'.$url.'" class="rounded shadow-sm" style="width: 48px; height: 38px; object-fit: cover;" alt="معلم إيطالي">
                        </a>';
            })
            ->addColumn('location_formatted', function (Traveler $traveler): string {
                return '<span class="badge badge-light border text-dark px-2 py-1"><i class="fa fa-map-marker text-danger mr-1"></i>'.htmlspecialchars($traveler->effective_location).'</span>';
            })
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

    /**
     * @return Collection<int, Traveler>
     */
    public function getAllActive(): Collection
    {
        return $this->repository->getAllActive();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function searchApplicants(string $term): array
    {
        return $this->repository->searchApplicants($term);
    }
}
