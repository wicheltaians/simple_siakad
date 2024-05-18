<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{
    public function tampilData(string $id):View {

        return view('user.profile',[
        'user' => User::findOrFail($id)
        ]);
    }

    public function index(): View
    {
       $dataUser = User::latest()->paginate(10);
       return view('user.index',compact('dataUser'));
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(Request $request): RedirectResponse
    {
       
        //validate form
        $request->validate([
            'username'      => 'required|min:5|unique:users,username',
            'email'         => 'required|min:5|unique:users,email|email',
            'password'      => 'required|min:5',
            'level'         => 'required'
        ]);

        User::create([
            'username'          => $request->username,
            'email'             => $request->email,
            'password'          => bcrypt($request->password), 
            'level'             => $request->level,
        ]);
        //redirect to index
        return redirect()->route('pengguna.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $pengguna = User::findOrFail($id);

        return view('user.show', compact('pengguna'));
    }

    public function edit(string $id): View
    {
        $dataUser = User::findOrFail($id);

        return view('user.edit', compact('dataUser'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $request->validate([
            'username'      => 'required|min:5',
            'email'         => 'required|min:5',
            'password'      => 'required|min:5',
            'level'         => 'required'
        ]);

        $dataUser = User::findOrFail($id);
        $dataUser->update([
                'username'  => $request->username,
                'email'     => $request->email,
                'password'  => md5($request->password),
                'level'     => $request->level
            ]);

        return redirect()->route('pengguna.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


     public function destroy($id): RedirectResponse
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


}
