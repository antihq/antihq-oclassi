<?php

use App\Models\Listing;
use Flux\Flux;
use Illuminate\Support\Facades\Http;
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

    public function submitSearch(): void
    {
        Flux::modal('search')->close();
    }

    public function resetFilters(): void
    {
        $this->sort = 'newest';
        $this->search = '';
        $this->priceRange = 'all';
        $this->selectedLocationId = null;
        $this->locationSearch = '';
        $this->bounds = null;
        $this->selectedLocationName = null;
        $this->locationSuggestions = [];
    }

    #[Computed]
    public function activeFiltersCount(): int
    {
        $count = 0;

        if ($this->sort !== 'newest') {
            $count++;
        }

        if ($this->search !== '') {
            $count++;
        }

        if ($this->priceRange !== 'all') {
            $count++;
        }

        if ($this->selectedLocationId !== null) {
            $count++;
        }

        return $count;
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
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <flux:heading size="xl">
                @if($this->listings->isEmpty())
                    No listings yet
                @else
                    {{ $this->listings->count() }} {{ Str::plural('listing', $this->listings->count()) }}
                @endif
            </flux:heading>

            @if($this->activeFiltersCount > 0)
                <flux:badge as="button" wire:click="resetFilters" class="ml-2">
                    {{ $this->activeFiltersCount }} {{ Str::plural('filter', $this->activeFiltersCount) }}
                    <flux:icon.x-mark variant="micro" class="ml-1" />
                </flux:badge>
            @endif
        </div>

        <flux:spacer />

        <div>
            <flux:modal.trigger name="search">
                <flux:input as="button" icon="magnifying-glass" placeholder="Search listings..." class="w-xs" />
            </flux:modal.trigger>
        </div>

        <div>
            <flux:dropdown>
                <flux:button icon="funnel">Filters</flux:button>
                <flux:menu>
                    <flux:menu.submenu heading="Sort">
                        <flux:menu.radio.group wire:model.live="sort">
                            <flux:menu.radio value="newest">Newest</flux:menu.radio>
                            <flux:menu.radio value="oldest">Oldest</flux:menu.radio>
                            <flux:menu.radio value="price_low">Lowest</flux:menu.radio>
                            <flux:menu.radio value="price_high">Highest</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu.submenu>
                    <flux:menu.separator />
                    <flux:menu.submenu heading="Price">
                        <flux:menu.radio.group wire:model.live="priceRange">
                            <flux:menu.radio value="all">All</flux:menu.radio>
                            <flux:menu.radio value="under_100">Under $100</flux:menu.radio>
                            <flux:menu.radio value="100_500">$100 – $500</flux:menu.radio>
                            <flux:menu.radio value="500_2000">$500 – $2,000</flux:menu.radio>
                            <flux:menu.radio value="2000_plus">$2,000+</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu.submenu>
                    <flux:menu.separator />
                    <flux:menu.item wire:click="resetFilters">Reset</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <flux:modal name="search" class="w-full max-w-[30rem]">
        <form wire:submit="submitSearch" class="space-y-4">
            <flux:heading size="lg">Search Listings</flux:heading>

            <flux:input
                wire:model="search"
                icon="magnifying-glass"
                placeholder="Search listings..."
                clearable
            />

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

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">Search</flux:button>
            </div>
        </form>
    </flux:modal>

    <div class="mt-6 grid grid-cols-3 gap-6">
        @foreach($this->listings as $listing)
            <div class="rounded-xl shadow-sm overflow-hidden group hover:shadow-md transition-shadow bg-white">
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
                    <flux:heading class="mt-4 text-lg/none! line-clamp-1 font-semibold">
                        <flux:link href="{{ route('listings.show', $listing) }}" variant="ghost" wire:navigate>{{ $listing->title }}</flux:link>
                    </flux:heading>
                    <div class="text-sm/none mt-4">
                        <flux:link href="{{ route('users.show', $listing->user) }}" variant="subtle" wire:navigate class="font-normal">{{ $listing->user->name }}</flux:link>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
