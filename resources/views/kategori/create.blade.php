<x-layout title="Tambah Kategori">
    <h3 class="text-del mb-3">Tambah Kategori</h3>
    <form action="{{ route('kategori.store') }}" method="POST" class="card card-body shadow-sm">
        @csrf
        @include('kategori._form')
    </form>
</x-layout>