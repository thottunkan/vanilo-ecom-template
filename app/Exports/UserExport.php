<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'customer')
            ->select([
                'id',
                'name',
                'email',
                'email_verified_at',
                'type',
                'is_active',
                'role',
                'first_name',
                'last_name',
                'phone',
                'date_of_birth',
                'gender',
                'avatar',
                'bio',
                'language',
                'currency',
                'last_login_at',
                'last_login_ip',
                'login_count',
                'created_at',
                'updated_at',
            ])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Email Verified At',
            'Type',
            'Status',
            'Role',
            'First Name',
            'Last Name',
            'Phone',
            'Date of Birth',
            'Gender',
            'Avatar',
            'Bio',
            'Language',
            'Currency',
            'Last Login At',
            'Last Login IP',
            'Login Count',
            'Created At',
            'Updated At',
        ];
    }
}