<?php

use App\Models\Listing;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Locked]
    public Listing $listing;
};
?>

<div class="flex justify-between gap-8">
    <div class="space-y-8 w-full max-w-lg">
        <flux:heading level="1" size="xl">Send an inquiry to {{ $listing->user->name }}</flux:heading>

        <flux:textarea label="Inquiry messge" placeholder="Hello there! I'm interested in..." />

        <div class="flex">
            <flux:spacer />
            <flux:button variant="primary" color="green">Send inquiry</flux:button>
        </div>
    </div>

    <div class="border w-full max-w-xs border-zinc-200">
        @if ($listing->photos->first())
            <img src="{{ Storage::url($listing->photos->first()->path) }}" alt="{{ $listing->title }}">
        @endif

        <div class="px-8 pb-8 -mt-8">
            <flux:avatar name="{{ $listing->user->name }}" size="xl" circle />

            <flux:heading level="1" size="lg" class="mt-8">
                <flux:link :href="route('listings.show', $listing)" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
            </flux:heading>

            <flux:heading class="mt-4">${{ number_format($listing->price) }}</flux:heading>
        </div>
    </div>
</div>
