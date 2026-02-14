<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        @include("partials.head")
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">
        <flux:header
            container
            class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:brand
                href="#"
                logo="https://fluxui.dev/img/demo/logo.png"
                :name="config('app.name')"
                class="max-lg:hidden dark:hidden"
            />
            <flux:brand
                href="#"
                logo="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Acme Inc."
                class="hidden max-lg:hidden! dark:flex"
            />
            <flux:spacer />
            @auth
                <flux:navbar class="-mb-px max-lg:hidden me-4">
                    <flux:navbar.item :href="route('listings.create')">
                        Post a new listing
                    </flux:navbar.item>
                    <flux:navbar.item wire:navigate>
                        Inbox
                    </flux:navbar.item>
                </flux:navbar>
                <x-desktop-user-menu />
            @else
                <flux:navbar class="-mb-px max-lg:hidden">
                    <flux:navbar.item :href="route('listings.create')">
                        Post a new listing
                    </flux:navbar.item>
                    <flux:navbar.item :href="route('register')" wire:navigate>
                        Sign up
                    </flux:navbar.item>
                    <flux:navbar.item :href="route('login')" wire:navigate>
                        Log in
                    </flux:navbar.item>
                </flux:navbar>
            @endauth
        </flux:header>
        <flux:sidebar
            sticky
            collapsible="mobile"
            class="border-r border-zinc-200 bg-zinc-50 lg:hidden dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="#"
                    logo="https://fluxui.dev/img/demo/logo.png"
                    logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                    name="Acme Inc."
                />
                <flux:sidebar.collapse
                    class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"
                />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="#" current>
                    Home
                </flux:sidebar.item>
                <flux:sidebar.item icon="inbox" badge="12" href="#">
                    Inbox
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-text" href="#">
                    Documents
                </flux:sidebar.item>
                <flux:sidebar.item icon="calendar" href="#">
                    Calendar
                </flux:sidebar.item>
                <flux:sidebar.group expandable heading="Favorites" class="grid">
                    <flux:sidebar.item href="#">
                        Marketing site
                    </flux:sidebar.item>
                    <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                    <flux:sidebar.item href="#">
                        Brand guidelines
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
            <flux:sidebar.nav>
                <flux:sidebar.item icon="cog-6-tooth" href="#">
                    Settings
                </flux:sidebar.item>
                <flux:sidebar.item icon="information-circle" href="#">
                    Help
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>
        <flux:main container>
            {{ $slot }}
        </flux:main>

        @fluxScripts
    </body>
</html>
