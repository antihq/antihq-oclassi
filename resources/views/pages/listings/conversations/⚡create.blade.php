<?php

use App\Models\Conversation;
use App\Models\Listing;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Locked]
    public Listing $listing;

    #[Validate('required|string|max:5000')]
    public string $body = '';

    public function send(): void
    {
        $this->validate();

        $conversation = Conversation::findOrCreateFor(auth()->user(), $this->listing);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $conversation->touchLastMessageAt();

        $this->listing->user->notify(new NewMessageNotification($message, $conversation));

        $this->redirect(route('conversations.show', $conversation), navigate: true);
    }
};
?>

<div class="flex justify-between gap-8 w-full max-w-5xl mx-auto">
    <div class="w-full max-w-lg">
        <flux:heading level="1" size="xl">Send an inquiry to {{ $listing->user->name }}</flux:heading>

        <form wire:submit="send" class="space-y-6 mt-8">
            <flux:field>
                <flux:label>Message</flux:label>
                <flux:textarea
                    wire:model="body"
                    placeholder="Hello there! I'm interested in..."
                    rows="4"
                />
                <flux:error name="body" />
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" color="green">Send inquiry</flux:button>
            </div>
        </form>
    </div>

    <div class="rounded-xl shadow-sm overflow-hidden max-w-sm">
        @if ($listing->photos->first())
            <img src="{{ Storage::url($listing->photos->first()->path) }}" alt="{{ $listing->title }}">
        @endif

        <div class="px-8 pb-8 -mt-8">
            <flux:avatar :src="$listing->user->profilePhotoUrl()" :name="$listing->user->name" size="xl" circle />
            <flux:heading class="text-xl! font-semibold mt-6">${{ number_format($listing->price / 100) }}</flux:heading>
            <flux:heading class="mt-2 text-lg! font-semibold">
                <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
            </flux:heading>
        </div>
    </div>
</div>
