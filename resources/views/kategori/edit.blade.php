<x-layout title="Edit Kategori">
    <h3 class="text-del mb-3">Edit Kategori</h3>
    <form action="{{ route('kategori.update', $kategori) }}" method="POST" class="card card-body shadow-sm">
        @csrf
        @method('PUT')
        @include('kategori._form')
    </form>
</x-layout>