@foreach ($guru as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Guru" maxWidth="2xl">
        <form action="{{ role_route('guru.update', ['guru' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <x-form.input name="nama" label="Nama" :value="old('nama', $item['name'])" required />

            {{-- NUPTK --}}
            <x-form.input name="nuptk" label="NUPTK" placeholder="NUPTK" :value="old('nuptk', $item['nuptk'])" />

            {{-- NIP --}}
            <x-form.input name="nip" label="NIP" placeholder="NIP" :value="old('nip', $item['nip'])"/>

            {{-- No HP --}}
            <x-form.input name="no_hp" label="No HP" placeholder="628xxxxxxxxxx" :value="old('no_hp', $item['no_hp'])" />

            {{-- Email --}}
            <x-form.input name="email" label="Email" type="email" :value="old('email', $item['email'])" required />

            {{-- Alamat --}}
            {{-- <x-form.textarea label="Alamat" name="alamat" placeholder="Masukkan alamat" rows="4"
                :value="$item['alamat'] ?? ''" required /> --}}

            {{-- Jenis Kelamin --}}
            {{-- <x-form.select
                label="Jenis Kelamin"
                name="jenis_kelamin"
                :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']"
                placeholder="Pilih jenis kelamin"
                :selected="old('jenis_kelamin', $item['jenis_kelamin'] ?? null)"
                :searchable="true"
                required
            />

            {{-- Status --}}
            <x-form.select
                label="Status"
                name="status"
                :options="[
                    'Aktif' => 'Aktif',
                    'Pensiun' => 'Pensiun',
                    'Mutasi' => 'Mutasi',
                    'Resign' => 'Resign'
                ]"
                placeholder="Pilih status"
                :selected="old('status', $item['status'])"
                required
            />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
