<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    #[Computed]
    public function users()
    {
        return User::query()
            ->withCount('listings')
            ->latest()
            ->paginate(10);
    }
};
?>

<div>
    <flux:table :paginate="$this->users">
        <flux:table.columns>
            <flux:table.column>User</flux:table.column>
            <flux:table.column>Listings</flux:table.column>
            <flux:table.column>Registered</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->users as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell class="flex items-center gap-3">
                        <flux:avatar size="xs" :src="$user->profilePhotoUrl()" :name="$user->name" circle />
                        <flux:link href="{{ route('users.show', $user) }}" wire:navigate>
                            {{ $user->name }}
                        </flux:link>
                    </flux:table.cell>
                    <flux:table.cell variant="strong">
                        {{ $user->listings_count }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        {{ $user->created_at->format('M j, Y') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>