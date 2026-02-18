<?php

use App\Models\Listing;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Locked]
    public Listing $listing;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|string|max:255')]
    public string $address = '';

    #[Validate('nullable|string|max:255')]
    public ?string $addressLine2 = null;

    #[Validate('required|numeric|min:0')]
    public string $price = '';

    public function mount(): void
    {
        $this->title = $this->listing->title;
        $this->description = $this->listing->description;
        $this->address = $this->listing->address;
        $this->addressLine2 = $this->listing->address_line_2 ?? '';
        $this->price = (string) ($this->listing->price / 100);
    }

    public function update(): void
    {
        $this->validate();

        $this->listing->update([
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'address_line_2' => $this->addressLine2 ?: null,
            'price' => (int) ($this->price * 100),
        ]);

        Flux::toast('Listing updated successfully.', variant: 'success');

        $this->redirectRoute('cp.listings.show', $this->listing);
    }
};
?>

<div>
    <flux:heading size="xl">Edit listing</flux:heading>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <form wire:submit="update" class="max-w-2xl">
        <div class="space-y-6">
            <div class="flex items-center gap-6">
                @if ($listing->photos->isNotEmpty())
                    <flux:avatar
                        :src="Storage::url($listing->photos->first()->path)"
                        :name="$listing->title"
                        size="xl"
                        circle
                    />
                @else
                    <flux:avatar :name="$listing->title" size="xl" circle />
                @endif
                <div>
                    <flux:heading size="lg">Listing Photo</flux:heading>
                    <flux:text class="mt-1">View only</flux:text>
                </div>
            </div>

            <flux:field>
                <flux:label>Title</flux:label>
                <flux:input wire:model="title" type="text" required autofocus autocomplete="title" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Description</flux:label>
                <flux:editor wire:model="description" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Address</flux:label>
                <flux:input wire:model="address" type="text" required autocomplete="street-address" />
                <flux:error name="address" />
            </flux:field>

            <flux:field>
                <flux:label badge="Optional">Address Line 2</flux:label>
                <flux:input wire:model="addressLine2" type="text" />
                <flux:error name="addressLine2" />
            </flux:field>

            <flux:field>
                <flux:label>Price</flux:label>
                <flux:input wire:model="price" type="text" required autocomplete="off" />
                <flux:error name="price" />
            </flux:field>
        </div>

        <div class="mt-8 flex gap-3">
            <flux:spacer />
            <flux:button href="{{ route('cp.listings.show', $listing) }}" wire:navigate variant="ghost">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
    </form>
</div>
