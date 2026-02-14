<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout("layouts.marketplace")] class extends Component {
    //
};
?>

<div>
    <flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Here's what's new today</flux:text>
    <flux:separator variant="subtle" />
</div>
