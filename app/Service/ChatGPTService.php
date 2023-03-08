<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class ChatGPTService
{
    /**
     * @param $data
     * @return string
     */
    public static function sendMessage($data): string{

        $fields = [
            "model" => env('CHAT_GPT_MODEL'),
            "messages" => array([
                "role" => "user",
                "content" => $data["content"]
            ]),
            "user" => $data["user"],
            "max_tokens" => (int) env('CHAT_GPT_MAX_TOKENS'),
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('CHAT_GPT_TOKEN')
        ])->post(env('CHAT_GPT_URL'), $fields);

        return json_decode($response->body())->choices[0]->message->content;
    }

}
