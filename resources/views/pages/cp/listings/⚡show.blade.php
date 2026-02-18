<?php

use App\Models\Listing;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Listing $listing;

    public function mount(Listing $listing): void
    {
        $this->listing = $listing->load(['user', 'photos']);
    }

    #[Computed]
    public function price()
    {
        return '$'.number_format($this->listing->price);
    }

    #[Computed]
    public function createdAt()
    {
        return $this->listing->created_at->format('M j, Y');
    }
};
?>

<div>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            @if ($listing->photos->isNotEmpty())
                <flux:avatar
                    :src="Storage::url($listing->photos->first()->path)"
                    :name="$listing->title"
                    size="2xl"
                    circle
                />
            @endif

            <flux:heading size="xl">{{ $listing->title }}</flux:heading>
        </div>

        <flux:button href="{{ route('cp.listings.edit', $listing) }}" wire:navigate>
            Edit
        </flux:button>
    </div>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <x-description.list class="w-full">
        <x-description.term>Price</x-description.term>
        <x-description.details variant="strong">
            {{ $this->price }}
        </x-description.details>

        <x-description.term>Description</x-description.term>
        <x-description.details>
            {!! $listing->description !!}
        </x-description.details>

        <x-description.term>Address</x-description.term>
        <x-description.details>
            {{ $listing->address }}{{ $listing->address_line_2 ? ', ' . $listing->address_line_2 : '' }}
        </x-description.details>

        @if ($listing->latitude && $listing->longitude)
            <x-description.term>Coordinates</x-description.term>
            <x-description.details>
                {{ $listing->latitude }}, {{ $listing->longitude }}
            </x-description.details>
        @endif

        <x-description.term>Owner</x-description.term>
        <x-description.details>
            <flux:link href="{{ route('cp.users.show', $listing->user) }}" wire:navigate class="flex! items-center gap-2">
                <flux:avatar
                    :src="$listing->user->profilePhotoUrl()"
                    :name="$listing->user->name"
                    size="xs"
                    circle
                />
                {{ $listing->user->name }}
            </flux:link>
        </x-description.details>

        <x-description.term>Published</x-description.term>
        <x-description.details>
            {{ $this->createdAt }}
        </x-description.details>

        <x-description.term>Status</x-description.term>
        <x-description.details variant="{{ $listing->isClosed() ? 'danger' : 'success' }}">
            {{ $listing->isClosed() ? 'Closed' : 'Open' }}
        </x-description.details>

        @if ($listing->closed_at)
            <x-description.term>Closed At</x-description.term>
            <x-description.details>
                {{ $listing->closed_at->format('M j, Y') }}
            </x-description.details>
        @endif

        <x-description.term>Photos</x-description.term>
        <x-description.details variant="strong">
            {{ $listing->photos->count() }}
        </x-description.details>
    </x-description.list>
</div>
