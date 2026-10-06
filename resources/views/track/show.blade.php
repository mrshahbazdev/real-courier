@extends('layouts.public')

@section('title', 'Track Shipment — '.$settings['company_name'])

@section('content')
<section class="result-wrap">
    <div class="container" style="max-width: 860px;">
        <form class="track-form" method="GET" action="{{ route('track') }}">
            <input type="text" name="tracking_no" placeholder="Enter tracking number" required value="{{ $query }}">
            <button class="btn btn-accent" type="submit">Track</button>
        </form>

        @if (! $shipment)
            <div class="notice-error">
                <strong>No shipment found</strong>
                We could not find a shipment for <span class="mono">{{ $query }}</span>. Please check the number and try again, or contact support.
            </div>
        @else
            @include('track.cards.'.$template)
        @endif
    </div>
</section>
@endsection
