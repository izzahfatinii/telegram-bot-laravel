<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Classes\Telegram;

class BotCommand {
    private $_chat_id;

    public function __construct($chat_id)
    {
        $this->_chat_id = $chat_id;
    }

    public function start() {
        $message = "Hi Welcome 👋 \n\nYou can choose a feature by clicking one of the buttons below.";

        $inline_keyboard = [ 
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Grammar 😃',
                        'callback_data' => 'mode:grammar'
                    ],
                ],
                [
                    [
                        'text' => 'Dictionary 📖',
                        'callback_data' => 'mode:dictionary'
                    ],
                ],
            ],
        ];

        $send_message = (new Telegram)->send_message($this->_chat_id, $message, $inline_keyboard);
    }
}