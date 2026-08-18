<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Telegram
 * 
 * Handles interactions with the Telegram API.
 * 
 * API documentation: https://core.telegram.org/bots/api
 */
class Telegram {
    private $_telegram_base_url;

    public function __construct()
    {
        $this->_telegram_base_url = env('TELEGRAM_BOT_URL');
    }

    public function set_webhook($url) {
        $response = $this->_make_http_request('GET', "/setWebhook?url=$url");

        return $response;
    }

    public function get_me() {
        $response = $this->_make_http_request('GET', '/getMe');

        return $response;
    }

    public function send_message($chat_id, $message, $keyboards = null) {
        $message = str_replace('*', '', $message);

        $body = [
            'chat_id' => $chat_id,
            'text' => $message,
        ];

        if ($keyboards) {
            $body = [
                ...$body, 
                'reply_markup' => (object) $keyboards
            ];
        }

        $response = $this->_make_http_request('POST', '/sendMessage', json_encode($body));

        return $response;
    }

    private function _make_http_request($method, $url, $json_body = null) {
        $response = Http::withBody(
            $json_body, 'application/json'
        )->$method($this->_telegram_base_url.$url);

        return $response->json();
    }
}