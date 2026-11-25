<?php

declare(strict_types=1);

namespace App\Services\ItalyCity;

use App\Models\ItalyCity;
use App\Repositories\ItalyCityRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ItalyCityService
{
    use UploadTrait;

    public function __construct(protected ItalyCityRepository $italyCityRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->italyCityRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ItalyCity $city): string => tableCheckbox($city->id))
            ->addColumn('image_view', function (ItalyCity $city): string {
                $imageUrl = $city->image ? (file_exists(public_path($city->image)) ? asset($city->image) : asset('storage/'.$city->image)) : asset('Site/assets/images/hero/slide-travelers-italy.jpg');

                return '<a href="'.$imageUrl.'" target="_blank" class="table-img-link">
                            <img src="'.$imageUrl.'" class="rounded shadow-sm" style="width: 48px; height: 38px; object-fit: cover;" alt="'.$city->name.'">
                        </a>';
            })
            ->addColumn('landmarks_count_badge', function (ItalyCity $city): string {
                return '<span class="badge badge-light border text-primary font-weight-bold px-2 py-1">'.($city->landmarks_count ?? 0).' معلم/منطقة</span>';
            })
            ->addColumn('status', fn (ItalyCity $city): string => tableStatus((bool) $city->is_active))
            ->addColumn('action', fn (ItalyCity $city): string => tableAction($city->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ItalyCity
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'italy_cities');
        }

        return $this->italyCityRepository->create($data);
    }

    public function update(ItalyCity $city, array $data): bool
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'italy_cities', $city->image);
        }

        return $this->italyCityRepository->update($city, $data);
    }

    public function delete(ItalyCity $city): bool
    {
        if ($city->image) {
            $this->uploadImage(null, 'italy_cities', $city->image);
        }

        return $this->italyCityRepository->delete($city);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->italyCityRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, ItalyCity>
     */
    public function getAllActive(): Collection
    {
        return $this->italyCityRepository->getAllActive();
    }
}
