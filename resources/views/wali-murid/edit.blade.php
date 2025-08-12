@foreach ($wali_murid as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Wali Murid" maxWidth="2xl">
        {{-- <form action="{{ route('mapel.update', $item['id']) }}" method="POST" --}}
        <form action="{{ role_route('wali.update', ['wali_murid' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- Nama Ayah --}}
            <x-form.input label="Nama Ayah" name="nama_ayah" :value="old('nama_ayah', $item['nama_ayah'])" required/>
            {{-- Nama Ibu --}}
            <x-form.input label="Nama Ibu" name="nama_ibu" :value="old('nama_ibu', $item['nama_ibu'])" required/>
            {{-- Nama Wali --}}
            <x-form.input label="Nama Wali" name="nama_wali" :value="old('nama_wali', $item['nama_wali'])" />
            {{-- No HP --}}
            <x-form.input label="No HP" name="no_hp" :value="old('no_hp', $item['no_hp'])" placeholder="628xxxxxxxxxx" />
            {{-- Pekerjaan Ayah --}}
            <x-form.input label="Pekerjaan Ayah" name="pekerjaan_ayah" :value="old('pekerjaan_ayah', $item['pekerjaan_ayah'])" required/>
            {{-- Pekerjaan Ibu --}}
            <x-form.input label="Pekerjaan Ibu" name="pekerjaan_ibu" :value="old('pekerjaan_ibu', $item['pekerjaan_ibu'])" required/>
            {{-- Pekerjaan Wali --}}
            <x-form.input label="Pekerjaan Wali" name="pekerjaan_wali" :value="old('pekerjaan_wali', $item['pekerjaan_wali'])" />
            {{-- Alamat --}}
            <x-form.textarea label="Alamat" name="alamat" placeholder="Masukkan alamat" rows="4"
                :value="old('alamat', $item['alamat'] ?? '')" required/>
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center
                    px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
