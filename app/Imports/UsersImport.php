<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        try {
            if (empty(array_filter($row))) {
                Log::info('Skipping empty row: ' . json_encode($row));
                return null;
            }

            if (empty($row['full_name'])) {
                Log::warning('Skipping row due to missing name: ' . json_encode($row));
                return null;
            }

            return new User([
                'name' => $row['full_name'],
                'first_name' => $row['last_name'] ?? null,
                'last_name' => $row['first_name'] ?? null,
                'email' => $row['email'] ?? null,
                'password' => Hash::make($row['mobile_number'] ?? '12345abc'),
                'phone' => $row['mobile_number'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing row: ' . json_encode($row) . ' - ' . $e->getMessage());
            return null;
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
