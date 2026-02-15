<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    public function __construct(
        public Message $message,
        public Conversation $conversation,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'sender_name' => $this->message->sender->name,
            'message_preview' => str($this->message->body)->limit(50),
            'listing_title' => $this->conversation->listing?->title,
        ];
    }
}
