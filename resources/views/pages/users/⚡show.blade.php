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
    <div class="flex flex-wrap items-center gap-y-4 gap-x-2 sm:gap-x-4">
        <div class="flex items-center gap-4">
            <flux:avatar :src="$user->profilePhotoUrl()" :name="$user->name" size="xl" circle />
            <div>
                <flux:heading size="xl">Hello, I'm {{ $user->name }}</flux:heading>

                @if ($user->bio)
                    <flux:text class="text-base mt-1">
                        {{ $user->bio }}
                    </flux:text>
                @endif
            </div>
        </div>

        @if(auth()->check() && auth()->id() === $user->id)
            <flux:spacer class="max-lg:hidden" />
            <div>
                <flux:button href="{{ route('profile.edit') }}" wire:navigate>Edit profile</flux:button>
            </div>
        @endif
    </div>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <flux:heading size="lg">
        @if($this->listings->isEmpty())
            No listings yet
        @else
            Listings
        @endif
    </flux:heading>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($this->listings as $listing)
            <div class="rounded-xl shadow-sm overflow-hidden group hover:shadow-md transition-shadow bg-white dark:bg-white/10">
                <div class="relative">
                    <img
                        class="aspect-[4/3] w-full object-cover group-hover:scale-105 transition-transform duration-300"
                        src="{{ $listing->photos->first() ? Storage::url($listing->photos->first()->path) : 'https://placehold.co/400x300/e2e8f0/94a3b8?text=No+photo' }}"
                        alt="{{ $listing->title }}"
                    >
                    <a href="{{ route('listings.show', $listing) }}" class="absolute inset-0" wire:navigate></a>
                </div>
                <div class="py-5 px-4">
                    <flux:heading class="text-xl/none font-semibold">${{ number_format($listing->price / 100) }}</flux:heading>
                    <flux:heading class="mt-3 text-lg! line-clamp-1 font-semibold">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                </div>
            </div>
        @endforeach
    </div>
</div>
