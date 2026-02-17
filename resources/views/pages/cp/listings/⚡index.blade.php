<?php

use App\Models\Listing;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function listings()
    {
        return Listing::query()
            ->with('photos')
            ->latest()
            ->paginate(10);
    }
};
?>

<div>
    <flux:heading size="xl">Listings</flux:heading>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <flux:table :paginate="$this->listings">
        <flux:table.columns>
            <flux:table.column>Listing</flux:table.column>
            <flux:table.column>Price</flux:table.column>
            <flux:table.column>Published</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->listings as $listing)
                <flux:table.row :key="$listing->id">
                    <flux:table.cell class="flex items-center gap-3">
                        @if ($listing->photos->isNotEmpty())
                            <flux:avatar
                                size="xs"
                                :src="Storage::url($listing->photos->first()->path)"
                                :name="$listing->title"
                            />
                        @endif
                        <flux:link href="{{ route('listings.show', $listing) }}" wire:navigate>
                            {{ $listing->title }}
                        </flux:link>
                    </flux:table.cell>
                    <flux:table.cell variant="strong">
                        ${{ number_format($listing->price) }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        {{ $listing->created_at->format('M j, Y') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
