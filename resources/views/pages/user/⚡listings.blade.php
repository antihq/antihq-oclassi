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
                        <flux:button icon="ellipsis-horizontal" variant="ghost" />
                    </div>
                </div>
                <div>
                    <flux:heading>${{ number_format($listing->price / 100, 2) }}</flux:heading>
                    <flux:heading size="lg" class="mt-2">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                    <flux:text class="mt-1">
                        <flux:link href="#">Edit listing</flux:link>
                    </flux:text>
                </div>
            </div>
        @endforeach
    </div>
</div>
