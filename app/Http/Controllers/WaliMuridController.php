<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\WaliMurid;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WaliMuridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = WaliMurid::with('siswa');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_ayah', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%")
                    ->orWhere('nama_wali', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('pekerjaan_ayah', 'like', "%{$search}%")
                    ->orWhere('pekerjaan_ibu', 'like', "%{$search}%")
                    ->orWhere('pekerjaan_wali', 'like', "%{$search}%");
            });
        }
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage);
        if ($paginator && is_object($paginator) && method_exists($paginator, 'through')) {
            $wali_murid = $paginator->through(function ($item) {
                return [
                    'id'                => $item->id,
                    'nama_ayah'         => $item->nama_ayah,
                    'nama_ibu'          => $item->nama_ibu,
                    'nama_wali'         => $item->nama_wali ?? '-',
                    'no_hp'             => $item->no_hp ?? '-',
                    'pekerjaan_ayah'    => $item->pekerjaan_ayah,
                    'pekerjaan_ibu'     => $item->pekerjaan_ibu,
                    'pekerjaan_wali'    => $item->pekerjaan_wali ?? '-',
                    'alamat'            => $item->alamat ?? '-',
                    'jumlah_anak'       => $item->siswa->count(),
                ];
            });
        } else {
            $wali_murid = collect([]);
        }
        $breadcrumbs = [
            ['label' => 'Manage Wali Murid']
        ];
        $title = 'Manage Wali Murid';

        return view('wali-murid.index', compact('wali_murid', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya tampilkan siswa yang belum memiliki wali
        $siswa = Siswa::whereNull('wali_murid_id')->get();

        $breadcrumbs = [
            ['label' => 'Manage Wali Murid', 'url' => role_route('wali-murid.index')],
            ['label' => 'Create Wali Murid'],
        ];

        $title = 'Create Wali Murid';

        return view('wali-murid.create', compact('breadcrumbs', 'title', 'siswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'exists:siswas,id',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'pekerjaan_ayah' => 'nullable|string',
            'pekerjaan_ibu' => 'nullable|string',
            'pekerjaan_wali' => 'nullable|string',
        ]);
        // Simpan data wali murid
        $wali = WaliMurid::create([
            'nama_ayah' => $validated['nama_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'nama_wali' => $validated['nama_wali'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
            'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
        ]);
        // Update semua siswa yang dipilih
        Siswa::whereIn('id', $validated['siswa_id'])->update([
            'wali_murid_id' => $wali->id
        ]);
        return redirect()->to(role_route('wali-murid.index'))->with('success', 'Wali murid berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $wali_murid = WaliMurid::with('siswa')->findOrFail($id);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $siswaQuery = Siswa::where('wali_murid_id', $wali_murid->id);
        $totalCount = $siswaQuery->count();
        if (!empty($search)) {
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nipd', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        $siswa = $siswaQuery->paginate($perPage);
                $breadcrumbs = [
            ['label' => 'Manage Wali Murid', 'url' => role_route('wali-murid.index')],
            ['label' => 'Detail Wali Murid'],
        ];
        $title = 'Detail Wali Murid';
        return view('wali-murid.show', compact( 'wali_murid', 'siswa', 'breadcrumbs', 'title', 'totalCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $wali_murid = WaliMurid::findOrFail($id);
        $siswa = Siswa::all();

        $breadcrumbs = [
            ['label' => 'Manage Wali Murid', 'url' => role_route('wali-murid.index')],
            ['label' => 'Edit Wali Murid'],
        ];

        $title = 'Edit Wali Murid';

        return view('wali-murid.edit', compact('wali_murid', 'siswa', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $wali_murid = WaliMurid::findOrFail($id);
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
            'no_hp'      => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/',
            ],
            'alamat'          => 'nullable|string',
            'pekerjaan_ayah'  => 'nullable|string',
            'pekerjaan_ibu'   => 'nullable|string',
            'pekerjaan_wali'  => 'nullable|string',
        ]);
        // Format nomor HP wali
        if (!empty($validated['no_hp'])) {
            $noHp = preg_replace('/[^0-9]/', '', $validated['no_hp']);
            if (str_starts_with($noHp, '0')) {
                $noHp = '62' . substr($noHp, 1);
            }
            $validated['no_hp'] = $noHp; // disimpan ke kolom `no_hp` di DB
        }
        $wali_murid->update($validated);
        return redirect()->route('wali-murid.index')->with('success', 'Data wali murid berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $wali_murid = WaliMurid::findOrFail($id);
            $wali_murid->delete();

            return redirect()->to(role_route('wali-murid.index'))->with('success', 'Data wali murid berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('wali-murid.index'))->with('error', 'Gagal menghapus data.');
        }
    }
}
