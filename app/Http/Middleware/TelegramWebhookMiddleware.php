<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TelegramSession;

class TelegramWebhookMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info($request);

        if (isset($request['message'])) {
            $type = 'message';
            $chat_id = $request['message']['chat']['id'];
            $reply = $request['message']['text'];
        } else if (isset($request['callback_query'])) {
            $type = 'callback_query';
            $chat_id = $request['callback_query']['message']['chat']['id'];
            $reply = $request['callback_query']['data'];
        }
        
        $session = TelegramSession::where('chat_id', $chat_id)->first();

        if ($session) {
            session([
                'chat_id' => $chat_id,
                'mode' => $session->mode,
            ]);
        } else {
            TelegramSession::insert([
                'chat_id' => $chat_id,
            ]);

            session([
                'chat_id' => $chat_id,
            ]);
        }
        
        return $next($request);
    }
}
