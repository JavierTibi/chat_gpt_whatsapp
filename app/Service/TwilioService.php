<?php

namespace App\Service;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\MessageInstance;

class TwilioService
{

    /**
     * Response message to Twilio
     *
     * @param string $message
     * @param string $recipient
     * @return MessageInstance
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public static function postMessageToWhatsApp(string $message, string $recipient): MessageInstance
    {
        $twilio_whatsapp_number = env('TWILIO_WHATSAPP_NUMBER');
        $account_sid = env("TWILIO_ACCOUNT_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }
}
