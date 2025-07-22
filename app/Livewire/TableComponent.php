<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $model; // Model class string, misal: App\Models\User
    public $columns = [];
    public $rowView = null;
    public $actions = ['detail' => true, 'edit' => true, 'delete' => true];
    public $filters = [];
    public $search = '';
    public $perPage = 10;
    public $sortBy = null;
    public $sortDirection = 'asc';
    public $selected = [];
    // public $showBulkActions = false; // untuk menampilkan aksi bulk seperti delete
    public $relationFilters = []; // contoh: ['role' => 'roles', 'mapel' => 'mapel']



    protected $queryString = ['search', 'perPage', 'sortBy', 'sortDirection', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function setSort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleSelect($id)
    {
        if (($key = array_search($id, $this->selected)) !== false) {
            unset($this->selected[$key]);
        } else {
            $this->selected[] = $id;
        }
    }

    public function selectAllPage($ids)
    {
        $this->selected = array_unique(array_merge($this->selected, $ids));
    }

    public function unselectAllPage($ids)
    {
        $this->selected = array_diff($this->selected, $ids);
    }

    public function bulkDelete()
    {
        $model = $this->model;
        $model::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        session()->flash('success', 'Data terpilih berhasil dihapus.');
    }

    public function render()
    {
        $model = $this->model;
        $query = $model::query();

        // Search (asumsi kolom pertama untuk search)
        if ($this->search && count($this->columns)) {
            $firstColumn = array_key_first($this->columns);
            $query->where($firstColumn, 'like', "%{$this->search}%");
        }

        // Filters
        // foreach ($this->filters as $field => $value) {
        //     if ($value !== null && $value !== '' && $value !== []) {
        //         // Jika field ada di relationFilters, gunakan whereHas
        //         if (isset($this->relationFilters[$field])) {
        //             $relation = $this->relationFilters[$field];
        //             $query->whereHas($relation, function ($q) use ($value) {
        //                 if (is_array($value)) {
        //                     $q->whereIn('id', $value);
        //                 } else {
        //                     $q->where('id', $value);
        //                 }
        //             });
        //         } else {
        //             if (is_array($value)) {
        //                 $query->whereIn($field, $value);
        //             } else {
        //                 $query->where($field, $value);
        //             }
        //         }
        //     }
        // }
        foreach ($this->filters as $field => $value) {
            if ($value !== null && $value !== '' && $value !== []) {
                if (isset($this->relationFilters[$field])) {
                    $relation = $this->relationFilters[$field];
                    $query->whereHas($relation, function ($q) use ($value) {
                        $relatedTable = $q->getModel()->getTable();
                        if (is_array($value)) {
                            // Pastikan array numerik dan tidak kosong
                            $ids = array_filter($value, fn($v) => is_numeric($v));
                            if (count($ids)) {
                                $q->whereIn($relatedTable . '.id', $ids);
                            }
                        } else {
                            $q->where($relatedTable . '.id', $value);
                        }
                    });
                } else {
                    if (is_array($value)) {
                        $ids = array_filter($value, fn($v) => is_numeric($v));
                        if (count($ids)) {
                            $query->whereIn($field, $ids);
                        }
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        // Sorting
        if ($this->sortBy) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $data = $query->paginate($this->perPage);

        return view('livewire.table-component', [
            'data' => $data,
            'totalCount' => $data->total(),
            'pageRowIds' => $data->pluck('id')->toArray(),
        ]);
    }
}
