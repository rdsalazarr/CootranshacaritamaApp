@extends('layouts.app')

@section('content')
    @vite(['resources/css/app.css', 'resources/js/components/page/firmarContrato.jsx'])
@endsection

@section('script')
    <script>
        window.contratoId = {!! json_encode($contratoId) !!};
        window.firmaId = {!! json_encode($firmaId) !!};
    </script>
@endsection