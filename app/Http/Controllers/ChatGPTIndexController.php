<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatGPTIndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id = null): Response
    {
        return Inertia::render('Chat/ChatIndex', [
            'chat' => fn () => $id ? Chat::findOrFail($id) : null,
            'messages' => Chat::latest()->where('user_id', Auth::id())->get()
        ]);
    }
}
