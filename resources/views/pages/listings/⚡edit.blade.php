<?php

use App\Models\Listing;
use App\Models\ListingPhoto;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.marketplace')] class extends Component
{
    use WithFileUploads;

    #[Locked]
    public Listing $listing;

    public string $tab = 'details';

    #[Validate('required', onUpdate: false)]
    public string $title = '';

    #[Validate('required', onUpdate: false)]
    public string $description = '';

    #[Validate('required', onUpdate: false)]
    public string $address = '';

    public string $addressLine2 = '';

    #[Validate('required|numeric|min:0', onUpdate: false)]
    public string $price = '';

    #[Validate(['photos.*' => 'image|max:10240'], onUpdate: false)]
    public array $photos = [];

    public array $existingPhotoIds = [];

    public string $addressSearch = '';

    public bool $editingAddress = false;

    public ?string $selectedAddressId = null;

    public array $addressSuggestions = [];

    public ?float $latitude = null;

    public ?float $longitude = null;

    public function mount(): void
    {
        if (! auth()->user()->is($this->listing->user)) {
            abort(403);
        }

        $this->title = $this->listing->title;
        $this->description = $this->listing->description;
        $this->address = $this->listing->address;
        $this->addressLine2 = $this->listing->address_line_2 ?? '';
        $this->price = (string) ($this->listing->price / 100);
        $this->existingPhotoIds = $this->listing->photos->pluck('id')->toArray();
        $this->latitude = $this->listing->latitude;
        $this->longitude = $this->listing->longitude;
    }

    public function removePhoto(int $index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function removeExistingPhoto(int $photoId): void
    {
        $photo = ListingPhoto::find($photoId);

        if ($photo && $photo->listing_id === $this->listing->id) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            $this->existingPhotoIds = array_values(array_diff($this->existingPhotoIds, [$photoId]));
        }
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
            'permanent' => 'true',
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

    public function startEditingAddress(): void
    {
        $this->addressSearch = $this->address;
        $this->editingAddress = true;
    }

    public function save(): void
    {
        $this->validate();

        $this->listing->update([
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'address_line_2' => $this->addressLine2 ?: null,
            'price' => (int) ($this->price * 100),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $existingPhotoCount = count($this->existingPhotoIds);

        foreach ($this->photos as $index => $photo) {
            $path = $photo->store('listings/'.$this->listing->id, 'public');

            $this->listing->photos()->create([
                'path' => $path,
                'order' => $existingPhotoCount + $index,
            ]);
        }

        Flux::toast('Your changes have been saved.', variant: 'success');
    }
};
?>

<div>
    <flux:tab.group>
        <flux:tabs wire:model="tab">
            <flux:tab name="details">Details</flux:tab>
            <flux:tab name="location">Location</flux:tab>
            <flux:tab name="pricing">Pricing</flux:tab>
            <flux:tab name="photos">Photos</flux:tab>
        </flux:tabs>
        <flux:tab.panel name="details" class="max-w-xl">
            <form wire:submit="save">
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
                <div class="mt-6">
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="location" class="max-w-xl">
            <form wire:submit="save">
                <flux:heading size="xl">Location</flux:heading>
                <div class="mt-6 space-y-6">
                    @if (!$editingAddress)
                        <flux:field>
                            <flux:label>Address</flux:label>
                            <div class="flex items-center gap-3">
                                <flux:input
                                    wire:model="address"
                                    readonly
                                />
                                <flux:button
                                    variant="ghost"
                                    type="button"
                                    wire:click="startEditingAddress"
                                >
                                    Edit
                                </flux:button>
                            </div>
                            <flux:error name="address" />
                        </flux:field>
                    @else
                        <flux:field>
                            <flux:label>Address</flux:label>
                            <div class="space-y-3">
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
                                <div class="flex gap-2">
                                    <flux:button
                                        variant="ghost"
                                        type="button"
                                        wire:click="$set('editingAddress', false)"
                                    >
                                        Cancel
                                    </flux:button>
                                    <flux:button
                                        variant="primary"
                                        type="button"
                                        wire:click="$set('editingAddress', false)"
                                    >
                                        Done
                                    </flux:button>
                                </div>
                            </div>
                            <flux:error name="address" />
                        </flux:field>
                    @endif

                    <flux:field>
                        <flux:label badge="Optional">
                            Apt, suite, building #
                        </flux:label>
                        <flux:input wire:model="addressLine2" />
                    </flux:field>
                </div>
                <div class="mt-6">
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="pricing" class="max-w-xl">
            <form wire:submit="save">
                <flux:heading size="xl">Pricing</flux:heading>
                <div class="mt-6 space-y-6">
                    <flux:field>
                        <flux:label>Price</flux:label>
                        <flux:input
                            wire:model="price"
                            placeholder="Enter price"
                        />
                        <flux:error name="price" />
                    </flux:field>
                </div>
                <div class="mt-6">
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="photos" class="max-w-xl">
            <form wire:submit="save">
                <flux:heading size="xl">Photos</flux:heading>

                @if ($listing->photos()->whereIn('id', $existingPhotoIds)->count() > 0)
                    <div class="mt-4">
                        <flux:label>Current Photos</flux:label>
                        <div class="mt-2 flex flex-col gap-2">
                            @foreach ($listing->photos()->whereIn('id', $existingPhotoIds)->get() as $photo)
                                <flux:file-item
                                    :heading="basename($photo->path)"
                                    :image="Storage::url($photo->path)"
                                >
                                    <x-slot name="actions">
                                        <flux:file-item.remove
                                            wire:click="removeExistingPhoto({{ $photo->id }})"
                                        />
                                    </x-slot>
                                </flux:file-item>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-6">
                    <flux:file-upload
                        wire:model="photos"
                        label="Add more photos"
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
                <div class="mt-6">
                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </flux:tab.panel>
    </flux:tab.group>
</div>
