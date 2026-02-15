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
    <div class="flex-1">
        @if(auth()->check() && auth()->user()->is($listing->user))
            <flux:callout variant="secondary" class="mb-4" inline>
                <flux:callout.heading>
                    This is your own listing.
                </flux:callout.heading>
                <x-slot name="actions">
                    <flux:button :href="route('listings.edit', $listing)">Edit listing</flux:button>
                </x-slot>
            </flux:callout>
        @endif

        <div>
            @if ($listing->photos->first())
                <img
                    class="rounded"
                    src="{{ Storage::url($listing->photos->first()->path) }}"
                    alt="{{ $listing->title }}"
                />
            @endif
        </div>

        <div class="mt-5 grid grid-cols-6 gap-2">
            @foreach ($listing->photos->skip(1) as $photo)
                <img
                    src="{{ Storage::url($photo->path) }}"
                    class="rounded"
                    alt="{{ $listing->title }}"
                />
            @endforeach
        </div>

        <div class="mt-8">
            {!! $listing->description !!}
        </div>

        <div class="mt-8">
            <flux:heading level="h2" size="lg">Location</flux:heading>
            <flux:text class="mt-2 text-base">
                {{ $listing->address }}{{ $listing->address_line_2 ? ", " . $listing->address_line_2 : "" }}
            </flux:text>
        </div>

        <div class="mt-8">
            <flux:heading level="h2" size="lg">
                About the listing author
            </flux:heading>
            <div class="mt-4 flex gap-8">
                <flux:avatar
                    name="{{ $listing->user->name }}"
                    size="xl"
                    circle
                />
                <div class="flex-1">
                    <div class="flex justify-between gap-8">
                        <flux:text class="text-base">
                            {{ $listing->user->bio ?: "Hello, I'm {$listing->user->name}" }}
                        </flux:text>
                        <flux:text>
                            <flux:link :href="route('profile.edit')">
                                Edit profile
                            </flux:link>
                        </flux:text>
                    </div>
                    <flux:text class="mt-6 text-base">
                        <flux:link :href="route('profile.edit')">
                            View profile
                        </flux:link>
                    </flux:text>
                </div>
            </div>
        </div>
    </div>
    <div class="w-xs">
        <flux:heading level="1" size="xl">{{ $listing->title }}</flux:heading>
        <flux:heading size="lg" class="mt-1">
            ${{ number_format($listing->price) }}
        </flux:heading>
        <div class="mt-5 flex items-center gap-2">
            <flux:avatar name="{{ $listing->user->name }}" circle />
            <flux:link :href="route('users.show', $listing->user)" wire:navigate variant="ghost">
                <flux:text variant="strong">{{ $listing->user->name }}</flux:text>
            </flux:link>
        </div>

        <div class="mt-12 text-center">
            <flux:button variant="primary" color="green" class="w-full" :disabled="auth()->check() && auth()->user()->is($listing->user)">
                Send an inquiry
            </flux:button>
            @if(auth()->check() && auth()->user()->is($listing->user))
                <flux:text class="mt-2">This is your own listing.</flux:text>
            @endif
        </div>
    </div>
</div>
