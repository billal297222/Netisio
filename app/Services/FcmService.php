<?php

namespace App\Services;

use Kreait\Firebase\Factory;

use Kreait\Firebase\Messaging\CloudMessage;

class FcmService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('firebase/netisio-firebase-adminsdk-fbsvc-3c1792f39d.json'));

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to a specific FCM token
     */
    public function sendToToken($token, $title, $body, $data = [])
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification([
                'title' => $title,
                'body' => $body,
            ])
            ->withData($data);

        return $this->messaging->send($message);
    }


}
