<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public User $user;

    #[Computed]
    public function listingsCount()
    {
        return $this->user->listings()->count();
    }

    #[Computed]
    public function conversationsStarted()
    {
        return $this->user->conversationsAsBuyer()->count();
    }
};
?>

<div>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            <flux:avatar
                :src="$user->profilePhotoUrl()"
                :name="$user->name"
                size="2xl"
                circle
            />

            <flux:heading size="xl">{{ $user->name }}</flux:heading>
        </div>

        <flux:button href="{{ route('cp.users.edit', $user) }}" wire:navigate>
            Edit
        </flux:button>
    </div>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <x-description.list class="w-full">
        <x-description.term>Email</x-description.term>
        <x-description.details>{{ $user->email }}</x-description.details>
        @if ($user->bio)
            <x-description.term>Bio</x-description.term>
            <x-description.details>{{ $user->bio }}</x-description.details>
        @endif

        <x-description.term>Listings Published</x-description.term>
        <x-description.details variant="strong">
            {{ $this->listingsCount }}
        </x-description.details>
        <x-description.term>Conversations Started</x-description.term>
        <x-description.details variant="strong">
            {{ $this->conversationsStarted }}
        </x-description.details>
        <x-description.term>Registered</x-description.term>
        <x-description.details>
            {{ $user->created_at->format("M j, Y") }}
        </x-description.details>
    </x-description.list>
</div>
