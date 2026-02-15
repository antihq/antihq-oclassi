<?php

use App\Models\Listing;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Url]
    public string $sort = 'newest';

    #[Computed]
    public function listings()
    {
        return match ($this->sort) {
            'oldest' => Listing::query()->with(['photos', 'user'])->oldest()->get(),
            'price_low' => Listing::query()->with(['photos', 'user'])->orderBy('price')->get(),
            'price_high' => Listing::query()->with(['photos', 'user'])->orderByDesc('price')->get(),
            default => Listing::query()->with(['photos', 'user'])->latest()->get(),
        };
    }
};
?>

<div>
    <flux:heading size="xl">
        @if($this->listings->isEmpty())
            No listings yet
        @else
            {{ $this->listings->count() }} {{ Str::plural('listing', $this->listings->count()) }}
        @endif
    </flux:heading>

    <div class="flex">
        <flux:radio.group wire:model.live="sort" variant="segmented" class="mt-6">
            <flux:radio value="newest" label="Newest" />
            <flux:radio value="oldest" label="Oldest" />
            <flux:radio value="price_low" label="Lowest Price" />
            <flux:radio value="price_high" label="Highest Price" />
        </flux:radio.group>
    </div>

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
                </div>
                <div>
                    <flux:heading>${{ number_format($listing->price / 100, 2) }}</flux:heading>
                    <flux:heading size="lg" class="mt-2">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                    <flux:link href="{{ route('users.show', $listing->user) }}" variant="ghost" wire:navigate class="text-sm">{{ $listing->user->name }}</flux:link>
                </div>
            </div>
        @endforeach
    </div>
</div>
