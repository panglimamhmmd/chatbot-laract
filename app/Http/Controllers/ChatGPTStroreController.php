<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGPTStroreController extends Controller
{
    //
    public function __invoke(StoreChatRequest $request, string $id = null)
    {
        try {
            $messages = [];
            if ($id) {
                $chat = Chat::findOrFail($id);
                $messages = $chat->context;
            }
            $messages[] = ['role' => 'user ', 'content' => $request->input('message')];
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages
            ]);

            $messages[] = ['role' => 'assistant', 'content' => $response->choices[0]->message->content];
            Chat::updateOrCreate(
                [
                    'id' => $id,
                    'user_id' => Auth::id()
                ],
                [
                    'context' => $messages
                ]
            );
            return redirect()->route('chat.show', [$chat->id]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
