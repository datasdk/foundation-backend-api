<?php

namespace Modules\Chat\Http\Controllers;

use App\Http\Controllers\OrionBaseController;
use Modules\Chat\Models\Conversation;
use Modules\Chat\Http\Requests\ChatRequest; // Opret denne request-fil
use Modules\Chat\Services\ConversationService;      // Opret service-fil hvis nødvendigt
use Orion\Http\Requests\Request;

class ChatController extends OrionBaseController
{
    protected $model = Conversation::class;
    protected $request = ChatRequest::class;

    /**
     * Liste over chat-conversationer
     */
    public function index(Request $request)
    {
        return view('chat::index');
    }

    /**
     * Vis form til oprettelse af chat
     */
    public function create(Request $request)
    {
        return view('chat::create');
    }

    /**
     * Gem ny chat conversation
     */
    public function store(Request $request)
    {
        app(ConversationService::class)->create($request->validated());

        return redirect()->route('chat.index')
                         ->with('success', 'Chat oprettet.');
    }

    /**
     * Vis chat conversation med pagineret historik
     */
    public function show(Request $request, ...$args)
    {
        $conversation = Conversation::findOrFail($args[0]);

        // Hent 30 nyeste beskeder, nyeste først
        $messages = $conversation->messages()->with('user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(30);

        return view('chat::show', compact('conversation', 'messages'));
    }

    /**
     * Rediger chat conversation
     */
    public function edit(Request $request, ...$args)
    {
        $conversation = Conversation::findOrFail($args[0]);
        return view('chat::edit', compact('conversation'));
    }

    /**
     * Opdater chat conversation
     */
    public function update(Request $request, ...$args)
    {
        $conversation = Conversation::findOrFail($args[0]);

        app(ConversationService::class)->update($conversation, $request->validated());

        return redirect()->route('chat.index')
                         ->with('success', 'Chat opdateret.');
    }

    /**
     * Slet chat conversation
     */
    public function destroy(Request $request, ...$args)
    {
        $conversation = Conversation::findOrFail($args[0]);

        app(ConversationService::class)->delete($conversation);

        return redirect()->route('chat.index')
                         ->with('success', 'Chat slettet.');
    }
}
