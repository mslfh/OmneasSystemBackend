<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Services\AttributeService;
use Illuminate\Http\Request;

class AttributesController extends BaseController
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->query('start', 0);
        $count = $request->query('count', 10);
        $filter = $request->query('filter', null);
        $selected = $request->query('selected', null);
        $sortBy = $request->query('sortBy', 'id');
        $descending = $request->query('descending', false);

        $filter = $filter ? json_decode($filter, true) : null;
        $selected = $selected ? json_decode($selected, true) : null;

        $attributes = $this->attributeService->getPaginatedAttributes($start, $count, $filter, $sortBy, $descending, $selected);

        return response()->json([
            'rows' => $attributes['data'],
            'total' => $attributes['total'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is typically used for returning forms in web applications
        // For API, you can return validation rules or empty response
        return $this->sendResponse([], 'Create form data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $attribute = $this->attributeService->createAttribute($request->all());
            return $this->sendResponse($attribute, 'Attribute created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error creating attribute', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $attribute = $this->attributeService->getAttributeById($id);
            if (!$attribute) {
                return $this->sendError('Attribute not found');
            }
            return $this->sendResponse($attribute, 'Attribute retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving attribute', [$e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $attribute = $this->attributeService->getAttributeById($id);
            if (!$attribute) {
                return $this->sendError('Attribute not found');
            }
            return $this->sendResponse($attribute, 'Edit form data retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving attribute for edit', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $attribute = $this->attributeService->updateAttribute($id, $request->all());
            if (!$attribute) {
                return $this->sendError('Attribute not found');
            }
            return $this->sendResponse($attribute, 'Attribute updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error updating attribute', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $result = $this->attributeService->deleteAttribute($id);
            if (!$result) {
                return $this->sendError('Attribute not found');
            }
            return $this->sendResponse([], 'Attribute deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error deleting attribute', [$e->getMessage()]);
        }
    }

    /**
     * Get attributes by field value.
     */
    public function getByField(Request $request)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!$field || !$value) {
                return $this->sendError('Field and value parameters are required');
            }

            $attributes = $this->attributeService->findByField($field, $value);
            return $this->sendResponse($attributes, 'Attributes retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving attributes', [$e->getMessage()]);
        }
    }

    /**
     * Get active attributes.
     */
    public function getGroupAttributes()
    {
        try {
            $attributes = $this->attributeService->getGroupAttributes();
            return $this->sendResponse($attributes, 'Group attributes retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving group attributes', [$e->getMessage()]);
        }
    }

    /**
     * Check if attribute exists.
     */
    public function exists($id)
    {
        try {
            $exists = $this->attributeService->exists($id);
            return $this->sendResponse(['exists' => $exists], 'Attribute existence checked successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error checking attribute existence', [$e->getMessage()]);
        }
    }

    /**
     * Get total attribute count.
     */
    public function count()
    {
        try {
            $count = $this->attributeService->count();
            return $this->sendResponse(['count' => $count], 'Attribute count retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving attribute count', [$e->getMessage()]);
        }
    }

    /**
     * Get attribute types.
     */
    public function getAttributeType()
    {
        try {
            $types = $this->attributeService->getAttributeTypes();
            return $this->sendResponse($types, 'Attribute types retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving attribute types', [$e->getMessage()]);
        }
    }
}
