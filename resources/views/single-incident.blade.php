@extends('layout.master')

@section('bodyClass', 'no-padding')

@section('outer-content')
@include('partials.nav')
@stop

@section('content')
<h1>{{ formatted_date($incident->created_at) }}</h1>
@foreach($incident_history as $incidentHistory)
<div class="timeline">
    <div class="content-wrapper">
        <div class="moment first">
            <div class="row event clearfix">
                <div class="col-sm-1">
                    <div class="status-icon status-{{ $incidentHistory->status }}" data-toggle="tooltip" title="{{ $incidentHistory->human_status }}" data-placement="left">
                        <i class="{{ $incidentHistory->icon }}"></i>
                    </div>
                </div>
                <div class="col-xs-10 col-xs-offset-2 col-sm-11 col-sm-offset-0">
                    @include('partials.incidentHistory', ['incident' => $incident, 'incident_history' => $incidentHistory, 'with_link' => false])
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@stop
