<x-layout title="Tambah Buku">
    <h3 class="text-del mb-3">Tambah Buku</h3>
    <form action="{{ route('buku.store') }}" method="POST" class="card card-body shadow-sm">
        @csrf
        @include('buku._form')
    </form>
</x-layout>