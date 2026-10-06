<x-layout title="Edit Buku">
    <h3 class="text-del mb-3">Edit Buku</h3>
    <form action="{{ route('buku.update', $buku) }}" method="POST" class="card card-body shadow-sm">
        @csrf
        @method('PUT')
        @include('buku._form')
    </form>
</x-layout>