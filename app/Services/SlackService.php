<?php

namespace App\Services;

use Exception;
use Slack;
use SlackMessage;

class SlackService
{
    public function __construct(protected Slack $slack)
    {
    }

    /**
     * @param string $message
     * @return void
     * @throws Exception
     */
    public function sendMessage(string $message): void
    {
        $slackMessage = new SlackMessage($this->slack);
        $slackMessage->setText($message);

        if (!$slackMessage->send()) {
            throw new Exception('Failed to send SlackMessage');
        }
    }
}
