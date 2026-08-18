<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Gemini2Flash
 * 
 * Handles interactions with the Gemini API.
 * 
 * API documentation: https://aistudio.google.com/app/apikey
 */
class Gemini2Flash {
    private $_gemini_base_url;

    public function __construct()
    {
        $this->_gemini_url = env('GEMINI_URL');
    }

    public function get_answer($question) {
        $body = (object) [
            'contents' => [ 
                (object) [
                    'parts' => [
                        (object) [
                            'text' => $question,
                        ]
                    ]
                ]
            ]
        ];
        
        $response = $this->_make_http_request(json_encode($body));
        
        $answer = $response['candidates'][0]['content']['parts'][0]['text'];

        return $answer;
    }

    private function _make_http_request($json_body) {
        $response = Http::withBody(
            $json_body, 'application/json'
        )->post($this->_gemini_url);

        return $response->json();
    }
}