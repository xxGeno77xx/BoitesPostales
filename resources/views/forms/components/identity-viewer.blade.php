<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">

        {{-- <img src="{{$getRecord()->document_name}}" alt="Description de l'image" width="300" height="200"> --}}

        <img src="http://192.168.60.43:8080/boitepostale-api/file/{{$getRecord()->document_name}}" alt="Description de l'image" width="300" height="200" style="display: block; margin: 0 auto;">


    </div>
</x-dynamic-component>
