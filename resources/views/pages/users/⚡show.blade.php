<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    public User $user;

    #[Computed]
    public function listings()
    {
        return $this->user->listings()->with('photos')->latest()->get();
    }
};
?>

<div>
    <div class="flex justify-between">
        <div class="flex items-center gap-4">
            <flux:avatar name="User name" size="xl" circle />
            <flux:heading size="xl">{{ $user->bio ?: "Hello, I'm {$user->name}" }}</flux:heading>
        </div>
        @if(auth()->check() && auth()->id() === $user->id)
            <div>
                <flux:button href="{{ route('profile.edit') }}" wire:navigate>Edit profile</flux:button>
            </div>
        @endif
    </div>
    <flux:separator class="mt-6 mb-8" />

    <flux:heading size="lg">
        @if($this->listings->isEmpty())
            No listings yet
        @else
            {{ $this->listings->count() }} {{ Str::plural('listing', $this->listings->count()) }}
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
                </div>
                <div>
                    <flux:heading>${{ number_format($listing->price / 100, 2) }}</flux:heading>
                    <flux:heading size="lg" class="mt-2">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                </div>
            </div>
        @endforeach
    </div>
</div>
