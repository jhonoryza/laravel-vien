<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserController extends Controller
{
    protected array $pageOptions = [5, 10, 25, 50, 100];

    public function index()
    {
        $limit   = request('limit', 10);
        $page    = request('page', 1);
        $builder = DB::table('users')
            ->select('id', 'name', 'email', 'created_at')
            ->when(request('filter.search'), function (Builder $query, $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(
                request('filter.name'),
                fn (Builder $query, $name) => $query->where('name', 'like', "%{$name}%")
            )
            ->when(
                request('filter.email'),
                fn (Builder $query, $email) => $query->where('email', $email)
            )
            ->when(
                request('sort'),
                fn (Builder $query, $sort) => $query->orderBy($sort[0] == '-' ? ltrim($sort, '-') : $sort, $sort[0] == '-' ? 'desc' : 'asc'),
                fn (Builder $query)        => $query->orderBy('id', 'asc'),
            );

        /** @var Collection $paginated */
        $paginated = $builder
            ->clone()
            ->simplePaginate(perPage: $limit, page: $page);
        // ->cursorPaginate(perPage: $limit, cursorName: 'page');

        $data = $paginated
            ->through(fn ($user) => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'created_at' => $user->created_at,
            ])
            ->withQueryString();

        return Inertia::render('User/Index', [
            'table' => [
                'users'       => $data,
                'pageOptions' => $this->pageOptions,
                'limit'       => $data->perPage(),
                'allIds'      => method_exists($paginated, 'total') ? $builder->select('id')->pluck('id')->toArray() : [],
                'columns'     => [
                    ['key' => 'id', 'label' => 'ID', 'visible' => true, 'sortable' => true],
                    ['key' => 'name', 'label' => 'Name', 'visible' => true, 'sortable' => true],
                    ['key' => 'email', 'label' => 'Email', 'visible' => true, 'sortable' => true],
                    ['key' => 'created_at', 'label' => 'Created At', 'visible' => true, 'sortable' => true],
                ],
                'filters'     => ['name', 'email', 'search'],
                'defaultSort' => '-created_at',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);
        $user = User::query()->create($data);

        session()->flash('message', [
            'message' => "Buat user {$user->id} berhasil.",
            'type'    => 'success',
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('User/Show', [
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'created_at' => $user->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('User/Edit', [
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'created_at' => $user->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,id,' . $user->id,
            'password' => 'nullable|string|min:8|max:255|confirmed',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $user->fill($data)->save();

        session()->flash('message', [
            'message' => 'Update user berhasil.',
            'type'    => 'success',
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('message', [
            'message' => "User id {$user->id} berhasil dihapus.",
            'type'    => 'success',
        ]);

        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'required|exists:users,id',
        ]);
        User::query()->whereIn('id', $request->ids)->delete();

        $idsString = collect($request->ids)->implode(',');
        session()->flash('message', [
            'message' => "User id {$idsString} berhasil dihapus.",
            'type'    => 'success',
        ]);

        return redirect()->back();
    }
}
