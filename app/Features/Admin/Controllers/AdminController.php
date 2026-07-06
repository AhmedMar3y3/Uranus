<?php

namespace App\Features\Admin\Controllers;

use App\Features\Admin\Requests\StoreAdminRequest;
use App\Features\Admin\Requests\UpdateAdminRequest;
use App\Features\Admin\Services\AdminAccountService;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly AdminAccountService $admins)
    {
    }

    public function index(Request $request): View
    {
        return view('admin.admins.index', [
            'admins' => $this->admins->paginate($request->string('search')->toString()),
        ]);
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $this->admins->create($request->validated());

        return back()->with('status', 'Admin account created.');
    }

    public function update(UpdateAdminRequest $request, Admin $admin): RedirectResponse
    {
        $this->admins->update($admin, $request->validated());

        return back()->with('status', 'Admin account updated.');
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        if ($admin->is(auth('admin')->user())) {
            return back()->withErrors(['admin' => 'You cannot delete your own admin account.']);
        }

        $this->admins->delete($admin);

        return back()->with('status', 'Admin account deleted.');
    }
}
