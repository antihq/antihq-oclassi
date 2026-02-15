<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Computed]
    public function listings()
    {
        return auth()->user()->listings()->with('photos')->latest()->get();
    }

    public function toggleListingStatus(int $listingId): void
    {
        $listing = auth()->user()->listings()->findOrFail($listingId);

        $listing->closed_at = $listing->closed_at ? null : now();
        $listing->save();
    }
};
?>

<div>
    <flux:heading size="xl">
        @if($this->listings->isEmpty())
            You have no listings yet
        @else
            Your {{ $this->listings->count() }} {{ Str::plural('listing', $this->listings->count()) }}
        @endif
    </flux:heading>

    <div class="mt-6 grid grid-cols-3 gap-5">
        @foreach($this->listings as $listing)
            <div class="space-y-4">
                <div class="relative">
                    <img
                        class="rounded aspect-[4/3] w-full object-cover"
                        src="{{ $listing->photos->first() ? Storage::url($listing->photos->first()->path) : 'https://placehold.co/400x300/e2e8f0/94a3b8?text=No+photo' }}"
                        alt="{{ $listing->title }}"
                    >
                    <a href="{{ route('listings.show', $listing) }}" class="absolute inset-0" wire:navigate></a>
                    <div class="absolute top-1 right-1">
                        <flux:dropdown>
                            <flux:button icon="ellipsis-horizontal" variant="ghost" />
                            <flux:menu>
                                @if($listing->isClosed())
                                    <flux:menu.item icon="arrow-path" wire:click="toggleListingStatus({{ $listing->id }})">
                                        Reopen listing
                                    </flux:menu.item>
                                @else
                                    <flux:menu.item icon="x-mark" wire:click="toggleListingStatus({{ $listing->id }})">
                                        Close listing
                                    </flux:menu.item>
                                @endif
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>
                <div>
                    <flux:heading>${{ number_format($listing->price / 100, 2) }}</flux:heading>
                    <flux:heading size="lg" class="mt-2">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                    <flux:text class="mt-1">
                        <flux:link href="{{ route('listings.edit', $listing) }}" wire:navigate>Edit listing</flux:link>
                    </flux:text>
                </div>
            </div>
        @endforeach
    </div>
</div>
