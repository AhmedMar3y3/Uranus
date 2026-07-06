<?php

namespace App\Features\Admin\Controllers;

use App\Features\Admin\Services\ConversationManagementService;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function __construct(private readonly ConversationManagementService $conversations)
    {
    }

    public function index(Request $request): View
    {
        return view('admin.conversations.index', [
            'conversations' => $this->conversations->paginate($request->string('search')->toString()),
        ]);
    }

    public function show(Conversation $conversation): View
    {
        $conversation->load(['userOne:id,email,username,full_name,is_online', 'userTwo:id,email,username,full_name,is_online']);

        return view('admin.conversations.show', [
            'conversation' => $conversation,
            'messageMeta' => $this->conversations->messageMeta($conversation),
        ]);
    }

    public function destroy(Conversation $conversation): RedirectResponse
    {
        $this->conversations->delete($conversation);

        return redirect()->route('admin.conversations.index')->with('status', 'Conversation deleted.');
    }
}
