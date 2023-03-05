<?php

namespace App\Service;

class ChatGPTService
{
    public function sendMessage($data){
        $ch = curl_init(env('CHAT_GPT_URL'));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
            'Authorization: Bearer ' . env('CHAT_GPT_TOKEN')
        ));

        $fields = array(
            "model" => env('CHAT_GPT_MODEL'),
            "messages" => [
                "role" => "user",
                "content"=> $data["content"]
                ],
            "user" => $data["user"],
            "max_tokens" => env('CHAT_GPT_MAX_TOKENS'),
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);


        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
