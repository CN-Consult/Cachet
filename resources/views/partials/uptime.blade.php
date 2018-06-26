<div class="uptime">
    <div class="panel panel-info">
        <div class="panel-heading">
            <strong>{{ trans('cachet.uptime.uptime') }}</strong>
        </div>

    </div>
</div>

<table class="table">
    <tbody>
        <tr>
            <th scope="row">{{ trans('cachet.uptime.today') }}</th>
            <td>{{$uptime_stats[0]}}</td>
        </tr>
        <tr>
            <th scope="row">{{ trans('cachet.uptime.last_week') }}</th>
            <td>{{$uptime_stats[1]}}</td>
        </tr>
        <tr>
            <th scope="row">{{ trans('cachet.uptime.last_month') }}</th>
            <td>{{$uptime_stats[2]}}</td>
        </tr>
        <tr>
            <th scope="row">{{ trans('cachet.uptime.current_year') }}</th>
            <td>{{$uptime_stats[3]}}</td>
        </tr>
        <tr>
            <th scope="row">{{ trans('cachet.uptime.last_year') }}</th>
            <td>{{$uptime_stats[4]}}</td>
        </tr>
    </tbody>
</table>