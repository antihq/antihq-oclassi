<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->buyer_id === $user->id || $conversation->seller_id === $user->id;
    }

    public function reply(User $user, Conversation $conversation): bool
    {
        return $conversation->buyer_id === $user->id || $conversation->seller_id === $user->id;
    }
}
