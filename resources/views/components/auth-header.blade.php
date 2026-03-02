@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading class="text-lg! font-semibold">{{ $title }}</flux:heading>
    <flux:subheading>{{ $description }}</flux:subheading>
</div>
