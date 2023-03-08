<?php

namespace App\Http\Controllers;

use App\Service\ChatGPTService;
use App\Service\TwilioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGPTController
{

    public function testChatGPT(Request $request) {
        $data = [
            "content" => $request->get('content'),
            "user" => $request->get('user')
        ];

        return ChatGPTService::sendMessage($data);
        //TwilioService::postMessageToWhatsApp($message, $from);

    }

    public function message(Request $request)
    {
        // Parse the request body from the POST
        $body = $request->all();

        // info on WhatsApp text message payload: https://developers.facebook.com/docs/whatsapp/cloud-api/webhooks/payload-examples#text-messages
        if (isset($body['object'])) {
            if (
                isset($body['entry'][0]['changes'][0]['value']['messages'][0])
            ) {
                $phone_number_id = $body['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                $from = $body['entry'][0]['changes'][0]['value']['messages'][0]['from']; // extract the phone number from the webhook payload
                $msg_body = $body['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']; // extract the message text from the webhook payload

                $rta_chat_gpt = ChatGPTService::sendMessage([
                   "content" => $msg_body,
                   "user" => $phone_number_id
                ]);
                Http::post("https://graph.facebook.com/".env("WHATSAPP_VERSION")."/" . $phone_number_id . "/messages?access_token="  .env('WHATSAPP_TOKEN'), [
                    'messaging_product' => 'whatsapp',
                    'to' => $from,
                    'text' => ['body' => $rta_chat_gpt],
                ]);
            }
            return response()->json([]);
        } else {
            // Return a '404 Not Found' if event is not from a WhatsApp API
            return response()->json([], 400);
        }
    }

    function verifyWebhook(Request $request) {
        /**
         * UPDATE YOUR VERIFY TOKEN
         * This will be the Verify Token value when you set up webhook
         **/
        $verify_token = env('VERIFY_TOKEN');

        // Parse params from the webhook verification request
        $mode = $request->query('hub.mode');
        $token = $request->query('hub.verify_token');
        $challenge = $request->query('hub.challenge');

        // Check if a token and mode were sent
        if ($mode && $token) {
            // Check the mode and token sent are correct
            if ($mode === "subscribe" && $token === $verify_token) {
                // Respond with 200 OK and challenge token from the request
                echo "WEBHOOK_VERIFIED";
                return response($challenge, 200);
            } else {
                // Responds with '403 Forbidden' if verify tokens do not match
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }
}
