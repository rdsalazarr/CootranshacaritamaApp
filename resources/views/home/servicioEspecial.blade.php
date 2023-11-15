@extends('layouts.app')

@section('content')
    @vite(['resources/css/app.css', 'resources/js/components/page/servicioEspecial.jsx'])
@endsection

@section('script')
    <script>
        window.id = {!! json_encode($id) !!};
    </script>
@endsection