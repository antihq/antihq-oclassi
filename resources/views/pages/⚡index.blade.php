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

    #[Url]
    public string $search = '';

    #[Url]
    public string $priceRange = 'all';

    #[Url]
    public string $locationSearch = '';

    #[Url]
    public ?string $selectedLocationId = null;

    public array $locationSuggestions = [];

    #[Url]
    public ?string $bounds = null;

    public ?string $selectedLocationName = null;

    public function updatedLocationSearch(): void
    {
        if (strlen($this->locationSearch) < 3) {
            $this->locationSuggestions = [];

            return;
        }

        $response = Http::get('https://api.mapbox.com/search/geocode/v6/forward', [
            'q' => $this->locationSearch,
            'access_token' => config('services.mapbox.access_token'),
            'autocomplete' => 'true',
            'limit' => '5',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->locationSuggestions = collect($data['features'] ?? [])->map(function ($feature) {
                return [
                    'id' => $feature['id'],
                    'full_address' => $feature['properties']['full_address'] ?? $feature['properties']['name'],
                    'address' => $feature['properties']['name'],
                    'latitude' => $feature['properties']['coordinates']['latitude'],
                    'longitude' => $feature['properties']['coordinates']['longitude'],
                    'bbox' => $feature['properties']['bbox'] ?? null,
                ];
            })->toArray();
        } else {
            $this->locationSuggestions = [];
        }
    }

    public function updatedSelectedLocationId(): void
    {
        $selected = collect($this->locationSuggestions)->firstWhere('id', $this->selectedLocationId);

        if ($selected) {
            $this->selectedLocationName = $selected['full_address'];

            if ($selected['bbox']) {
                $west = $selected['bbox'][0];
                $south = $selected['bbox'][1];
                $east = $selected['bbox'][2];
                $north = $selected['bbox'][3];
                $this->bounds = "{$north},{$west},{$south},{$east}";
            } else {
                $this->bounds = null;
            }
        }
    }

    public function clearLocation(): void
    {
        $this->selectedLocationId = null;
        $this->locationSearch = '';
        $this->bounds = null;
        $this->selectedLocationName = null;
        $this->locationSuggestions = [];
    }

    #[Computed]
    public function listings()
    {
        $query = Listing::query()->with(['photos', 'user'])->open();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        $ranges = [
            'under_100' => [0, 10000],
            '100_500' => [10000, 50000],
            '500_2000' => [50000, 200000],
            '2000_plus' => [200000, PHP_INT_MAX],
        ];

        if ($this->priceRange !== 'all' && isset($ranges[$this->priceRange])) {
            $query->whereBetween('price', $ranges[$this->priceRange]);
        }

        if ($this->bounds) {
            [$north, $west, $south, $east] = explode(',', $this->bounds);
            $query->whereBetween('latitude', [$south, $north])
                ->whereBetween('longitude', [$west, $east]);
        }

        return match ($this->sort) {
            'oldest' => $query->oldest()->get(),
            'price_low' => $query->orderBy('price')->get(),
            'price_high' => $query->orderByDesc('price')->get(),
            default => $query->latest()->get(),
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

    <div class="flex mt-6 gap-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search listings..." clearable />
        </div>

        <div>
            <flux:select wire:model.live="priceRange" placeholder="All Prices">
                <flux:select.option value="all">All Prices</flux:select.option>
                <flux:select.option value="under_100">Under $100</flux:select.option>
                <flux:select.option value="100_500">$100 – $500</flux:select.option>
                <flux:select.option value="500_2000">$500 – $2,000</flux:select.option>
                <flux:select.option value="2000_plus">$2,000+</flux:select.option>
            </flux:select>
        </div>

        <div>
            <flux:select
                wire:model="selectedLocationId"
                variant="combobox"
                :filter="false"
                placeholder="Location"
            >
                <x-slot name="input">
                    <flux:select.input
                        wire:model.live="locationSearch"
                        placeholder="Search city, country..."
                    />
                </x-slot>
                @foreach ($this->locationSuggestions as $suggestion)
                    <flux:select.option
                        value="{{ $suggestion['id'] }}"
                        wire:key="{{ $suggestion['id'] }}"
                    >
                        {{ $suggestion['full_address'] }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            @if ($this->selectedLocationName)
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-sm text-gray-500">{{ $this->selectedLocationName }}</span>
                    <flux:button
                        variant="ghost"
                        wire:click="clearLocation"
                        size="sm"
                    >
                        Clear
                    </flux:button>
                </div>
            @endif
        </div>
    </div>

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
