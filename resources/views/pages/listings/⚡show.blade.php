<?php

use App\Models\Listing;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Locked]
    public Listing $listing;
};
?>

<div class="flex justify-between gap-8">
    <div class="flex-1">
        @if (auth()->check() &&auth()->user()->is($listing->user))
            <flux:callout variant="secondary" class="mb-4" inline>
                <flux:callout.heading>
                    This is your own listing.
                </flux:callout.heading>
                <x-slot name="actions">
                    <flux:button :href="route('listings.edit', $listing)">
                        Edit listing
                    </flux:button>
                </x-slot>
            </flux:callout>
        @endif

        <div
            x-data="{
                activeIndex: 0,
                containerBounds: { left: 0, right: 0, width: 0 },
                opacities: {},
                open: false,
                currentIndex: 0,
                images: [
                    @foreach ($listing->photos as $photo)
                        {
                            thumb: '{{ Storage::url($photo->path) }}',
                            full: '{{ Storage::url($photo->path) }}',
                            alt: '{{ $listing->title }}',
                        },
                    @endforeach
                ],
                openLightbox(index) {
                    this.currentIndex = index;
                    this.open = true;
                    document.body.classList.add('overflow-hidden');
                },
                closeLightbox() {
                    this.open = false;
                    document.body.classList.remove('overflow-hidden');
                },
                updateContainerBounds() {
                    const container = this.$refs.scrollContainer
                    const rect = container.getBoundingClientRect()
                    this.containerBounds = {
                        left: rect.left,
                        right: rect.right,
                        width: rect.width,
                    }
                },
                computeOpacity(element) {
                    if (! element || this.containerBounds.width === 0) return 1
                    const rect = element.getBoundingClientRect()
                    if (rect.left < this.containerBounds.left) {
                        const diff = this.containerBounds.left - rect.left
                        const percent = diff / rect.width
                        return Math.max(0.5, 1 - percent)
                    } else if (rect.right > this.containerBounds.right) {
                        const diff = rect.right - this.containerBounds.right
                        const percent = diff / rect.width
                        return Math.max(0.5, 1 - percent)
                    } else {
                        return 1
                    }
                },
                updateAllOpacities() {
                    const container = this.$refs.scrollContainer
                    Array.from(container.children).forEach((element, index) => {
                        if (element.tagName === 'IMG') {
                            this.opacities[index] = this.computeOpacity(element)
                        }
                    })
                },
                updateActiveIndex() {
                    const container = this.$refs.scrollContainer
                    const scrollLeft = container.scrollLeft
                    const cardWidth = container.children[0].offsetWidth
                    const gap = 16
                    this.activeIndex = Math.round(scrollLeft / (cardWidth + gap))
                },
                scrollTo(index) {
                    const container = this.$refs.scrollContainer
                    const cardWidth = container.children[0].offsetWidth
                    const gap = 16
                    container.scrollTo({
                        left: (cardWidth + gap) * index,
                        behavior: 'smooth',
                    })
                },
            }"
            x-init="
                updateContainerBounds()
                updateAllOpacities()
            "
            window:resize="updateContainerBounds(); updateAllOpacities();"
            x-on:keydown.escape.window="closeLightbox()"
        >
            <div
                x-ref="scrollContainer"
                x-on:scroll="
                    updateActiveIndex()
                    updateAllOpacities()
                "
                class="flex snap-x snap-mandatory gap-4 overflow-x-auto overflow-y-hidden scroll-smooth [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
            >
                @foreach ($listing->photos as $photo)
                    <button
                        x-on:click="openLightbox({{ $loop->index }})"
                        type="button"
                        class="shrink-0 snap-start"
                    >
                        <img
                            src="{{ Storage::url($photo->path) }}"
                            alt="{{ $listing->title }}"
                            :style="'opacity: ' + (opacities[{{ $loop->index }}] ?? 1)"
                            class="h-72 w-72 rounded-lg object-cover sm:h-96 sm:w-96 border border-zinc-200"
                        />
                    </button>
                @endforeach

                <div class="w-64 shrink-0 sm:w-96"></div>
            </div>
            <div class="mt-4 flex justify-center gap-2">
                @foreach ($listing->photos as $photo)
                    <button
                        x-on:click="scrollTo({{ $loop->index }})"
                        class="size-2.5 rounded-full bg-zinc-300 transition"
                        :class="activeIndex === {{ $loop->index }} ? 'bg-zinc-600' : 'hover:bg-zinc-400'"
                    ></button>
                @endforeach
            </div>

            <template x-teleport="body">
                <div
                    x-show="open"
                    x-transition:enter="transition duration-300 ease-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="fixed inset-0 z-100 flex items-center justify-center bg-black/90 p-12 backdrop-blur-sm sm:p-16"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Image lightbox"
                    x-on:click.self="closeLightbox()"
                >
                    <button
                        x-on:click="closeLightbox()"
                        type="button"
                        class="absolute end-4 top-4 z-10 rounded-full bg-white/10 p-2 text-white/80 backdrop-blur-xs transition hover:bg-white/20 hover:text-white"
                        aria-label="Close lightbox"
                    >
                        <flux:icon.x-mark />
                    </button>

                    <template
                        x-for="(image, index) in images"
                        x-bind:key="index"
                    >
                        <img
                            x-bind:src="image.full"
                            x-bind:alt="image.alt"
                            x-show="currentIndex === index"
                            class="max-h-full w-full max-w-full object-contain"
                        />
                    </template>
                </div>
            </template>
        </div>

        <div class="mt-12">
            {!! $listing->description !!}
        </div>

        <div class="mt-12">
            <flux:heading level="h2" size="lg">Location</flux:heading>
            @if($listing->latitude && $listing->longitude)
                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200">
                    <img
                        src="https://api.mapbox.com/styles/v1/mapbox/streets-v12/static/pin-s+285A98({{ $listing->longitude }},{{ $listing->latitude }})/{{ $listing->longitude }},{{ $listing->latitude }},14,0,0/640x320?access_token={{ config('services.mapbox.access_token') }}"
                        alt="Map showing location of {{ $listing->title }}"
                        class="w-full rounded-lg"
                        loading="lazy"
                    />
                </div>
            @endif
        </div>

        <div class="mt-12">
            <flux:heading level="h2" size="lg">
                About the listing author
            </flux:heading>
            <div class="mt-6 flex gap-8">
                <flux:avatar
                    :src="$listing->user->profilePhotoUrl()"
                    :name="$listing->user->name"
                    size="xl"
                    circle
                />
                <div class="flex-1">
                    <div class="flex justify-between gap-8">
                        <flux:text class="text-base">
                            {{ $listing->user->bio ?: "Hello, I'm {$listing->user->name}" }}
                        </flux:text>
                        <flux:button :href="route('profile.edit')">
                            Edit profile
                        </flux:button>
                    </div>
                    <flux:button :href="route('profile.edit')" class="mt-6">
                        View profile
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    <div class="w-xs">
        <flux:heading level="1" size="xl">{{ $listing->title }}</flux:heading>
        <flux:heading size="lg" class="mt-1">
            ${{ number_format($listing->price) }}
        </flux:heading>
        <div class="mt-5 flex items-center gap-2">
            <flux:avatar
                :src="$listing->user->profilePhotoUrl()"
                :name="$listing->user->name"
                circle
            />
            <flux:link
                :href="route('users.show', $listing->user)"
                wire:navigate
                variant="ghost"
            >
                <flux:text variant="strong">
                    {{ $listing->user->name }}
                </flux:text>
            </flux:link>
        </div>

        <div class="mt-12 text-center">
            @if (auth()->check() &&auth()->user()->is($listing->user))
                <flux:button
                    variant="primary"
                    color="green"
                    class="w-full"
                    disabled
                >
                    Send an inquiry
                </flux:button>
                <flux:text class="mt-2">This is your own listing.</flux:text>
            @else
                <flux:button
                    variant="primary"
                    color="green"
                    class="w-full"
                    :href="route('listings.conversations.create', $listing)"
                >
                    Send an inquiry
                </flux:button>
            @endif
        </div>
    </div>
</div>
