@php
    $response =  Http::get("http://192.168.200.99:8080/customers");

    $stuff = $response->collect()->where("id", $getRecord()->rn)->first() ;
 
    $display = $stuff
@endphp

<div>
    {{($stuff['societyName'])}}
</div>
