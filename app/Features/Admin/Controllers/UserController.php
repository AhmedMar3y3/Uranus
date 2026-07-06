<?php

namespace App\Features\Admin\Controllers;

use App\Enums\Gender;
use App\Features\Admin\Requests\UpdateUserRequest;
use App\Features\Admin\Services\UserManagementService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserManagementService $users)
    {
    }

    public function index(Request $request): View
    {
        return view('admin.users.index', [
            'users' => $this->users->paginate(
                $request->string('search')->toString(),
                $request->string('profile')->toString()
            ),
            'genders' => Gender::cases(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->users->update($user, $request->validated());

        return back()->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->users->delete($user);

        return back()->with('status', 'User deleted.');
    }
}
