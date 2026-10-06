@php $b = $buku ?? null; @endphp

@foreach([['isbn', 'ISBN'], ['judul', 'Judul'], ['penulis', 'Penulis'], ['penerbit', 'Penerbit']] as [$field, $label])
    <div class="mb-3">
        <label class="form-label">{{ $label }}</label>
        <input type="text" name="{{ $field }}" value="{{ old($field, $b?->$field) }}"
               class="form-control @error($field) is-invalid @enderror">
        @error($field)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endforeach

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $b?->tahun_terbit) }}"
               class="form-control @error('tahun_terbit') is-invalid @enderror">
        @error('tahun_terbit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" value="{{ old('stok', $b?->stok ?? 0) }}"
               class="form-control @error('stok') is-invalid @enderror">
        @error('stok')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_id', $b?->kategori_id) == $k->id)>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Sinopsis</label>
    <textarea name="sinopsis" rows="4" class="form-control @error('sinopsis') is-invalid @enderror">{{ old('sinopsis', $b?->sinopsis) }}</textarea>
    @error('sinopsis')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-del">Simpan</button>
<a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>