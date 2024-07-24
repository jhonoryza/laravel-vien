<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SettingController extends Controller
{
    protected array $pageOptions = [5, 10, 25, 50, 100];

    public function index()
    {
        $limit = request('limit', 10);
        $page  = request('page', 1);

        $builder = DB::table('settings')
            ->select('id', 'key', 'value', 'created_at')
            ->when(request('filter.search'), function (Builder $query, $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('key', 'like', "%{$search}%")
                        ->orWhere('value', 'like', "%{$search}%");
                });
            })
            ->when(
                request('filter.key'),
                fn (Builder $query, $filter) => $query->where('key', 'like', "%{$filter}%")
            )
            ->when(
                request('filter.value'),
                fn (Builder $query, $filter) => $query->where('value', $filter)
            )
            ->when(
                request('sort'),
                fn (Builder $query, $sort) => $query->orderBy($sort[0] == '-' ? ltrim($sort, '-') : $sort, $sort[0] == '-' ? 'desc' : 'asc'),
                fn (Builder $query)        => $query->orderBy('id', 'asc'),
            );

        /** @var Collection $paginated */
        $paginated = $builder
            ->clone()
            ->paginate(perPage: $limit, page: $page);
        // ->cursorPaginate(perPage: $limit, cursorName: 'page');

        $data = $paginated
            ->through(fn ($item) => [
                'id'         => $item->id,
                'key'        => $item->key,
                'value'      => $item->value,
                'created_at' => $item->created_at,
            ])
            ->withQueryString();

        return Inertia::render('Setting/Index', [
            'table' => [
                'settings'    => $data,
                'pageOptions' => $this->pageOptions,
                'limit'       => $data->perPage(),
                'allIds'      => method_exists($paginated, 'total') ? $builder->select('id')->pluck('id')->toArray() : [],
                'columns'     => [
                    ['key' => 'id', 'label' => 'ID', 'visible' => true, 'sortable' => true],
                    ['key' => 'key', 'label' => 'Key', 'visible' => true, 'sortable' => true],
                    ['key' => 'value', 'label' => 'Value', 'visible' => true, 'sortable' => true],
                    ['key' => 'created_at', 'label' => 'Created At', 'visible' => true, 'sortable' => true],
                ],
                'filters'     => ['key', 'value', 'search'],
                'defaultSort' => '-created_at',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Setting/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'key'   => 'required|string|max:255|unique:settings',
            'value' => 'required|string|max:255',
        ]);
        $model = Setting::query()->create($data);

        session()->flash('message', [
            'message' => "Buat setting {$model->id} berhasil.",
            'type'    => 'success',
        ]);

        return redirect()->route('settings.show', $model->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        return Inertia::render('Setting/Show', [
            'setting' => [
                'id'         => $setting->id,
                'key'        => $setting->key,
                'value'      => $setting->value,
                'created_at' => $setting->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        return Inertia::render('Setting/Edit', [
            'setting' => [
                'id'         => $setting->id,
                'key'        => $setting->key,
                'value'      => $setting->value,
                'created_at' => $setting->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'key'   => 'required|string|max:255|unique:settings,id,' . $setting->id,
            'value' => 'required|string|max:255',
        ]);

        $setting->fill($data)->save();

        session()->flash('message', [
            'message' => 'Update setting berhasil.',
            'type'    => 'success',
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        session()->flash('message', [
            'message' => "Setting id {$setting->id} berhasil dihapus.",
            'type'    => 'success',
        ]);

        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'required|exists:settings,id',
        ]);
        Setting::query()->whereIn('id', $request->ids)->delete();

        $idsString = collect($request->ids)->implode(',');
        session()->flash('message', [
            'message' => "Setting id {$idsString} berhasil dihapus.",
            'type'    => 'success',
        ]);

        return redirect()->back();
    }
}
