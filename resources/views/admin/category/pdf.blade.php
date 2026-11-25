<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Categories Report</title>

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

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 10px;
        }

        img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <h2>Categories Report</h2>

    <p>
        Generated on {{ now()->format('d M Y h:i A') }}
    </p>

    <table>
        <thead>
            <tr>
                <th width="40">#</th>
                <th width="60">Image</th>
                <th>Name</th>
                <th>Parent Category</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>

        <tbody>

            @forelse($categories as $index => $category)

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td class="text-center">
                        @if(!empty($category->image_path))
                            <img src="{{ public_path($category->image_path) }}" alt="{{ $category->name }}">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $category->name }}</td>

                    <td>{{ $category->parent?->name ?? '-' }}</td>

                    <td>
                        {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}
                    </td>

                    <td>
                        {{ $category->updated_at ? $category->updated_at->format('d M Y') : '-' }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No Categories Found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="footer">
        Total Categories : {{ $categories->count() }}
    </div>

</body>

</html>