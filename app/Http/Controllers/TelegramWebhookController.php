<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Classes\Telegram;
use App\Http\Services\BotCommand;
use App\Http\Services\Question;
use App\Models\TelegramSession;

/**
 * Controller to handle incoming Telegram webhook requests.
 * 
 * Processes messages and callback queries from users, including:
 * - Executing bot commands starting with '/'
 * - Switching bot modes when receiving 'mode:' commands
 * - Handling user questions based on the current mode stored in session
 * 
 * Logs any exceptions encountered during processing.
 */
class TelegramWebhookController extends Controller
{
    public function handle(Request $request) {
        $chat_id = session('chat_id');

        try {
            if (isset($request['message'])) {
                $reply = $request['message']['text'];
            } else if (isset($request['callback_query'])) {
                $reply = $request['callback_query']['data'];
            }

            // handling command
            if (str_starts_with($reply, '/')) {
                $command = ltrim($reply, '/');
                $bot = new BotCommand($chat_id);

                if (method_exists($bot, $command)) {
                    $bot->{$command}();
                } else {
                    $send_message = (new Telegram)->send_message($chat_id, 'Command not exists');
                }
            } 
            // handling choosing mode
            else if (str_starts_with($reply, 'mode:')) {
                $mode = ltrim(strstr($reply, ':'), ':');

                TelegramSession::where('chat_id', $chat_id)->update([
                    'mode' => $mode,
                ]); 

                $send_message = (new Telegram)->send_message($chat_id, ucfirst($mode)." activated.\nPlease reply to this chat with your question 🙂");
            } 
            // answring question based on mode
            else if(session('mode')) {
                $question = new Question($chat_id);

                $question->{session('mode')}($reply);
            }
    
        } catch(\Exception $e) {
            Log::error($e);
        }
    }
}
