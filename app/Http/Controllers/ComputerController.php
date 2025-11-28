<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Imports\ComputersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Jobs\PingComputerJob;


class ComputerController extends Controller
{


  public function index()
{
    $computers = Computer::select('id', 'name', 'ip', 'employee_name', 'location', 'status', 'ping_time')->get();
    return view('computers.index', compact('computers'));
}


public function pingAll()
{
    foreach (Computer::all() as $c) {
        PingComputerJob::dispatch($c);
    }
    return response()->json('ok');
}


public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt'
    ]);

    Excel::import(new ComputersImport, $request->file('file'));

    return back()->with('success', '100 ПК импортировано! Пинг начат...');
}

public function edit($id)
{
    $computer = Computer::findOrFail($id);
    return view('computers.edit', compact('computer'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'ip' => 'required|ip|unique:computers,ip,' . $id,
        'employee_name' => 'nullable',
        'location' => 'nullable',
    ]);

    $computer = Computer::findOrFail($id);
    $computer->update($request->all());

    return redirect()->route('computers.index')->with('success', 'ПК обновлён!');
}


    public function create()
{
    return view('computers.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'ip' => 'required|ip|unique:computers',
        'employee_name' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
    ]);

    Computer::create($request->all());

    return redirect()->route('computers.index')->with('success', 'ПК добавлен!');
}

    public function destroy($id)
    {
        Computer::findOrFail($id)->delete();
        return redirect()->route('computers.index')->with('success', 'Удалено!');
    }
}