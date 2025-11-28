<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\PhonesExport;
use Maatwebsite\Excel\Facades\Excel;


class PhoneController extends Controller
{
    public function index()
    {
        return view('phones.index', [
            'phones' => Phone::with('employee')->get(),
            'employees' => Employee::pluck('full_name', 'id')
        ]);
    }

    public function create()
    {
        return view('phones.form', ['employees' => Employee::pluck('full_name', 'id')]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|unique:phones',
        ]);

        Phone::create($request->all());

        return redirect()->route('phones.index')->with('success', 'Телефон добавлен!');
    }

    public function edit($id)
    {
        $phone = Phone::findOrFail($id);
        return view('phones.form', [
            'phone' => $phone,
            'employees' => Employee::pluck('full_name', 'id')
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'phone_number' => 'required|unique:phones,phone_number,' . $id,
        ]);

        Phone::findOrFail($id)->update($request->all());

        return redirect()->route('phones.index')->with('success', 'Обновлено!');
    }

    public function destroy($id)
    {
        Phone::findOrFail($id)->delete();
        return redirect()->route('phones.index')->with('success', 'Удалено!');
    }

public function export()
{
    return Excel::download(new PhonesExport, 'phones.xlsx');
}

}