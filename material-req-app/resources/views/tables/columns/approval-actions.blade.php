<div class="inline-flex gap-1">
    <x-filament::button
        size="xs"
        color="success"
        wire:click="$emit('tableAction', 'approve', {{ $record->id }})"
    >
        Approve
    </x-filament::button>

    <x-filament::button
        size="xs"
        color="danger"
        wire:click="$emit('tableAction', 'reject', {{ $record->id }})"
    >
        Reject
    </x-filament::button>
</div>
