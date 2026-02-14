<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout("layouts.marketplace")] class extends Component {
    use WithFileUploads;

    public string $tab = "details";

    public string $title = "";

    public string $description = "";

    public string $address = "";

    public string $addressLine2 = "";

    public string $price = "";

    #[Validate(["photos.*" => "image|max:10240"])]
    public array $photos = [];

    public function removePhoto(int $index): void
    {
        $this->photos[$index]?->delete();
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
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
            <flux:heading size="xl">Listing Details</flux:heading>
            <div class="mt-6 space-y-6">
                <flux:field>
                    <flux:label>Title</flux:label>
                    <flux:input
                        wire:model="title"
                        placeholder="Enter listing title"
                    />
                    <flux:error name="title" />
                </flux:field>

                <flux:editor
                    wire:model="description"
                    label="Description"
                    placeholder="Describe your listing..."
                />
            </div>
            <div class="mt-6">
                <flux:button wire:click="$set('tab', 'location')">
                    Next
                </flux:button>
            </div>
        </flux:tab.panel>
        <flux:tab.panel name="location"  class="max-w-xl">
            <flux:heading size="xl">Location</flux:heading>
            <div class="mt-6 space-y-6">
                <flux:field>
                    <flux:label>Address</flux:label>
                    <flux:input
                        wire:model="address"
                        placeholder="Street address"
                    />
                    <flux:error name="address" />
                </flux:field>

                <flux:field>
                    <flux:label badge="Optional">
                        Apt, suite, building #
                    </flux:label>
                    <flux:input wire:model="addressLine2" />
                    <flux:error name="addressLine2" />
                </flux:field>
            </div>
            <div class="mt-6">
                <flux:button wire:click="$set('tab', 'pricing')">
                    Next
                </flux:button>
            </div>
        </flux:tab.panel>
        <flux:tab.panel name="pricing"  class="max-w-xl">
            <flux:heading size="xl">Pricing</flux:heading>
            <div class="mt-6 space-y-6">
                <flux:field>
                    <flux:label>Price</flux:label>
                    <flux:input wire:model="price" placeholder="Enter price" />
                    <flux:error name="price" />
                </flux:field>
            </div>
            <div class="mt-6">
                <flux:button wire:click="$set('tab', 'photos')">
                    Next
                </flux:button>
            </div>
        </flux:tab.panel>
        <flux:tab.panel name="photos"  class="max-w-xl">
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
            </div>
            <div class="mt-6">
                <flux:button variant="primary">Publish Listing</flux:button>
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</div>
