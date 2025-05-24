<?php

namespace App\Repositories;

use App\Models\UserProfile;
use App\Contracts\UserProfileContract;

class UserProfileRepository implements UserProfileContract
{
    public function all()
    {
        return UserProfile::all();
    }

    public function find($id)
    {
        return UserProfile::findOrFail($id);
    }

    public function create(array $data)
    {
        return UserProfile::create($data);
    }

    public function update($id, array $data)
    {
        $profile = UserProfile::findOrFail($id);
        $profile->update($data);
        return $profile;
    }

    public function delete($id)
    {
        $profile = UserProfile::findOrFail($id);
        $profile->delete();
        return true;
    }
}
