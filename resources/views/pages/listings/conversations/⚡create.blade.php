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

<div class="flex justify-between gap-8">
    <div class="space-y-8 w-full max-w-lg">
        <flux:heading level="1" size="xl">Send an inquiry to {{ $listing->user->name }}</flux:heading>

        <form wire:submit="send" class="space-y-6">
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

    <div class="border w-full max-w-xs border-zinc-200">
        @if ($listing->photos->first())
            <img src="{{ Storage::url($listing->photos->first()->path) }}" alt="{{ $listing->title }}">
        @endif

        <div class="px-8 pb-8 -mt-8">
            <flux:avatar :src="$listing->user->profilePhotoUrl()" :name="$listing->user->name" size="xl" circle />

            <flux:heading level="1" size="lg" class="mt-8">
                <flux:link :href="route('listings.show', $listing)" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
            </flux:heading>

            <flux:heading class="mt-4">${{ number_format($listing->price) }}</flux:heading>
        </div>
    </div>
</div>
