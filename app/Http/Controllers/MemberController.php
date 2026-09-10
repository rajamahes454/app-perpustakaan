<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private array $members = [
        ['id' => 1, 'nama' => 'Ahmad Fadli', 'nim' => '3125600101', 'email' => 'ahmad.fadli@pens.ac.id', 'nomor_telepon' => '081234567890', 'alamat' => 'Surabaya', 'status' => 'aktif'],
        ['id' => 2, 'nama' => 'Siti Nur Aisyah', 'nim' => '3125600102', 'email' => 'siti.aisyah@pens.ac.id', 'nomor_telepon' => '081234567891', 'alamat' => 'Sidoarjo', 'status' => 'aktif'],
        ['id' => 3, 'nama' => 'Budi Santoso', 'nim' => '3125600103', 'email' => 'budi.santoso@pens.ac.id', 'nomor_telepon' => '081234567892', 'alamat' => 'Gresik', 'status' => 'nonaktif'],
    ];

    public function index()
    {
        $members = $this->members;

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        return redirect()->route('members.index')
            ->with('success', "Anggota \"{$validated['nama']}\" berhasil ditambahkan (data dummy, belum tersimpan ke database).");
    }

    public function show(string $id)
    {
        return "MemberController@show, id: {$id}";
    }

    public function edit(string $id)
    {
        return "MemberController@edit, id: {$id}";
    }

    public function update(Request $request, string $id)
    {
        return "MemberController@update, id: {$id}";
    }

    public function destroy(string $id)
    {
        return "MemberController@destroy, id: {$id}";
    }
}