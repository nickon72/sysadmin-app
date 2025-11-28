<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrintersExport;


class PrinterController extends Controller
{

public function export()
{
    return Excel::download(new PrintersExport, 'printers.xlsx');
}


    public function index()
    {
        $printers = Printer::all();
        return view('printers.index', compact('printers'));
    }

    public function create()
    {
        return view('printers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ip' => 'required|ip|unique:printers,ip',
            'location' => 'nullable',
            'notes' => 'nullable',
        ]);

        Printer::create($request->all());

        return redirect()->route('printers.index')->with('success', 'Принтер добавлен!');
    }

    public function edit($id)
    {
        $printer = Printer::findOrFail($id);
        return view('printers.edit', compact('printer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'ip' => 'required|ip|unique:printers,ip,' . $id,
            'location' => 'nullable',
            'notes' => 'nullable',
        ]);

        Printer::findOrFail($id)->update($request->all());

        return redirect()->route('printers.index')->with('success', 'Обновлено!');
    }

    public function destroy($id)
    {
        Printer::findOrFail($id)->delete();
        return redirect()->route('printers.index')->with('success', 'Удалено!');
    }

public function refreshToner()
{
    \App\Jobs\UpdatePrinterTonerJob::dispatch(); // запускаем Job немедленно

    return back()->with('success', 'Обновление тонера запущено! Через 5–20 сек обновится.');
}

}