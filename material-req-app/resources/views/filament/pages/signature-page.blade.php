<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-start">
        <x-filament::button 
        size="sm"
        color="primary"
        wire:click="save"
        class="mt-4 !w-auto">
            Save Signature
        </x-filament::button>
    </div>

    @if(filament()->auth()->user()?->signature)
        <div class="mt-6">
            <span class="px-2 py-1 bg-green-100 text-green-700 rounded">
                ✔ Signature Uploaded
            </span>
            <div class="mt-2">
                <img style="height:120px;width:150px" src="{{asset('storage/'.filament()->auth()->user()->signature)}}" class="h-24 border rounded"/>
            </div>
        </div>
    @endif
</x-filament-panels::page>
