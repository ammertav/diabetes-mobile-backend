@extends('layouts.app')

@section('content')
    <x-toast />
    
    <div x-data="{
        selectedType: 'all',
        selectedProtocolId: '{{ $protocols->first()->id ?? '' }}',
        protocols: {{ json_encode($protocols) }}
    }">
        @include('fasting-protocol.partials._header')
        
        <div class="grid grid-cols-12 gap-8">
            @include('fasting-protocol.partials._list')
            @include('fasting-protocol.partials._preview')
        </div>
    </div>
@endsection
