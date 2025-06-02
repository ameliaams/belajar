<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

    <style>
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-gray-300 rounded px-2 py-1;
        }

        th, td {
            white-space: nowrap;
        }

        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }

        .dt-container {
            max-height: 500px;
            overflow: auto;
        }

        table.dataTable thead th {
            position: sticky;
            top: 0;
            background-color: #f9fafb;
            z-index: 20;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold">Data Karyawan</h2>
            </div>

            <div class="dt-container">
                <table id="employee-table" class="stripe hover order-column row-border compact w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Departemen</th>
                            <th>Bergabung</th>
                            <th>Status</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $employee->avatar }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</td>
                            <td>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $employee->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->status }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($employee->allowance, 0, ',', '.') }}</td>
                            <td class="text-blue-600 font-semibold">
                                Rp {{ number_format($employee->base_salary + $employee->allowance, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="px-6 py-3 text-sm font-bold text-gray-700 bg-gray-50">Total</th>
                            <th></th> {{-- Jabatan --}}
                            <th></th> {{-- Departemen --}}
                            <th></th> {{-- Bergabung --}}
                            <th></th> {{-- Status --}}
                            <th colspan="2" class="px-6 py-3 text-sm font-bold text-gray-700 bg-gray-50 text-center">
                                Rp {{ number_format($totalBaseSalary + $totalAllowance, 0, ',', '.') }}
                            </th>
                            <th class="px-6 py-3 text-sm font-bold text-blue-700 bg-gray-50">
                                Rp {{ number_format($totalBaseSalary + $totalAllowance, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- jQuery + DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#employee-table').DataTable({
                scrollX: true,
                scrollY: '400px',
                scrollCollapse: true,
                paging: true,
                fixedColumns: {
                    leftColumns: 2
                },
            });
        });
    </script>
</body>
</html>
