@foreach ($proyek as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Proyek" maxWidth="2xl">
        <form action="{{ role_route('p5-proyek.update', ['p5_proyek' => $item['id']], ['tab' => request('tab', 'proyek')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="tab" value="{{ request('tab', 'dimensi') }}"> --}}
            <input type="hidden" name="tab" value="proyek">

            {{-- Nama --}}
            <x-form.input
                label="Nama Proyek"
                name="nama_proyek"
                :value="old('nama_proyek', $item['nama_proyek'])"
                required />

            {{-- Deskripsi --}}
            <x-form.textarea
                label="Deskripsi Proyek"
                name="deskripsi_proyek"
                :value="old('deskripsi_proyek', $item['deskripsi_proyek'])"
                placeholder="Masukkan deskripsi proyek"
                rows="4"
                required />

            {{-- Tema --}}
            {{-- <x-form.select
                label="Tema"
                name="p5_tema_id"
                :options="$temaList"
                placeholder="Pilih Tema"
                :selected="old('p5_tema_id', $item['p5_tema_id'])"
                required /> --}}

            {{-- Tahun Semester --}}
            <x-form.select
                label="Tahun Semester"
                name="tahun_semester_id"
                :options="$tahunSemesterList"
                placeholder="Pilih Tahun Semester"
                :selected="old('tahun_semester_id', $item['tahun_semester_id'])"
                required />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
