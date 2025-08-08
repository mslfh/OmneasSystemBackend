<?php

namespace App\Services;

use App\Contracts\ComboContract;

class ComboService
{
    protected $comboRepository;

    public function __construct(ComboContract $comboRepository)
    {
        $this->comboRepository = $comboRepository;
    }

    public function getAllCombos()
    {
        return $this->comboRepository->getAll();
    }

    public function getComboById($id)
    {
        return $this->comboRepository->findById($id);
    }

    public function createCombo(array $data)
    {
        return $this->comboRepository->create($data);
    }

    public function updateCombo($id, array $data)
    {
        return $this->comboRepository->update($id, $data);
    }

    public function deleteCombo($id)
    {
        return $this->comboRepository->delete($id);
    }

    public function getActiveCombos()
    {
        return $this->comboRepository->getActiveItems();
    }

    /**
     * Get paginated combos
     */
    public function getPaginatedCombos($start, $count, $filter, $sortBy, $descending, $selected)
    {
        $query = \App\Models\Combo::query();

        if ($filter) {
            if ($filter['field'] == "name") {
                $query->where('name', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "description") {
                $query->where('description', 'like', "%{$filter['value']}%");
            } else if ($filter['field'] == "price") {
                $query->where('price', '=', $filter['value']);
            }
        }

        if ($selected) {
            if ($selected['field'] == "deleted") {
                $query->where('deleted_at', '!=', null);
            } else if ($selected['field'] == "active") {
                $query->where('is_active', true);
            } else if ($selected['field'] == "featured") {
                $query->where('is_featured', true);
            }
        }

        $sortDirection = $descending ? 'desc' : 'asc';
        $query->with(['items', 'products'])->withTrashed()->orderBy($sortBy, $sortDirection);

        $total = $query->count();
        $data = $query->skip($start)->take($count)->get();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
