<?php

namespace App\Features\Admin\Controllers;

use App\Enums\FriendshipStatus;
use App\Features\Admin\Requests\UpdateFriendshipRequest;
use App\Features\Admin\Services\FriendshipManagementService;
use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendshipController extends Controller
{
    public function __construct(private readonly FriendshipManagementService $friendships)
    {
    }

    public function index(Request $request): View
    {
        return view('admin.friendships.index', [
            'friendships' => $this->friendships->paginate(
                $request->string('search')->toString(),
                $request->string('status')->toString()
            ),
            'statuses' => FriendshipStatus::cases(),
            'users' => User::query()->select('id', 'full_name', 'username', 'email')->orderBy('full_name')->get(),
        ]);
    }

    public function update(UpdateFriendshipRequest $request, Friendship $friendship): RedirectResponse
    {
        $this->friendships->update($friendship, $request->validated());

        return back()->with('status', 'Friendship updated.');
    }

    public function destroy(Friendship $friendship): RedirectResponse
    {
        $this->friendships->delete($friendship);

        return back()->with('status', 'Friendship deleted.');
    }
}
