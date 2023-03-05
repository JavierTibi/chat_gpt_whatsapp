<?php

namespace App\Http\Controllers;

use App\Service\TwilioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatGPTController
{

    public function receiveMessage(Request $request) {
        $from = $request->get('From');
        $body = $request->get('Body');

        $message="ChatGPT is out of service";
        TwilioService::postMessageToWhatsApp($message, $from);
        return new JsonResponse([
            'success' => true,
        ]);
    }
}
