<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">Pilih Role</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('select.role.submit') }}">
            @csrf
            <div class="mb-4">
                <label for="role">Pilih Role yang ingin digunakan:</label>
                <select name="role" id="role" class="mt-1 block w-full">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Lanjutkan
            </button>
        </form>
    </div>
</x-app-layout>
