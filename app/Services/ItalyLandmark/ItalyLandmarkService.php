<?php

declare(strict_types=1);

namespace App\Services\ItalyLandmark;

use App\Models\ItalyLandmark;
use App\Repositories\ItalyLandmarkRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ItalyLandmarkService
{
    use UploadTrait;

    public function __construct(protected ItalyLandmarkRepository $italyLandmarkRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->italyLandmarkRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ItalyLandmark $landmark): string => tableCheckbox($landmark->id))
            ->addColumn('city_name', function (ItalyLandmark $landmark): string {
                return '<span class="badge badge-light border text-dark px-2 py-1"><i class="fa fa-map-marker text-danger mr-1"></i>'.($landmark->city?->name ?? '-').'</span>';
            })
            ->addColumn('image_view', function (ItalyLandmark $landmark): string {
                $imageUrl = $landmark->image ? (file_exists(public_path($landmark->image)) ? asset($landmark->image) : asset('storage/'.$landmark->image)) : asset('Site/assets/images/hero/slide-travelers-italy.jpg');

                return '<a href="'.$imageUrl.'" target="_blank" class="table-img-link">
                            <img src="'.$imageUrl.'" class="rounded shadow-sm" style="width: 52px; height: 38px; object-fit: cover;" alt="'.$landmark->name.'">
                        </a>';
            })
            ->addColumn('status', fn (ItalyLandmark $landmark): string => tableStatus((bool) $landmark->is_active))
            ->addColumn('action', fn (ItalyLandmark $landmark): string => tableAction($landmark->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ItalyLandmark
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'italy_landmarks');
        }

        return $this->italyLandmarkRepository->create($data);
    }

    public function update(ItalyLandmark $landmark, array $data): bool
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'italy_landmarks', $landmark->image);
        }

        return $this->italyLandmarkRepository->update($landmark, $data);
    }

    public function delete(ItalyLandmark $landmark): bool
    {
        if ($landmark->image) {
            $this->uploadImage(null, 'italy_landmarks', $landmark->image);
        }

        return $this->italyLandmarkRepository->delete($landmark);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->italyLandmarkRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, ItalyLandmark>
     */
    public function getAllActive(?int $cityId = null): Collection
    {
        return $this->italyLandmarkRepository->getAllActive($cityId);
    }
}
