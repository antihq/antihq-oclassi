<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.landing')] class extends Component
{
};
?>

<div class="relative overflow-x-hidden">
    <div class="bg-[#E2C591] mt-4">
        <flux:header class="flex justify-between max-w-7xl mx-auto px-4 xl:px-12! relative">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer class="lg:hidden" />
            <div class="flex">
                <a href="{{ route('home') }}" wire:navigate>
                    <svg width="178px" height="58px" viewBox="0 0 178 58" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Frame-Copy-2" transform="translate(-40, -18)" fill="#224238">
                                <g id="Frame" transform="translate(0, 17)">
                                    <g id="The-Vault" transform="translate(40, 1)">
                                        <path d="M7.44,47.856 C8.08,47.856 8.608,47.688 9.024,47.352 C9.44,47.016 9.648,46.24 9.648,45.024 L9.648,14.88 L7.2,14.88 C5.44,14.88 3.904,15.344 2.592,16.272 C1.28,17.2 0.624,18.752 0.624,20.928 L0.48,20.928 L0.48,14.4 L22.176,14.4 L22.176,20.928 L22.032,20.928 C22.032,18.752 21.376,17.2 20.064,16.272 C18.752,15.344 17.216,14.88 15.456,14.88 L13.008,14.88 L13.008,45.024 C13.008,46.24 13.216,47.016 13.632,47.352 C14.048,47.688 14.576,47.856 15.216,47.856 L15.216,48 L7.44,48 L7.44,47.856 Z" id="Path" fill-rule="nonzero"></path>
                                        <path d="M30.912,14.544 C30.272,14.544 29.744,14.712 29.328,15.048 C28.912,15.384 28.704,16.16 28.704,17.376 L28.704,28.56 L39.264,28.56 L39.264,17.376 C39.264,16.16 39.056,15.384 38.64,15.048 C38.224,14.712 37.696,14.544 37.056,14.544 L37.056,14.4 L44.832,14.4 L44.832,14.544 C44.192,14.544 43.664,14.712 43.248,15.048 C42.832,15.384 42.624,16.16 42.624,17.376 L42.624,45.024 C42.624,46.24 42.832,47.016 43.248,47.352 C43.664,47.688 44.192,47.856 44.832,47.856 L44.832,48 L37.056,48 L37.056,47.856 C37.696,47.856 38.224,47.688 38.64,47.352 C39.056,47.016 39.264,46.24 39.264,45.024 L39.264,29.04 L28.704,29.04 L28.704,45.024 C28.704,46.24 28.912,47.016 29.328,47.352 C29.744,47.688 30.272,47.856 30.912,47.856 L30.912,48 L23.136,48 L23.136,47.856 C23.776,47.856 24.304,47.688 24.72,47.352 C25.136,47.016 25.344,46.24 25.344,45.024 L25.344,17.376 C25.344,16.16 25.136,15.384 24.72,15.048 C24.304,14.712 23.776,14.544 23.136,14.544 L23.136,14.4 L30.912,14.4 L30.912,14.544 Z" id="Path" fill-rule="nonzero"></path>
                                        <path d="M54.72,28.512 C54.016,28.512 53.552,28.208 53.328,27.6 L49.2,17.328 L49.056,16.992 C48.352,15.36 47.264,14.544 45.792,14.544 L45.792,14.4 L65.28,14.4 L65.28,20.928 L65.136,20.928 C65.136,18.752 64.48,17.2 63.168,16.272 C61.856,15.344 60.32,14.88 58.56,14.88 L51.792,14.88 L56.208,25.776 C56.56,26.8 56.952,27.52 57.384,27.936 C57.816,28.352 58.24,28.56 58.656,28.56 L58.992,28.56 C60.208,28.56 60.984,28.352 61.32,27.936 C61.656,27.52 61.824,26.992 61.824,26.352 L61.968,26.352 L61.968,31.248 L61.824,31.248 C61.824,30.608 61.656,30.08 61.32,29.664 C60.984,29.248 60.208,29.04 58.992,29.04 L51.36,29.04 L51.36,47.52 L56.208,47.52 C58.128,47.52 59.728,47.192 61.008,46.536 C62.288,45.88 63.296,44.656 64.032,42.864 C64.768,41.072 65.136,38.544 65.136,35.28 L65.28,35.28 L65.28,48 L45.792,48 L45.792,47.856 C46.432,47.856 46.96,47.688 47.376,47.352 C47.792,47.016 48,46.24 48,45.024 L48,28.56 L54.768,28.56 L54.768,28.512 L54.72,28.512 Z" id="Path" fill-rule="nonzero"></path>
                                        <path d="M84.48,48 L84.48,47.856 C84.992,47.856 85.408,47.688 85.728,47.352 C86.048,47.016 86.16,46.432 86.064,45.6 L78.768,17.136 C78.48,16.208 78.056,15.544 77.496,15.144 C76.936,14.744 76.384,14.544 75.84,14.544 L75.84,14.4 L83.616,14.4 L83.616,14.544 C82.56,14.544 82.032,15.104 82.032,16.224 C82.032,16.512 82.08,16.896 82.176,17.376 L89.808,47.04 L96.48,19.488 C96.768,18.048 96.912,16.992 96.912,16.32 C96.912,15.616 96.784,15.144 96.528,14.904 C96.272,14.664 95.904,14.544 95.424,14.544 L95.424,14.4 L100.416,14.4 L100.416,14.544 C99.616,14.544 99,14.768 98.568,15.216 C98.136,15.664 97.712,16.64 97.296,18.144 L90.048,48 L84.48,48 Z" id="Path" fill-rule="nonzero"></path>
                                        <path d="M109.776,14.4 L109.776,14.544 C108.72,14.544 108.192,15.12 108.192,16.272 C108.192,16.56 108.24,16.928 108.336,17.376 L115.488,45.264 C115.776,46.192 116.2,46.856 116.76,47.256 C117.32,47.656 117.872,47.856 118.416,47.856 L118.416,48 L110.64,48 L110.64,47.856 C111.696,47.856 112.224,47.296 112.224,46.176 C112.224,45.888 112.176,45.504 112.08,45.024 L108.912,32.592 C106.544,32.752 104.312,33.712 102.216,35.472 C100.12,37.232 98.608,40 97.68,43.776 C97.488,44.832 97.392,45.648 97.392,46.224 C97.392,46.896 97.504,47.336 97.728,47.544 C97.952,47.752 98.32,47.856 98.832,47.856 L98.832,48 L93.84,48 L93.84,47.856 C94.576,47.856 95.152,47.672 95.568,47.304 C95.984,46.936 96.384,46.176 96.768,45.024 L104.208,14.4 L109.776,14.4 Z M98.064,41.616 C99.088,38.352 100.536,35.848 102.408,34.104 C104.28,32.36 106.32,31.36 108.528,31.104 L104.448,15.36 L98.064,41.616 Z" id="Shape" fill-rule="nonzero"></path>
                                        <path d="M124.464,14.544 C123.824,14.544 123.296,14.712 122.88,15.048 C122.464,15.384 122.256,16.16 122.256,17.376 L122.256,40.944 C122.256,42.32 122.584,43.496 123.24,44.472 C123.896,45.448 124.744,46.184 125.784,46.68 C126.824,47.176 127.936,47.424 129.12,47.424 C130.912,47.424 132.456,46.856 133.752,45.72 C135.048,44.584 135.696,42.992 135.696,40.944 L135.696,17.376 C135.696,16.16 135.488,15.384 135.072,15.048 C134.656,14.712 134.128,14.544 133.488,14.544 L133.488,14.4 L138.384,14.4 L138.384,14.544 C137.744,14.544 137.216,14.712 136.8,15.048 C136.384,15.384 136.176,16.16 136.176,17.376 L136.176,40.944 C136.176,43.376 135.384,45.232 133.8,46.512 C132.216,47.792 130.288,48.432 128.016,48.432 C126.48,48.432 125.016,48.136 123.624,47.544 C122.232,46.952 121.096,46.088 120.216,44.952 C119.336,43.816 118.896,42.48 118.896,40.944 L118.896,17.376 C118.896,16.16 118.688,15.384 118.272,15.048 C117.856,14.712 117.328,14.544 116.688,14.544 L116.688,14.4 L124.464,14.4 L124.464,14.544 Z" id="Path" fill-rule="nonzero"></path>
                                        <path d="M147.12,14.544 C146.48,14.544 145.952,14.712 145.536,15.048 C145.12,15.384 144.912,16.16 144.912,17.376 L144.912,47.52 L149.76,47.52 C151.68,47.52 153.28,47.192 154.56,46.536 C155.84,45.88 156.848,44.656 157.584,42.864 C158.32,41.072 158.688,38.544 158.688,35.28 L158.832,35.28 L158.832,48 L139.344,48 L139.344,47.856 C139.984,47.856 140.512,47.688 140.928,47.352 C141.344,47.016 141.552,46.24 141.552,45.024 L141.552,17.376 C141.552,16.16 141.344,15.384 140.928,15.048 C140.512,14.712 139.984,14.544 139.344,14.544 L139.344,14.4 L147.12,14.4 L147.12,14.544 Z" id="Path" fill-rule="nonzero"></path>
                                        <path d="M162.384,47.856 C163.024,47.856 163.552,47.688 163.968,47.352 C164.384,47.016 164.592,46.24 164.592,45.024 L164.592,14.88 L162.144,14.88 C160.384,14.88 158.848,15.344 157.536,16.272 C156.224,17.2 155.568,18.752 155.568,20.928 L155.424,20.928 L155.424,14.4 L177.12,14.4 L177.12,20.928 L176.976,20.928 C176.976,18.752 176.32,17.2 175.008,16.272 C173.696,15.344 172.16,14.88 170.4,14.88 L167.952,14.88 L167.952,45.024 C167.952,46.24 168.16,47.016 168.576,47.352 C168.992,47.688 169.52,47.856 170.16,47.856 L170.16,48 L162.384,48 L162.384,47.856 Z" id="Path" fill-rule="nonzero"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
            </div>
            <flux:spacer />
            @auth
                <flux:navbar class="-mb-px max-lg:hidden me-4">
                    <flux:navbar.item href="{{ route('listings.index') }}" class="text-accent! hover:bg-accent/5!" wire:navigate>Browse listings</flux:navbar.item>
                    <flux:navbar.item href="{{ route('listings.create') }}" class="text-accent! hover:bg-accent/5!" wire:navigate>Post a new listing</flux:navbar.item>
                    <flux:navbar.item :href="route('inbox')" class="text-accent! hover:bg-accent/5!" wire:navigate>
                        Inbox
                        @php($unreadCount = auth()->user()->unreadConversationsCount())
                        @if($unreadCount > 0)
                            <flux:badge variant="solid" color="green" size="sm" class="ml-1" rounded>{{ $unreadCount }}</flux:badge>
                        @endif
                    </flux:navbar.item>
                </flux:navbar>
                <x-desktop-user-menu />
            @else
                <flux:navbar class="-mb-px max-lg:hidden">
                    <flux:navbar.item href="{{ route('listings.index') }}" class="text-accent! hover:bg-accent/5!" wire:navigate>Browse listings</flux:navbar.item>
                    <flux:navbar.item href="{{ route('listings.create') }}" class="text-accent! hover:bg-accent/5!" wire:navigate>Post a new listing</flux:navbar.item>
                    <flux:navbar.item href="{{ route('register') }}" class="text-accent! hover:bg-accent/5!" wire:navigate>Sign up</flux:navbar.item>
                    <flux:navbar.item href="{{ route('login') }}" class="text-accent! hover:bg-accent/5!" wire:navigate>Login</flux:navbar.item>
                </flux:navbar>
                <div class="w-10 lg:hidden"></div>
            @endauth
        </flux:header>
        <flux:sidebar
            sticky
            collapsible="mobile"
            class="border-r border-zinc-200 bg-zinc-50 lg:hidden dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.header>
                <flux:spacer />
                <flux:sidebar.collapse
                    class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"
                />
            </flux:sidebar.header>
            @auth
                <flux:sidebar.nav>
                    <flux:sidebar.item href="{{ route('listings.index') }}" class="text-accent! hover:bg-accent/5!">Browse listings</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('listings.create') }}" class="text-accent! hover:bg-accent/5!">Post a new listing</flux:sidebar.item>
                    <flux:sidebar.item :href="route('inbox')" class="text-accent! hover:bg-accent/5!" wire:navigate>
                        Inbox
                        @php($unreadCount = auth()->user()->unreadConversationsCount())
                        @if($unreadCount > 0)
                            <flux:badge variant="solid" color="green" size="sm" class="ml-1" rounded>{{ $unreadCount }}</flux:badge>
                        @endif
                    </flux:sidebar.item>
                </flux:sidebar.nav>
            @else
                <flux:sidebar.nav>
                    <flux:sidebar.item href="{{ route('listings.index') }}" class="text-accent! hover:bg-accent/5!">Browse listings</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('listings.create') }}" class="text-accent! hover:bg-accent/5!">Post a new listing</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('register') }}" class="text-accent! hover:bg-accent/5!">Sign up</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('login') }}" class="text-accent! hover:bg-accent/5!">Login</flux:sidebar.item>
                </flux:sidebar.nav>
            @endauth
        </flux:sidebar>
    </div>
    <section id="hero" class="pt-54 bg-linear-to-b from-[#2B4E43]/40 to-[#132620]/90 to-28% relative">
        <div class="max-w-7xl mx-auto px-6 xl:px-12 relative">
            <h1 class="font-heading text-[3.5rem]/15.75 max-w-158 font-bold">Curated luxury marketplace. Connect with expert sellers. Buy directly.</h1>
            <p class="mt-10 max-w-180 text-lg/8">The Vault connects collectors with vetted experts in vintage watches, leather goods, and rare cameras. Browse authenticated items from certified specialists worldwide. No middlemen, no transaction fees.</p>
            <div class="mt-8">
                <flux:button href="{{ route('listings.index') }}" variant="primary" wire:navigate>Browse the collection</flux:button>
                <flux:button href="{{ route('listings.create') }}" wire:navigate>Sell with us</flux:button>
            </div>
            <div class="absolute -bottom-75 -right-92">
                <img src="/Wrist Watch.K02.2k Copy.webp" class="w-219">
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 xl:px-12 relative pt-72 pb-36">
            <h2 class="text-center font-heading max-w-3xl text-[3.25rem]/15 mx-auto font-bold">Trusted by collectors worldwide. Vetted by experts.</h2>
            <div class="flex items-center justify-center gap-x-10 gap-y-4 mt-12 flex-wrap text-sm font-medium">
                <p>Sellers with 20+ years of specialized expertise</p>
                <p>Specialists in Swiss watches, Hermès, and rare cameras</p>
                <p>Curators from Manhattan to Paris to London</p>
            </div>
        </div>
    </section>
    <section class="bg-[#132620]/90 relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-[#AA9772] py-36 text-[#FFFBEB]">
                <div class="pl-18 relative">
                    <div class="absolute top-34.5 -left-25">
                        <h2 class="text-sm uppercase font-semibold tracking-[0.3em] -rotate-90">Expert Authentication</h2>
                    </div>
                    <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-xl">Every item authenticated by certified specialists</h3>
                    <p class="mt-8 text-lg/7.5 max-w-xl font-medium">Each listing comes from a vetted expert—whether it's a Swiss-certified watchmaker, a Parisian-trained Hermès specialist, or a collector with three decades of experience. You'll know exactly who curated your piece and why it's authentic.</p>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc.webp" class="w-110 absolute -top-40 -right-4" alt="">
                </div>
                <div class="pr-18 relative mt-82 flex flex-row-reverse">
                    <div>
                        <div class="absolute top-32 -right-18">
                            <h2 class="text-sm uppercase font-semibold tracking-[0.3em] rotate-90">Curated Selection</h2>
                        </div>
                        <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-150">Only the finest pieces make it through our doors</h3>
                        <p class="mt-8 text-lg/7.5  max-w-150 font-medium">We don't accept everything. Sellers apply for approval, and only those with proven expertise and exceptional inventory join the marketplace. That means you browse only premium vintage watches, fine leather goods, and rare equipment—not clutter.</p>
                    </div>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy.webp" class="w-110 absolute -top-40 -left-4" alt="">
                </div>
                <div class="pl-18 relative mt-82">
                    <div class="absolute top-34.5 -left-25">
                        <h2 class="text-sm uppercase font-semibold tracking-[0.3em] -rotate-90">Direct Communication</h2>
                    </div>
                    <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-xl">Connect directly with the expert who curated <span class="whitespace-nowrap">your item</span></h3>
                    <p class="mt-8 text-lg/7.5 max-w-150 font-medium">
                        No middlemen, no anonymous sellers. When you find something you love, you'll speak directly with the specialist who sourced it. Ask questions, learn its provenance, and buy with confidence from someone who knows their craft inside and out.
                    </p>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy 2.webp" class="w-110 absolute -top-40 -right-4" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="bg-[#132620]/90 relative pt-72">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-[#33394A] py-36 text-[#F8FCFB]">
                <div class="pl-18 relative">
                    <div class="absolute top-14 -left-6">
                        <h2 class="text-sm uppercase font-semibold tracking-[0.3em] -rotate-90">Zero Fees</h2>
                    </div>
                    <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-xl">No transaction fees. Period.</h3>
                    <p class="mt-8 text-lg/7.5 max-w-xl font-medium">
                        You keep what you sell. The Vault doesn't take a cut of your transactions. List your items, communicate with buyers, and close deals—100% of the sale price is yours.
                    </p>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy 5.webp" class="w-110 absolute -top-40 -right-4" alt="">
                </div>
                <div class="pr-18 relative mt-82 flex flex-row-reverse">
                    <div>
                        <div class="absolute top-30 -right-16">
                            <h2 class="text-sm uppercase font-semibold tracking-[0.3em] rotate-90">Qualified Buyers</h2>
                        </div>
                        <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-150">A marketplace of serious collectors</h3>
                        <p class="mt-8 text-lg/7.5  max-w-150 font-medium">
                            Our buyers know what they're looking for—authenticated vintage watches, luxury leather goods, and rare cameras. They're here because they value expertise and quality. Connect with customers who appreciate your curation.
                        </p>
                    </div>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy 3.webp" class="w-110 absolute -top-40 -left-4" alt="">
                </div>
                <div class="pl-18 relative mt-82">
                    <div class="absolute top-20 -left-12">
                        <h2 class="text-sm uppercase font-semibold tracking-[0.3em] -rotate-90">Global Reach</h2>
                    </div>
                    <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-xl">Sell to collectors worldwide</h3>
                    <p class="mt-8 text-lg/7.5 max-w-150 font-medium">
                        Your expertise isn't limited by geography. List your items once, and reach serious buyers across continents. From Manhattan to Paris to London, The Vault connects you with a global audience of collectors.
                    </p>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy 4.webp" class="w-110 absolute -top-40 -right-4" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="bg-[#132620]/90 relative pt-72">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-[#AA9772] py-36 text-[#FFFBEB]">
                <div class="pl-18 relative">
                    <div class="absolute top-14 -left-6">
                        <h2 class="text-sm uppercase font-semibold tracking-[0.3em] -rotate-90">Zero Fees</h2>
                    </div>
                    <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-xl">The full value goes to sellers. Better prices for buyers.</h3>
                    <p class="mt-8 text-lg/7.5 max-w-xl font-medium">
                        Because we don't charge transaction fees, sellers don't mark up prices to cover costs. You get fair prices on premium items, and sellers get rewarded for their expertise. It's straightforward for everyone.
                    </p>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy 7.webp" class="w-110 absolute -top-40 -right-4" alt="">
                </div>
                <div class="pr-18 relative mt-82 flex flex-row-reverse">
                    <div>
                        <div class="absolute top-26 -right-13">
                            <h2 class="text-sm uppercase font-semibold tracking-[0.3em] rotate-90">Global Access</h2>
                        </div>
                        <h3 class="font-heading text-[3.5rem]/15.5 font-bold max-w-150">Discover rare finds from sellers worldwide</h3>
                        <p class="mt-8 text-lg/7.5  max-w-150 font-medium">
                            A vintage watch from a Miami collector. A Hermès bag sourced in Paris. A rare camera curated in London. Browse rare pieces from specialists across the globe, all authenticated and ready for you.
                        </p>
                    </div>
                    <img src="/silhouette-of-man-looking-away-isolated-on-white-2026-01-06-00-03-40-utc copy 6.webp" class="w-110 absolute -top-40 -left-4" alt="">
                </div>
            </div>
        </div>
    </section>
</div>
