<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employees.index', [
            'employees' => Employee::with('phones')->get()
        ]);
    }

    public function create()
    {
        return view('employees.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'position' => 'required',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Сотрудник добавлен!');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.form', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required',
            'position' => 'required',
        ]);

        Employee::findOrFail($id)->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Обновлено!');
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('employees.index')->with('success', 'Удалено!');
    }


public function export()
{
    return Excel::download(new EmployeesExport, 'employees.xlsx');
}

}