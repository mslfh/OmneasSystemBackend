<?php

namespace App\Services;

use App\Contracts\PrintTemplateContract;
use Illuminate\Http\JsonResponse;

class PrintTemplateService
{
    protected $printTemplateRepository;

    public function __construct(PrintTemplateContract $printTemplateRepository)
    {
        $this->printTemplateRepository = $printTemplateRepository;
    }

    public function getAll()
    {
        return $this->printTemplateRepository->getAll();
    }

    public function findById($id)
    {
        return $this->printTemplateRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->printTemplateRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->printTemplateRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->printTemplateRepository->delete($id);
    }

    public function getActiveTemplates()
    {
        return $this->printTemplateRepository->getActiveTemplates();
    }
}
