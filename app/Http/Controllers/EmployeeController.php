<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $totalBaseSalary = $employees->sum('base_salary');
        $totalAllowance = $employees->sum('allowance');
        $totalAll = $employees->sum(fn($e) => $e->base_salary + $e->allowance);

        return view('employees.index', compact('employees', 'totalBaseSalary', 'totalAllowance', 'totalAll'));
    }
}
