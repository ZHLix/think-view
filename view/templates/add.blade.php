@extends('app')

@section('body')
    @component('components.form', array_filter([
        'public_key' => $public_key ?? '',
        'form' => $form
    ]))@endcomponent
@endsection