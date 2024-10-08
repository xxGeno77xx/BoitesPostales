<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">

    <img src="http://192.168.60.43:8080/boitepostale-api/file/{{ $getRecord()->document_name }}"
        alt="{{ $getRecord()->document_name }}"  >

    <x-filament::modal width="5xl" slide-over>
        <x-slot name="trigger">
            <x-filament::button>
                Agrandir la pièce d'identité
            </x-filament::button>
        </x-slot>

        <img src="http://192.168.60.43:8080/boitepostale-api/file/{{ $getRecord()->document_name }}"
        alt="{{ $getRecord()->document_name }}"  >
    </x-filament::modal>

</x-dynamic-component>
