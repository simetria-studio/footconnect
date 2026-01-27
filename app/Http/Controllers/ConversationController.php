<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::query()
            ->where(function ($q) use ($user): void {
                $q->where('user_one_id', $user->id)
                    ->orWhere('user_two_id', $user->id);
            })
            ->with(['userOne', 'userTwo', 'messages' => fn ($q) => $q->latest()])
            ->latest()
            ->get();

        return view('messages.index', [
            'conversations' => $conversations,
            'user' => $user,
        ]);
    }

    public function show(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless(
            $conversation->user_one_id === $user->id || $conversation->user_two_id === $user->id,
            403
        );

        $conversation->load(['userOne', 'userTwo', 'messages.sender']);

        return view('messages.show', [
            'conversation' => $conversation,
            'user' => $user,
        ]);
    }

    public function start(Request $request, $userId)
    {
        $authUser = $request->user();

        // Validações robustas
        if (!$authUser || !$authUser->id) {
            abort(403, 'Usuário não autenticado');
        }

        // Buscar o usuário destino pelo ID
        $target = User::find($userId);

        if (!$target || !$target->id) {
            abort(404, 'Usuário destino não encontrado');
        }

        if ($authUser->id === $target->id) {
            abort(403, 'Não é possível iniciar conversa consigo mesmo');
        }

        // Garantir que os IDs sejam inteiros válidos
        $userOneId = (int) min($authUser->id, $target->id);
        $userTwoId = (int) max($authUser->id, $target->id);

        if ($userOneId <= 0 || $userTwoId <= 0) {
            abort(500, 'IDs de usuário inválidos');
        }

        // Buscar ou criar conversa
        $conversation = Conversation::where(function ($query) use ($userOneId, $userTwoId) {
            $query->where('user_one_id', $userOneId)
                  ->where('user_two_id', $userTwoId);
        })->first();

        if (!$conversation) {
            try {
                $conversation = Conversation::create([
                    'user_one_id' => $userOneId,
                    'user_two_id' => $userTwoId,
                ]);
            } catch (\Exception $e) {
                \Log::error('Erro ao criar conversa', [
                    'user_one_id' => $userOneId,
                    'user_two_id' => $userTwoId,
                    'user_id' => $authUser->id,
                    'target_id' => $target->id,
                    'error' => $e->getMessage(),
                ]);
                abort(500, 'Erro ao criar conversa: ' . $e->getMessage());
            }
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless($user && $user->id, 403, 'Usuário não autenticado');
        abort_unless($conversation && $conversation->id, 404, 'Conversa não encontrada');
        abort_unless(
            $conversation->user_one_id === $user->id || $conversation->user_two_id === $user->id,
            403,
            'Você não tem permissão para enviar mensagens nesta conversa'
        );

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $data['body'],
        ]);

        return redirect()->route('messages.show', $conversation);
    }
}

