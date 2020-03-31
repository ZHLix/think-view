@extends('templates.body')

@section('main')
    @component('templates.main', ['topNavInfo' => $topNavInfo])
        @component('components.table', ['url' => $url,'cols' => $cols, 'toolbar' => $toolbar, 'contextmenu' => $contextmenu])@endcomponent
    @endcomponent
@endsection