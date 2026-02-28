<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.marketplace')] class extends Component
{
    use WithFileUploads;

    public string $tab = 'details';

    #[Validate('required', onUpdate: false)]
    public string $title = '';

    #[Validate('required', onUpdate: false)]
    public string $description = '';

    #[Validate('required', onUpdate: false)]
    public string $address = '';

    public string $addressLine2 = '';

    #[Validate('required', onUpdate: false)]
    public string $price = '';

    #[Validate(['photos.*' => 'image|max:10240'], onUpdate: false)]
    public array $photos = [];

    public string $addressSearch = '';

    public ?string $selectedAddressId = null;

    public array $addressSuggestions = [];

    public ?float $latitude = null;

    public ?float $longitude = null;

    public array $completedSteps = [];

    public function nextStep(string $nextTab): void
    {
        $rules = match ($this->tab) {
            'details' => [
                'title' => 'required',
                'description' => 'required',
            ],
            'location' => ['address' => 'required'],
            'pricing' => ['price' => 'required'],
            default => [],
        };

        $this->validate($rules);

        $this->completedSteps[] = $this->tab;
        $this->tab = $nextTab;
    }

    public function canAccessTab(string $tab): bool
    {
        $tabOrder = ['details', 'location', 'pricing', 'photos'];
        $tabIndex = array_search($tab, $tabOrder);
        $currentIndex = array_search($this->tab, $tabOrder);

        return $tabIndex <= $currentIndex ||
            in_array($tabOrder[$tabIndex - 1] ?? null, $this->completedSteps);
    }

    public function removePhoto(int $index): void
    {
        $this->photos[$index]?->delete();
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function updatedAddressSearch(): void
    {
        if (strlen($this->addressSearch) < 3) {
            $this->addressSuggestions = [];

            return;
        }

        $response = Http::get('https://api.mapbox.com/search/geocode/v6/forward', [
            'q' => $this->addressSearch,
            'access_token' => config('services.mapbox.access_token'),
            'autocomplete' => 'true',
            'limit' => '5',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->addressSuggestions = collect($data['features'] ?? [])->map(function ($feature) {
                return [
                    'id' => $feature['id'],
                    'full_address' => $feature['properties']['full_address'] ?? $feature['properties']['name'],
                    'address' => $feature['properties']['name'],
                    'latitude' => $feature['properties']['coordinates']['latitude'],
                    'longitude' => $feature['properties']['coordinates']['longitude'],
                ];
            })->toArray();
        } else {
            $this->addressSuggestions = [];
        }
    }

    public function updatedSelectedAddressId(): void
    {
        $selected = collect($this->addressSuggestions)->firstWhere('id', $this->selectedAddressId);

        if ($selected) {
            $this->address = $selected['address'];
            $this->latitude = $selected['latitude'];
            $this->longitude = $selected['longitude'];
        }
    }

    public function publish(): void
    {
        $this->validate();

        $listing = auth()->user()->listings()->create([
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'address_line_2' => $this->addressLine2 ?: null,
            'price' => (int) (floatval(preg_replace('/[^\d.]/', '', $this->price)) * 100),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        foreach ($this->photos as $index => $photo) {
            $path = $photo->store('listings/'.$listing->id, 'public');

            $listing->photos()->create([
                'path' => $path,
                'order' => $index,
            ]);
        }

        $this->redirect(route('listings.show', $listing));
    }
};
?>

<div class="w-full max-w-xl mx-auto">
    <flux:tab.group>
        <flux:tabs wire:model="tab">
            <flux:tab name="details">Details</flux:tab>
            <flux:tab
                name="location"
                :disabled="!$this->canAccessTab('location')"
            >
                Location
            </flux:tab>
            <flux:tab
                name="pricing"
                :disabled="!$this->canAccessTab('pricing')"
            >
                Pricing
            </flux:tab>
            <flux:tab name="photos" :disabled="!$this->canAccessTab('photos')">
                Photos
            </flux:tab>
        </flux:tabs>
        <flux:tab.panel name="details" class="max-w-xl">
            <form wire:submit="nextStep('location')">
                <flux:heading size="xl">Listing details</flux:heading>
                <div class="mt-6 space-y-6">
                    <flux:field>
                        <flux:label>Title</flux:label>
                        <flux:input
                            wire:model="title"
                            placeholder="Enter listing title"
                        />
                        <flux:error name="title" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Description</flux:label>
                        <flux:editor
                            wire:model="description"
                            placeholder="Describe your listing..."
                        />
                        <flux:error name="description" />
                    </flux:field>
                </div>
                <div class="mt-6 flex gap-3">
                    <flux:spacer />
                    <flux:button type="submit">Next</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="location" class="max-w-xl">
            <form wire:submit="nextStep('pricing')">
                <flux:heading size="xl">Location</flux:heading>
                <div class="mt-6 space-y-6">
                    <flux:field>
                        <flux:label>Address</flux:label>
                        <flux:select
                            wire:model="selectedAddressId"
                            variant="combobox"
                            :filter="false"
                        >
                            <x-slot name="input">
                                <flux:select.input
                                    wire:model.live="addressSearch"
                                    placeholder="Search address..."
                                />
                            </x-slot>
                            @foreach ($this->addressSuggestions as $suggestion)
                                <flux:select.option
                                    value="{{ $suggestion['id'] }}"
                                    wire:key="{{ $suggestion['id'] }}"
                                >
                                    {{ $suggestion['full_address'] }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="address" />
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Optional">
                            Apt, suite, building #
                        </flux:label>
                        <flux:input wire:model="addressLine2" />
                    </flux:field>
                </div>
                <div class="mt-6 flex gap-3">
                    <flux:spacer />
                    <flux:button type="submit">Next</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="pricing" class="max-w-xl">
            <form wire:submit="nextStep('photos')">
                <flux:heading size="xl">Pricing</flux:heading>
                <div class="mt-6 space-y-6">
                    <flux:field>
                        <flux:label>Price</flux:label>
                        <flux:input
                            wire:model="price"
                            mask:dynamic="$money($input)"
                            placeholder="$0.00"
                        />
                        <flux:error name="price" />
                    </flux:field>
                </div>
                <div class="mt-6 flex gap-3">
                    <flux:spacer />
                    <flux:button type="submit">Next</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="photos" class="max-w-xl">
            <form wire:submit="publish">
                <flux:heading size="xl">Photos</flux:heading>
                <div class="mt-6">
                    <flux:file-upload
                        wire:model="photos"
                        label="Upload photos"
                        multiple
                    >
                        <flux:file-upload.dropzone
                            heading="Drop photos here or click to browse"
                            text="JPG, PNG, GIF up to 10MB"
                        />
                    </flux:file-upload>
                    <div class="mt-3 flex flex-col gap-2">
                        @foreach ($photos as $index => $photo)
                            <flux:file-item
                                :heading="$photo->getClientOriginalName()"
                                :image="$photo->temporaryUrl()"
                                :size="$photo->getSize()"
                            >
                                <x-slot name="actions">
                                    <flux:file-item.remove
                                        wire:click="removePhoto({{ $index }})"
                                    />
                                </x-slot>
                            </flux:file-item>
                        @endforeach
                    </div>
                    <flux:error name="photos" />
                </div>
                <div class="mt-6 flex gap-3">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">
                        Publish Listing
                    </flux:button>
                </div>
            </form>
        </flux:tab.panel>
    </flux:tab.group>
</div>
