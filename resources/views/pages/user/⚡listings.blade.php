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

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($this->listings as $listing)
            <div @class([
                'bg-white rounded-xl shadow-sm overflow-hidden',
                'group hover:shadow-md transition-shadow' => !$listing->isClosed(),
                'overflow-hidden' => $listing->isClosed(),
            ])>
                <div class="relative">

                    @if($listing->photos->first())
                        <img
                            @class([
                                'aspect-[4/3] w-full object-cover',
                                'group-hover:scale-105 transition-transform duration-300' => !$listing->isClosed(),
                            ])
                            src="{{ Storage::url($listing->photos->first()->path) }}"
                            alt="{{ $listing->title }}"
                        >
                    @else
                        <span class="aspect-[4/3] w-full object-cover flex items-center justify-center">
                            <x-icon name="camera" class="text-zinc-400 size-12" />
                        </span>
                    @endif
                    <a href="{{ route('listings.show', $listing) }}" class="absolute inset-0" wire:navigate></a>
                    @if($listing->isClosed())
                        <div class="absolute inset-0 bg-black/60 rounded flex flex-col items-center justify-center z-10">
                            <flux:heading class="text-white">Listing Closed</flux:heading>
                            <flux:text class="text-white/80">Not visible on marketplace</flux:text>
                            <flux:button wire:click="toggleListingStatus({{ $listing->id }})" variant="primary" color="green" class="mt-3">
                                Reopen listing
                            </flux:button>
                        </div>
                    @else
                        <div class="absolute top-2.5 right-2.5 z-20">
                            <flux:dropdown>
                                <flux:button icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('listings.edit', $listing) }}" wire:navigate>
                                        Edit listing
                                    </flux:menu.item>
                                    @unless($listing->isClosed())
                                        <flux:menu.item wire:click="toggleListingStatus({{ $listing->id }})">
                                            Close listing
                                        </flux:menu.item>
                                    @endif
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                    @endif
                </div>
                <div class="py-5 px-4">
                    <flux:heading class="text-xl/none font-semibold">${{ number_format($listing->price / 100, 2) }}</flux:heading>
                    <flux:heading class="mt-4 text-lg! line-clamp-1 font-semibold">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                </div>
            </div>
        @endforeach
    </div>
</div>
