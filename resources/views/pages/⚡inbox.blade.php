<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Computed]
    public function conversations()
    {
        return auth()->user()->conversations();
    }

    public function placeholder()
    {
        return '<div class="space-y-4">'.str_repeat('<flux:skeleton class="h-20 w-full" />', 3).'</div>';
    }
};
?>

<div class="max-w-5xl mx-auto" wire:poll.10s>
    <flux:heading level="1" size="xl">Inbox</flux:heading>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    @if($this->conversations->isEmpty())
        <div class="text-center py-12">
            <flux:icon name="inbox" variant="outline" class="size-12 mx-auto text-zinc-400" />
            <flux:text class="mt-4 text-zinc-500">No conversations yet</flux:text>
            <flux:text class="text-zinc-400 text-sm mt-1">When you send or receive inquiries, they'll appear here</flux:text>
        </div>
    @else
        <div class="space-y-2 divide-y divide-zinc-100">
            @foreach($this->conversations as $conversation)
                @php
                    $otherUser = $conversation->otherUser(auth()->user());
                    $unreadCount = $conversation->unreadMessagesFor(auth()->user())->count();
                @endphp
                <a
                    href="{{ route('conversations.show', $conversation) }}"
                    wire:navigate
                    class="flex items-start gap-4 py-4 px-2 -mx-2 hover:bg-zinc-50 transition-colors"
                >
                    <flux:avatar :src="$otherUser->profilePhotoUrl()" :name="$otherUser->name" circle />

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <flux:heading size="lg">
                                {{ $otherUser->name }}
                            </flux:heading>
                            @if($conversation->last_message_at)
                                <flux:text class="text-sm text-zinc-400 shrink-0">
                                    {{ $conversation->last_message_at->diffForHumans() }}
                                </flux:text>
                            @endif
                        </div>

                        @if($conversation->listing)
                            <flux:text class="mt-0.5">
                                {{ $conversation->listing->title }}
                            </flux:text>
                        @endif
                    </div>

                    @if($unreadCount > 0)
                        <flux:badge size="sm" variant="solid" color="green" rounded>{{ $unreadCount }}</flux:badge>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
