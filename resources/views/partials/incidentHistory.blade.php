<div class="panel panel-message incidentHistory">
    <div class="panel-heading">
        @if($current_user)
            <div class="pull-right btn-group">
                <a href="{{ route('dashboard.incidents.edit', ['id' => $incident->id]) }}" class="btn btn-default">{{ trans('forms.edit') }}</a>
                <a href="{{ route('dashboard.incidents.delete', ['id' => $incident->id]) }}" class="btn btn-danger confirm-action" data-method='DELETE'>{{ trans('forms.delete') }}</a>
            </div>
        @endif
        @if($incident->component)
            <span class="label label-default">{{ $incident->component->name }}</span>
        @endif
        <strong>{{ $incident->name }}</strong>{{ $incident_history->isScheduled ? trans("cachet.incidents.scheduled_at", ["timestamp" => $incident_history->scheduled_at_diff]) : null }}
        <br>
        <small class="date">
            @if($with_link)
                <a href="{{ route('incident', ['id' => $incident_history->id]) }}" class="links"><abbr class="timeago" data-toggle="tooltip" data-placement="right" title="{{ $incident_history->timestamp_formatted }}" data-timeago="{{ $incident_history->timestamp_iso }}"></abbr></a>
            @else
                <abbr class="timeago" data-toggle="tooltip" data-placement="right" title="{{ $incident_history->timestamp_formatted }}" data-timeago="{{ $incident_history->timestamp_iso }}"></abbr>
            @endif
        </small>
    </div>
    <div class="panel-body markdown-body">
        {!! $incident_history->formattedMessage !!}
    </div>
</div>
