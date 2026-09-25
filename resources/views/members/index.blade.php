<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Anggota</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        .success { background: #d1fae5; color: #065f46; padding: 10px 14px; border-radius: 4px; margin-top: 16px; }
        .btn { display: inline-block; padding: 6px 14px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        form.inline { display: inline; }
        .search-box { margin-top: 16px; margin-bottom: 16px; }
        .search-box input { padding: 6px 10px; width: 260px; border: 1px solid #ccc; border-radius: 4px; }
        .search-box button { padding: 6px 14px; }
    </style>
</head>
<body>
    <h1>Daftar Anggota</h1>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <p><a href="{{ route('members.create') }}" class="btn">+ Tambah Anggota</a></p>

    <form method="GET" action="{{ route('members.index') }}" class="search-box">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota...">
        <button type="submit" class="btn">Cari</button>
        @if (request('search'))
            <a href="{{ route('members.index') }}" class="btn" style="background: #6b7280;">Reset</a>
        @endif
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nama</th><th>NIM</th><th>Email</th>
                <th>No. Telepon</th><th>Alamat</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $member)
                <tr>
                    <td>{{ $member['id'] }}</td>
                    <td>{{ $member['nama'] }}</td>
                    <td>{{ $member['nim'] }}</td>
                    <td>{{ $member['email'] }}</td>
                    <td>{{ $member['nomor_telepon'] }}</td>
                    <td>{{ $member['alamat'] }}</td>
                    <td>{{ ucfirst($member['status']) }}</td>
                    <td>
                        <a href="{{ route('members.show', $member['id']) }}">Detail</a> |
                        <a href="{{ route('members.edit', $member['id']) }}">Edit</a> |
                        <form class="inline" action="{{ route('members.destroy', $member['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada data anggota.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $members->appends(request()->query())->links() }}
</body>
</html>