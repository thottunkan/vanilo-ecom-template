<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Users Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #e9ecef;
        }

        table th,
        table td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
        }

        table th {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .active {
            color: green;
            font-weight: bold;
        }

        .inactive {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <h2>Users Report</h2>

    <p>
        Generated on {{ now()->format('d M Y h:i A') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Type</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Language</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Last Login</th>
            </tr>
        </thead>

        <tbody>

            @forelse($users as $index => $user)

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->name }}
                    </td>

                    <td>{{ $user->email }}</td>

                    <td>{{ ucfirst($user->role) }}</td>

                    <td>{{ ucfirst($user->type) }}</td>

                    <td>{{ $user->phone ?? '-' }}</td>

                    <td>{{ $user->gender ?? '-' }}</td>

                    <td>{{ strtoupper($user->language) }}</td>

                    <td>{{ $user->currency }}</td>

                    <td class="text-center">
                        @if($user->is_active)
                            <span class="active">Active</span>
                        @else
                            <span class="inactive">Inactive</span>
                        @endif
                    </td>

                    <td>
                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') : '-' }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="11" class="text-center">
                        No users found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="footer">
        Total Users : {{ $users->count() }}
    </div>

</body>

</html>