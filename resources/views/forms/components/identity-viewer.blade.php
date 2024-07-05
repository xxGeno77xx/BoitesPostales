<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->

 
        {{-- <img src="{{$getRecord()->document_name}}" alt="Description de l'image" width="300" height="200"> --}}

            <img src="http://192.168.200.99:8080/customers/images/toto.png"
     alt="Description de l'image"
     width="300"
     height="200"
     style="display: block; margin: 0 auto;">

    </div>
</x-dynamic-component>
