<?php

namespace App\Repositories;

use App\Contracts\SystemSettingContract;
use App\Models\SystemSetting;

class SystemSettingRepository implements SystemSettingContract
{
    public function getAll()
    {
        return SystemSetting::all();
    }
    public function getByKey($key)
    {
        return SystemSetting::where('key', $key)->first();
    }

    public function findById($id)
    {
        return SystemSetting::findOrFail($id);
    }

    public function create(array $data)
    {
        return SystemSetting::create($data);
    }

    public function update($id, array $data)
    {
        $setting = SystemSetting::findOrFail($id);
        $setting->update($data);
        return $setting;
    }

    public function delete($id)
    {
        $setting = SystemSetting::findOrFail($id);
        $setting->delete();
        return $setting;
    }
}
