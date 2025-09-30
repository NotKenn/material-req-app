<x-filament-panels::page>
    {{ $this->form }}

    <x-filament::button wire:click="save" class="mt-4">
        Save Signature
    </x-filament::button>

    @if(auth()->user()?->signature)
        <div class="mt-6">
            <span class="px-2 py-1 bg-green-100 text-green-700 rounded">
                ✔ Signature Uploaded
            </span>
            <div class="mt-2">
                <img src="{{ Storage::url(auth()->user()->signature) }}" class="h-24 border rounded"/>
            </div>
        </div>
    @endif
</x-filament-panels::page>
