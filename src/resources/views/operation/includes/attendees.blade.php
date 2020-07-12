<small class="badge badge-success" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_yes') }}">{{ $op->attendees->where('status', '=', 'yes')->count() }}</small>
<small class="badge badge-warning" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_maybe') }}">{{ $op->attendees->where('status', '=', 'maybe')->count() }}</small>
<small class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('calendar::seat.attending_no') }}">{{ $op->attendees->where('status', '=', 'no')->count() }}</small>
