<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
        <flux:sidebar sticky collapsible="mobile" class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700 [:where(&)]:w-60!">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    :href="route('cp')"
                >
                    <x-slot name="logo">
                        <svg class="ml-1" width="70.0082169px" height="26px" viewBox="0 0 70.0082169 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="2-・-Small-Copy-2" transform="translate(-48, -26)" fill="#261C17">
                                    <g id="Group" transform="translate(48, 26)">
                                        <g id="O’Classi" fill-rule="nonzero">
                                            <path d="M7.8,20.26 C11.94,20.26 15.06,17.02 15.06,13.02 C15.06,9 11.94,5.74 7.8,5.74 C3.64,5.74 0.5,9 0.5,13.02 C0.5,17.02 3.64,20.26 7.8,20.26 Z M7.8,17.66 C5.08,17.66 3.4,15.56 3.4,13.02 C3.4,10.48 5.08,8.34 7.8,8.34 C10.5,8.34 12.16,10.48 12.16,13.02 C12.16,15.56 10.5,17.66 7.8,17.66 Z" id="Shape"></path>
                                            <path d="M16.26,10.9 L16.82,10.7 C18.3,10.18 19.3,9 19.3,7.46 C19.3,6.02 18.5,5.14 17.3,5.14 C16.42,5.14 15.66,5.86 15.66,6.72 C15.66,7.6 16.42,8.32 17.22,8.32 C17.38,8.32 17.52,8.3 17.68,8.26 C17.5,8.8 17.1,9.08 16.28,9.36 L15.72,9.56 L16.26,10.9 Z" id="Path"></path>
                                            <path d="M27,20.26 C29.44,20.26 31.24,19.32 32.3,18.04 L30.26,16.3 C29.44,17.2 28.42,17.66 27.06,17.66 C24.3,17.66 22.6,15.58 22.6,13.02 C22.6,10.46 24.3,8.34 27,8.34 C28.26,8.34 29.3,8.78 30.14,9.66 L32.12,7.94 C30.86,6.44 29.28,5.74 27,5.74 C22.68,5.74 19.7,9 19.7,13.02 C19.7,17.02 22.84,20.26 27,20.26 Z" id="Path"></path>
                                            <polygon id="Path" points="33.24 20 35.94 20 35.94 5.4 33.24 5.4"></polygon>
                                            <path d="M42.28,20.26 C44.14,20.26 45.08,19.48 45.64,18.3 L45.64,20 L48.26,20 L48.26,9.6 L45.58,9.6 L45.58,11.24 C45.02,10.12 44.08,9.38 42.28,9.38 C39.42,9.38 37.14,11.84 37.14,14.84 C37.14,17.84 39.42,20.26 42.28,20.26 Z M42.72,17.82 C41,17.82 39.84,16.4 39.84,14.82 C39.84,13.2 41,11.82 42.72,11.82 C44.5,11.82 45.64,13.22 45.64,14.84 C45.64,16.46 44.5,17.82 42.72,17.82 Z" id="Shape"></path>
                                            <path d="M53.42,20.26 C55.72,20.26 57.46,18.82 57.46,16.9 C57.46,15.34 56.76,14.22 53.74,13.44 C52.5,13.12 52.34,12.8 52.34,12.44 C52.34,11.8 52.9,11.52 53.64,11.52 C54.34,11.52 54.98,11.84 55.48,12.64 L57.44,11.14 C56.38,9.76 55.26,9.34 53.58,9.34 C51.14,9.34 49.7,10.7 49.7,12.52 C49.7,13.84 50.12,15.1 52.9,15.76 C54.54,16.14 54.8,16.44 54.8,16.98 C54.8,17.6 54.28,18.06 53.36,18.06 C52.48,18.06 51.68,17.64 50.92,16.78 L49.1,18.44 C50.24,19.66 51.66,20.26 53.42,20.26 Z" id="Path"></path>
                                            <path d="M62.08,20.26 C64.38,20.26 66.12,18.82 66.12,16.9 C66.12,15.34 65.42,14.22 62.4,13.44 C61.16,13.12 61,12.8 61,12.44 C61,11.8 61.56,11.52 62.3,11.52 C63,11.52 63.64,11.84 64.14,12.64 L66.1,11.14 C65.04,9.76 63.92,9.34 62.24,9.34 C59.8,9.34 58.36,10.7 58.36,12.52 C58.36,13.84 58.78,15.1 61.56,15.76 C63.2,16.14 63.46,16.44 63.46,16.98 C63.46,17.6 62.94,18.06 62.02,18.06 C61.14,18.06 60.34,17.64 59.58,16.78 L57.76,18.44 C58.9,19.66 60.32,20.26 62.08,20.26 Z" id="Path"></path>
                                            <path d="M68.52,8.74 C69.46,8.74 70.14,8.04 70.14,7.14 C70.14,6.28 69.46,5.56 68.52,5.56 C67.6,5.56 66.9,6.28 66.9,7.14 C66.9,8.02 67.6,8.74 68.52,8.74 Z M67.18,20 L69.88,20 L69.88,9.6 L67.18,9.6 L67.18,20 Z" id="Shape"></path>
                                        </g>
                                        <path d="M41.28,5.38 C43.08,5.38 44.02,6.12 44.58,7.24 L44.58,5.6 L47.26,5.6 L47.259,9 L44.0717092,8.99976276 C43.5983964,8.33347052 42.850483,7.87883552 41.9082425,7.82530339 L41.72,7.82 C40.7179171,7.82 39.9059151,8.28841358 39.4026465,8.99980102 L36.4388821,9.00015988 C37.1464962,6.89648715 39.0344752,5.38 41.28,5.38 Z M52.58,5.34 C54.26,5.34 55.38,5.76 56.44,7.14 L54.48,8.64 C53.98,7.84 53.34,7.52 52.64,7.52 C51.9,7.52 51.34,7.8 51.34,8.44 C51.34,8.63657895 51.3877078,8.82123096 51.6329162,9.00046876 L48.7224039,9.00071576 C48.7062844,8.84203384 48.7,8.68146581 48.7,8.52 C48.7,6.7 50.14,5.34 52.58,5.34 Z M61.24,5.34 C62.92,5.34 64.04,5.76 65.1,7.14 L63.14,8.64 C62.64,7.84 62,7.52 61.3,7.52 C60.56,7.52 60,7.8 60,8.44 C60,8.63657895 60.0477078,8.82123096 60.2929162,9.00046876 L57.3824039,9.00071576 C57.3662844,8.84203384 57.36,8.68146581 57.36,8.52 C57.36,6.7 58.8,5.34 61.24,5.34 Z" id="Shape"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </x-slot>
                </flux:sidebar.brand>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="users" :href="route('cp.users.index')" :current="request()->routeIs('cp.users.*')" wire:navigate>
                    {{ __('Users') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="list-bullet" :href="route('cp.listings.index')" :current="request()->routeIs('cp.listings.*')" wire:navigate>
                    {{ __('Listings') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="chat-bubble-left-right" :href="route('cp.conversations.index')" :current="request()->routeIs('cp.conversations.*')" wire:navigate>
                    {{ __('Conversations') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="arrow-top-right-on-square" :href="route('home')" wire:navigate>
                    {{ __('My marketplace') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :avatar="auth()->user()->profilePhotoUrl()"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
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
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
