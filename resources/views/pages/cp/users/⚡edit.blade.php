<?php

use App\Models\User;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Locked]
    public User $user;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:500')]
    public ?string $bio = null;

    public function mount(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->bio = $this->user->bio;
    }

    public function update(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user->id),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'bio' => $this->bio,
        ]);

        Flux::toast('User updated successfully.', variant: 'success');

        $this->redirectRoute('cp.users.show', $this->user);
    }
};
?>

<div>
    <flux:heading size="xl">Edit user</flux:heading>

    <flux:separator class="mt-6 mb-8" variant="subtle" />

    <form wire:submit="update" class="max-w-2xl">
        <div class="space-y-6">
            <div class="flex items-center gap-6">
                <flux:avatar
                    :src="$user->profilePhotoUrl()"
                    :name="$user->name"
                    size="xl"
                    circle
                />
                <div>
                    <flux:heading size="lg">Profile Photo</flux:heading>
                    <flux:text class="mt-1">View only</flux:text>
                </div>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" required autofocus autocomplete="name" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" required autocomplete="email" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label badge="Optional">Bio</flux:label>
                <flux:textarea wire:model="bio" rows="4" />
                <flux:error name="bio" />
            </flux:field>
        </div>

        <div class="mt-8 flex gap-3">
            <flux:spacer />
            <flux:button href="{{ route('cp.users.show', $user) }}" wire:navigate variant="ghost">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
    </form>
</div>
