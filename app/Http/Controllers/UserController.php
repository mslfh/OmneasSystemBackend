<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->getAllUsers());
    }

    public function findByField(Request $request)
    {
        $search = $request->input('search');
        return response()->json($this->userService->findByField($search));
    }

    public function show($id)
    {
        return response()->json($this->userService->getUserById($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:15',
        ]);

        $data['password'] = bcrypt($data['password']);
        return response()->json($this->userService->createUser($data), 201);
    }

    public function importUser(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv|max:2048',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return response()->json(['message' => 'Users imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import users: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'phone' => 'nullable|string|max:15',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return response()->json($this->userService->updateUser($id, $data));
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
