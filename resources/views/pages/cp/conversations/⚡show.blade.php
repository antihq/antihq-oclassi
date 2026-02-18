<?php

use App\Models\Conversation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Locked]
    public Conversation $conversation;

    public function mount(Conversation $conversation): void
    {
        $this->conversation = $conversation->load(['listing.photos', 'buyer', 'seller']);
    }

    #[Computed]
    public function threadMessages()
    {
        return $this->conversation
            ->messages()
            ->with('sender')
            ->get();
    }

    #[Computed]
    public function messagesCount()
    {
        return $this->conversation->messages()->count();
    }

    #[Computed]
    public function createdAt()
    {
        return $this->conversation->created_at->format('M j, Y');
    }

    #[Computed]
    public function lastMessageAt()
    {
        return $this->conversation->last_message_at
            ? $this->conversation->last_message_at->format('M j, Y')
            : '-';
    }
};
?>

<div>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            <flux:heading size="xl">Conversation #{{ $conversation->id }}</flux:heading>
        </div>
    </div>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <div class="space-y-8">
        <div>
            <flux:heading size="lg">Participants</flux:heading>

            <div class="mt-4 flex gap-6">
                <div class="flex items-center gap-3">
                    <flux:avatar
                        :src="$conversation->buyer->profilePhotoUrl()"
                        :name="$conversation->buyer->name"
                        size="md"
                        circle
                    />
                    <div class="space-y-1">
                        <flux:text class="text-sm font-medium">Buyer</flux:text>
                        <div class="text-sm">
                            <flux:link href="{{ route('cp.users.show', $conversation->buyer) }}" wire:navigate>
                                {{ $conversation->buyer->name }}
                            </flux:link>
                        </div>
                        <flux:text class="text-xs">{{ $conversation->buyer->email }}</flux:text>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:avatar
                        :src="$conversation->seller->profilePhotoUrl()"
                        :name="$conversation->seller->name"
                        size="md"
                        circle
                    />
                    <div class="space-y-1">
                        <flux:text class="text-sm font-medium">Seller</flux:text>
                        <div class="text-sm">
                            <flux:link href="{{ route('cp.users.show', $conversation->seller) }}" wire:navigate>
                                {{ $conversation->seller->name }}
                            </flux:link>
                        </div>
                        <flux:text class="text-xs">{{ $conversation->seller->email }}</flux:text>
                    </div>
                </div>
            </div>
        </div>

        @if ($conversation->listing)
            <div>
                <flux:heading size="lg">Listing</flux:heading>

                <div class="mt-4 flex items-center gap-4">
                    @if ($conversation->listing->photos->isNotEmpty())
                        <flux:avatar
                            :src="Storage::url($conversation->listing->photos->first()->path)"
                            :name="$conversation->listing->title"
                            size="lg"
                        />
                    @else
                        <flux:avatar size="lg" />
                    @endif

                    <div class="flex-1">
                        <flux:link href="{{ route('cp.listings.show', $conversation->listing) }}" wire:navigate>
                            {{ $conversation->listing->title }}
                        </flux:link>
                        <flux:text class="mt-1">
                            ${{ number_format($conversation->listing->price) }}
                        </flux:text>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <flux:heading size="lg">Messages</flux:heading>

            <flux:table class="mt-2">
                <flux:table.columns>
                    <flux:table.column>Sender</flux:table.column>
                    <flux:table.column>Message</flux:table.column>
                    <flux:table.column>Sent</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->threadMessages as $message)
                        <flux:table.row :key="$message->id">
                            <flux:table.cell class="flex items-center gap-2">
                                <flux:avatar
                                    :src="$message->sender->profilePhotoUrl()"
                                    :name="$message->sender->name"
                                    size="xs"
                                    circle
                                />
                                <flux:link href="{{ route('cp.users.show', $message->sender) }}" wire:navigate class="font-semibold">
                                    {{ $message->sender->name }}
                                </flux:link>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:text class="max-w-md break-words">{{ $message->body }}</flux:text>
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">
                                {{ $message->created_at->format('M j, Y g:i A') }}
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">
                                @if ($message->isRead())
                                    <flux:badge variant="subtle" size="sm">Read</flux:badge>
                                @else
                                    <flux:badge variant="primary" size="sm">Unread</flux:badge>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            @if ($this->threadMessages->isEmpty())
                <flux:callout variant="secondary" class="mt-4" inline>
                    <flux:callout.heading>
                        No messages yet
                    </flux:callout.heading>
                </flux:callout>
            @endif
        </div>

        <div>
            <flux:heading size="lg">Details</flux:heading>

            <x-description.list class="w-full mt-4">
                <x-description.term>Subject</x-description.term>
                <x-description.details>{{ $conversation->subject }}</x-description.details>

                <x-description.term>Started</x-description.term>
                <x-description.details>{{ $this->createdAt }}</x-description.details>

                <x-description.term>Last Message</x-description.term>
                <x-description.details>{{ $this->lastMessageAt }}</x-description.details>

                <x-description.term>Total Messages</x-description.term>
                <x-description.details variant="strong">{{ $this->messagesCount }}</x-description.details>
            </x-description.list>
        </div>
    </div>
</div>
