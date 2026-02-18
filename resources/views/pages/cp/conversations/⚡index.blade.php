<?php

use App\Models\Conversation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    #[Computed]
    public function conversations()
    {
        return Conversation::query()
            ->with(['listing.photos', 'buyer', 'seller', 'latestMessage'])
            ->latest('last_message_at')
            ->paginate(10);
    }
};
?>

<div>
    <flux:heading size="xl">Conversations</flux:heading>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <flux:table :paginate="$this->conversations">
        <flux:table.columns>
            <flux:table.column>Conversation</flux:table.column>
            <flux:table.column>Started</flux:table.column>
            <flux:table.column>Last Message</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->conversations as $conversation)
                <flux:table.row :key="$conversation->id">
                    <flux:table.cell class="flex gap-3">
                        @if ($conversation->listing && $conversation->listing->photos->isNotEmpty())
                            <flux:avatar
                                size="xs"
                                :src="Storage::url($conversation->listing->photos->first()->path)"
                                :name="$conversation->listing->title"
                            />
                        @endif
                        <div class="flex flex-col">
                            @if ($conversation->listing)
                                <flux:link href="{{ route('listings.show', $conversation->listing) }}" wire:navigate>
                                    {{ $conversation->listing->title }}
                                </flux:link>
                            @endif
                            @if ($conversation->buyer)
                                <flux:text class="mt-2 text-xs">
                                    Started by {{ $conversation->buyer->name }}
                                </flux:text>
                            @endif
                        </div>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        {{ $conversation->created_at->format('M j, Y') }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        @if ($conversation->last_message_at)
                            {{ $conversation->last_message_at->format('M j, Y') }}
                        @else
                            <flux:text class="text-zinc-400">-</flux:text>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        <flux:button :href="route('cp.conversations.show', $conversation)" wire:navigate size="sm" variant="ghost">
                            View
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
