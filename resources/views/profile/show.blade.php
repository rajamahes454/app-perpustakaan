@extends('layouts.app')

@section('title', 'Profil Petugas')

@section('content')
    <h1>Profil Petugas</h1>

    <table style="max-width: 600px; margin-bottom: 30px;">
        <tr>
            <th style="width: 160px; background: #f3f4f6;">Nama</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Role</th>
            <td>{{ ucfirst($user->role) }}</td>
        </tr>
    </table>

    <h2>Ganti Password</h2>

    <form action="{{ route('profile.password.update') }}" method="POST" style="max-width: 400px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label for="current_password" style="display: block; font-weight: bold; margin-bottom: 4px;">Password Lama</label>
            <input type="password" name="current_password" id="current_password" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            @error('current_password')
                <div style="color: #b91c1c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 14px;">
            <label for="password" style="display: block; font-weight: bold; margin-bottom: 4px;">Password Baru</label>
            <input type="password" name="password" id="password" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            @error('password')
                <div style="color: #b91c1c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password_confirmation" style="display: block; font-weight: bold; margin-bottom: 4px;">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <button type="submit" class="btn">Perbarui Password</button>
    </form>
@endsection
