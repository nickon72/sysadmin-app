<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CredentialsExport;






class CredentialController extends Controller
{
    public function index()
    {
        return view('credentials.index', [
            'credentials' => Credential::all()
        ]);
    }


public function export()
{
    return Excel::download(new CredentialsExport, 'credentials.xlsx');
}



   public function edit($id)
{
    $cred = Credential::findOrFail($id);
    $cred->decrypted_password = Crypt::decryptString($cred->password_encrypted);

    return view('credentials.form', compact('cred'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required',
            'login' => 'required',
            'password' => 'required',
        ]);

        $encrypted = Crypt::encryptString($request->password);

        Credential::where('id', $id)->update([
            'service_name' => $request->service_name,
            'login' => $request->login,
            'password' => '******',
            'password_encrypted' => $encrypted,
            'connection_method' => $request->connection_method,
            'description' => $request->description,
        ]);

        return redirect()->route('credentials.index')->with('success', 'Обновлено!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required',
            'login' => 'required',
            'password' => 'required',
        ]);

        $encrypted = Crypt::encryptString($request->password);

        Credential::create([
            'service_name' => $request->service_name,
            'login' => $request->login,
            'password' => '******',
            'password_encrypted' => $encrypted,
            'connection_method' => $request->connection_method,
            'description' => $request->description,
        ]);

        return redirect()->route('credentials.index')->with('success', 'Добавлено!');
    }

    public function destroy($id)
    {
        Credential::findOrFail($id)->delete();
        return redirect()->route('credentials.index');
    }

public function show($id)
{
    $master = request('master_password');

    if ($master !== config('app.master_password')) {
        return '<span class="text-red-600">Неверный!</span>';
    }

    $cred = Credential::findOrFail($id);
    Session::put("unlocked_{$id}", true);
    $decrypted = Crypt::decryptString($cred->password_encrypted);

    return <<<HTML
    <span class="text-green-700 font-bold">$decrypted</span>
    <button type="button" onclick="hidePassword($id)" class="text-xs text-red-600 ml-2 underline">
        Скрыть
    </button>
    HTML;
}

public function hide($id)
{
    Session::forget("unlocked_{$id}");

    return <<<HTML
    ******
    <input type="password" id="master-input-$id" placeholder="мастер-пароль" class="text-xs w-24 px-1 border rounded">
    <button type="button" onclick="showPassword($id)" class="text-xs text-indigo-600 ml-1 underline">
        Показать
    </button>
    HTML;
}


}