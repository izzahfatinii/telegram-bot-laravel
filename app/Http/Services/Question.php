<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Classes\Telegram;
use App\Classes\Gemini2Flash;

class Question {
    private $_chat_id;

    public function __construct($chat_id)
    {
        $this->_chat_id = $chat_id;
    }

    public function grammar($question) {
        $question = "\"$question\" correct the sentence grammar and only give 1 suggestion.";
        
        $answer = (new Gemini2Flash)->get_answer($question);

        (new Telegram)->send_message($this->_chat_id, $answer);
    }

    public function dictionary($question) {
        $question = "\"$question\" what is meaning of the word(s) and only give 1 asnwer.";
        
        $answer = (new Gemini2Flash)->get_answer($question);

        (new Telegram)->send_message($this->_chat_id, $answer);
    }
}