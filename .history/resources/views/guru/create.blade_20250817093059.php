<x-modal name="form-create-guru" title="Tambah Guru" maxWidth="2xl">
    <form action="{{ role_route('guru.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf

        {{-- Nama --}}
        <x-form.input name="nama" label="Nama" required />

        {{-- NUPTK --}}
        <x-form.input name="nuptk" label="NUPTK" placeholder="NUPTK"/>

        {{-- NIP --}}
        <x-form.input name="nip" label="NIP" placeholder="NIP"/>

        {{-- No HP --}}
        <x-form.input name="no_hp" label="No HP" placeholder="628xxxxxxxxxx" />

        {{-- Email --}}
        <x-form.input label="Email" name="email" :value="old('email')" type="email"/>

        {{-- Alamat --}}
        {{-- <x-form.textarea label="Alamat Lengkap" name="alamat" placeholder="Masukkan alamat" rows="4" /> --}}

        {{-- Jenis Kelamin --}}
        {{-- <x-form.select
            label="Jenis Kelamin"
            name="jenis_kelamin"
            :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']"
            placeholder="Pilih jenis kelamin"
            :selected="old('jenis_kelamin')"
            :searchable="true"
            required
        /> --}}

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
            :selected="old('status')"
            required
        />

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
