<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.marketplace')] class extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $photo = null;

    public ?string $bio = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->bio = Auth::user()->bio;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function updateProfilePhoto(): void
    {
        $this->validate([
            'photo' => 'nullable|image|max:10240',
        ]);

        $user = Auth::user();

        if ($user->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
        }

        if ($this->photo) {
            $path = $this->photo->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        $this->photo = null;

        Session::flash('status', 'profile-photo-updated');
    }

    public function removeProfilePhoto(): void
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        Session::flash('status', 'profile-photo-removed');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfilePhoto" class="my-6 w-full space-y-6">
            <div class="flex items-center gap-6">
                <flux:avatar
                    :src="auth()->user()->profilePhotoUrl()"
                    :name="auth()->user()->name"
                    size="xl"
                    circle
                />
                <div class="flex-1">
                    <flux:heading size="lg">{{ __('Profile Photo') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('JPG, PNG or GIF. Max 10MB.') }}</flux:text>
                </div>
            </div>

            @if ($photo && $photo->isPreviewable())
                <flux:file-item
                    :heading="$photo->getClientOriginalName()"
                    :image="$photo->temporaryUrl()"
                    :size="$photo->getSize()"
                >
                    <x-slot name="actions">
                        <flux:file-item.remove wire:click="$set('photo', null)" />
                    </x-slot>
                </flux:file-item>
            @else
                <flux:file-upload wire:model="photo" label="Upload photo">
                    <flux:file-upload.dropzone
                        heading="Drop photo here or click to browse"
                        text="JPG, PNG, GIF up to 10MB"
                    />
                </flux:file-upload>
            @endif

            <flux:error name="photo" />

            @if (session('status') === 'profile-photo-updated')
                <flux:text class="font-medium !dark:text-green-400 !text-green-600">
                    {{ __('Profile photo updated.') }}
                </flux:text>
            @endif

            @if (session('status') === 'profile-photo-removed')
                <flux:text class="font-medium !dark:text-green-400 !text-green-600">
                    {{ __('Profile photo removed.') }}
                </flux:text>
            @endif

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" :disabled="!$photo">
                    {{ __('Update Photo') }}
                </flux:button>

                @if (auth()->user()->profile_photo_path)
                    <flux:button variant="ghost" type="button" wire:click="removeProfilePhoto" wire:confirm="Are you sure you want to remove your profile photo?">
                        {{ __('Remove Photo') }}
                    </flux:button>
                @endif
            </div>
        </form>

        <flux:separator class="my-8" />

        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <flux:textarea wire:model="bio" :label="__('Bio')" rows="3" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
