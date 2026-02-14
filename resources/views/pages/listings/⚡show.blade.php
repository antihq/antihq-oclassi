<?php

use App\Models\Listing;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

new #[Layout('layouts.marketplace')] class extends Component
{
    #[Locked]
    public Listing $listing;
};
?>
<div>
    <!-- TODO: Listing show page -->
</div>
