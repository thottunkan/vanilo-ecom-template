<?php

namespace App\Imports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    /**
     * Import users from Excel.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Skip the heading row
        $rows->shift();

        foreach ($rows as $row) {
            if (empty($row[2])) {
                // Skip rows without an email
                continue;
            }
            User::updateOrCreate(
                [
                    'email' => $row[2],
                ],
                [
                    'name' => $row[1],
                    'email_verified_at' => !empty($row[3])
                        ? ExcelDate::excelToDateTimeObject($row[3])->format('Y-m-d H:i:s')
                        : null,
                    'type' => $row[4],
                    'is_active' => $row[5],
                    'role' => $row[6],
                    'first_name' => $row[7],
                    'last_name' => $row[8],
                    'phone' => $row[9],
                    'date_of_birth' => !empty($row[10])
                        ? ExcelDate::excelToDateTimeObject($row[10])->format('Y-m-d')
                        : null,
                    'gender' => $row[11],
                    'avatar' => $row[12],
                    'bio' => $row[13],
                    'language' => $row[14],
                    'currency' => $row[15],
                    'last_login_at' => !empty($row[16])
                        ? ExcelDate::excelToDateTimeObject($row[16])->format('Y-m-d H:i:s')
                        : null,
                    'last_login_ip' => $row[17],
                    'login_count' => $row[18] ?? 0,
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}