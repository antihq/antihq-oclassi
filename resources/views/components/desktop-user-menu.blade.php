<flux:dropdown position="top" align="start">
    <flux:sidebar.profile
        :avatar="auth()->user()->profilePhotoUrl()"
        :initials="auth()->user()->initials()"
        :chevron="false"
        circle
    />

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            <flux:avatar
                :src="auth()->user()->profilePhotoUrl()"
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
            />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('user.listings.index')" wire:navigate>
                {{ __('Your listings') }}
            </flux:menu.item>
            <flux:menu.item :href="route('profile.edit')" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
            @if(auth()->user()->isAdmin())
                <flux:menu.group heading="Admin">
                    <flux:menu.item :href="route('cp.users.index')" wire:navigat>Dashboard</flux:menu.item>
                </flux:menu.group>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
