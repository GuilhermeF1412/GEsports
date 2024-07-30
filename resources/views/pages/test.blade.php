@extends('layouts.app')

@section('content')
    <div class="container">
        @dd($test);
        @if (!empty($test))
            <div class="league">
                @foreach($test as $test2)
                    <div class="match transition-fast">
                        <div class="status"></div>
                    </div>
                @endforeach
                @else
                    <p>No data available.</p>
    @endif
@endsection
