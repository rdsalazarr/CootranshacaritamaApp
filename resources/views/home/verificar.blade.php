@extends('layouts.app')

@section('content')
    @vite(['resources/css/app.css', 'resources/js/components/page/verificar.jsx'])
@endsection

@section('script')
    <script>
        window.dataDocumento = {!! json_encode($dataDocumento) !!};
    </script>
@endsection