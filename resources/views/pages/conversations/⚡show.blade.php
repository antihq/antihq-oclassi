<?php

use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    public Conversation $conversation;

    #[Validate('required|string|max:5000', onUpdate: false)]
    public string $body = '';

    public function mount(): void
    {
        Gate::authorize('view', $this->conversation);

        $this->markMessagesAsRead();
    }

    #[Computed]
    public function threadMessages()
    {
        return $this->conversation
            ->messages()
            ->with('sender')
            ->get();
    }

    #[Computed]
    public function otherUser()
    {
        return $this->conversation->otherUser(auth()->user());
    }

    public function send(): void
    {
        Gate::authorize('reply', $this->conversation);

        $this->validate();

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->conversation->touchLastMessageAt();

        $this->notifyOtherUser($message);

        $this->body = '';

        $this->conversation->refresh();
    }

    protected function markMessagesAsRead(): void
    {
        $this->conversation
            ->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    protected function notifyOtherUser(Message $message): void
    {
        $otherUser = $this->conversation->otherUser(auth()->user());

        $otherUser->notify(
            new NewMessageNotification($message, $this->conversation),
        );
    }
};
?>

<div wire:poll.5s class="flex justify-between gap-8">
    <div class="w-full max-w-2xl">
        @if (auth()->id() === $conversation->buyer_id)
            <flux:heading level="1" size="xl">You sent an inquiry</flux:heading>
        @else
            <div>
                <a href="{{ route('users.show', $this->otherUser) }}" wire:navigate>
                    <flux:avatar
                        :name="$this->otherUser->name"
                        size="xl"
                        circle
                    />
                </a>

                <flux:heading level="1" size="xl" class="mt-6">
                    @if (auth()->id() === $conversation->buyer_id)
                        You sent an inquiry
                    @else
                        You received an inquiry from
                        {{ $this->otherUser->name }}
                    @endif
                </flux:heading>
            </div>
        @endif

        <div class="mt-8">
            <flux:heading size="lg">Inquire message</flux:heading>

            <div class="mt-5 flex">
                @if (($message = $this->threadMessages->first())->wasSentBy(auth()->user()))
                    <flux:text
                        class="rounded-lg bg-blue-500 px-4 py-2 text-base font-medium text-white"
                    >
                        {{ $message->body }}
                    </flux:text>
                @else
                    <flux:text
                        class="rounded-lg bg-zinc-100 px-4 py-2 text-base font-medium text-zinc-800"
                    >
                        {{ $message->body }}
                    </flux:text>
                @endif
            </div>
        </div>

        <div class="mt-8">
            <flux:heading size="lg">Conversation</flux:heading>

            <div class="mt-6 space-y-4" wire:scroll-bottom>
                @foreach ($this->threadMessages->skip(1) as $message)
                    @if ($message->wasSentBy(auth()->user()))
                        <div class="flex flex-row-reverse gap-3">
                            <div class="max-w-lg">
                                <div class="rounded-2xl bg-blue-500 px-4 py-2 font-medium">
                                    <flux:text class="text-white">{{ $message->body }}</flux:text>
                                </div>
                                <flux:text class="mt-2 text-right text-xs">{{ $message->created_at->diffForHumans() }}</flux:text>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-3">
<a href="{{ route('users.show', $this->otherUser) }}" wire:navigate>
                                <flux:avatar :name="$this->otherUser->name" size="md" circle />
                            </a>
                            <div class="max-w-lg">
                                <div class="rounded-2xl bg-zinc-100 px-4 py-2 font-medium">
                                    <flux:text class="text-zinc-800">{{ $message->body }}</flux:text>
                                </div>
                                <flux:text class="mt-2 text-xs">{{ $message->created_at->diffForHumans() }}</flux:text>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <form wire:submit="send" class="mt-8 border-t border-zinc-200 pt-4">
            <flux:composer
                wire:model="body"
                inline
                placeholder="Type your message..."
                rows="1"
            >
                <x-slot name="actionsTrailing">
                    <flux:button
                        type="submit"
                        size="sm"
                        variant="primary"
                        color="blue"
                        icon="paper-airplane"
                    />
                </x-slot>
            </flux:composer>
        </form>
    </div>

    <div class="w-full max-w-xs shrink-0 self-start border border-zinc-200">
        @if ($conversation->listing)
            @if ($conversation->listing->photos->first())
                <img
                    src="{{ Storage::url($conversation->listing->photos->first()->path) }}"
                    alt="{{ $conversation->listing->title }}"
                />
            @endif

            <div class="px-6 pb-6">
                @if (auth()->id() === $conversation->buyer_id)
                    <flux:avatar
                        :name="$this->otherUser->name"
                        size="xl"
                        class="-mt-6"
                        circle
                    />
                @endif

                <flux:heading level="2" size="lg" class="mt-6">
                    <flux:link
                        :href="route('listings.show', $conversation->listing)"
                        variant="ghost"
                        wire:navigate
                    >
                        {{ $conversation->listing->title }}
                    </flux:link>
                </flux:heading>

                <flux:heading class="mt-4">
                    ${{ number_format($conversation->listing->price) }}
                </flux:heading>
            </div>
        @endif
    </div>
</div>
