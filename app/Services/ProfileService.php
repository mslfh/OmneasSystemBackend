<?php

namespace App\Services;

use App\Contracts\ProfileContract;
use Illuminate\Http\JsonResponse;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileContract $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getAll()
    {
        return $this->profileRepository->getAll();
    }

    public function getActiveProfiles()
    {
        $profiles = $this->profileRepository->getAll();
        $activeProfiles = $profiles->filter(function ($profile) {
            return $profile->status === 'active';
        });
        return $activeProfiles;
    }

    public function findById($id)
    {
        return $this->profileRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->profileRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->profileRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->profileRepository->delete($id);
    }

    /**
     * Get paginated profiles
     */
    public function getPaginatedProfiles($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Profile::query();

        if ($filter) {
            if ($filter['field'] == "name") {
                $query->where('name', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "description") {
                $query->where('description', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "status") {
                $query->where('status', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->where('deleted_at', '!=', null);
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['products'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
