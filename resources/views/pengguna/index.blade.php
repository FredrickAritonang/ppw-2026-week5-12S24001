<x-layout title="Kelola Pengguna" heading="Kelola Pengguna" :crumbs="['Kelola Pengguna']">
    <div class="panel-box">
        <form method="GET" class="row g-2 mb-3">
             <div class="col-md-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau username…"></div>
             <div class="col-md-4">
                 <select name="role" class="form-select">
                     <option value="">Semua peran</option>
                     @foreach(\App\Support\Peran::DATA as $k => $d)<option value="{{ $k }}" @selected(request('role') === $k)>{{ $d['label'] }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-del w-100"><i class="bi bi-search me-1"></i>Cari</button></div>
        </form>
        <div class="table-responsive">
            <table class="tbl">
                <thead><tr><th>Nama</th><th>Username</th><th>Email</th><th>Peran</th><th>Pinjaman Aktif</th></tr></thead>
                <tbody>
                @foreach($penggunas as $u)
                    <tr>
                        <td class="fw-semibold">{{ $u->name }}</td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="tag"><i class="bi {{ $u->peran_data['icon'] }} me-1"></i>{{ $u->peran_data['label'] }}</span></td>
                        <td>{{ $u->pinjaman_aktif }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $penggunas->links() }}</div>
    </div>
</x-layout>