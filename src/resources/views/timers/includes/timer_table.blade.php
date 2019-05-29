<table class="table table-striped table-bordered table-hover">
    <thead class="thead-dark">
        <th>Name</th>
        <th>Structure</th>
        <th>Type</th>
        <th>Days | Hours | Minutes | Seconds</th>
        <th>EVE Time</th>
        <th>Win/Loss</th>
        <th>User</th>
        <th></th>
    </thead>
    <tbody>
    @foreach($timers as $id => $timer)
        <?php
        $mapItem = \Seat\Eveapi\Models\Sde\MapDenormalize::find($timer->itemID);
        $user = \Seat\Web\Models\User::find($timer->user_id);

        $nowDate = Carbon\Carbon::now();
        $timerDate = Carbon\Carbon::createFromTimeStamp(strtotime($timer->timeExiting));

        $hours = $nowDate->diffInHours($timerDate, false);
        $minutes = $nowDate->diffInMinutes($timerDate, false);

        $class = '';
        $style = 'font-size: 1.1em;';
        if($timer->timerType === '0') {
            $class = 'success';
        }
        else if($timer->timerType === '1') {
            $class = 'danger';
        }

        // The database does not store system names. Find roman numerals and split out the basename.
        $sys_tmp = preg_split("/\ [IVX]+/", $mapItem->itemName);
        $system = $sys_tmp[0];
        ?>
        <tr class="{{ $class }}" style="{{ $style }}" id="item{{ $id }}" hours="{{ $hours }}">
            <td style="{{ $style }}">
                <label title="{{ $mapItem->itemID }}" class="badge
                        @switch($mapItem->groupID)
                            @case(5)
                                {{'bg-maroon'}}
                                @break
                            @case(7)
                                {{'bg-purple'}}
                                @break
                            @case(8)
                                {{'bg-light'}}
                                @break
                        @endswitch
                " style="padding: .2em .7em .3em;">
                    @switch($mapItem->groupID)
                        @case(5)
                            {{'S'}}
                            @break
                        @case(7)
                            {{'P'}}
                            @break
                        @case(8)
                            {{'M'}}
                            @break
                    @endswitch
                </label>
                <a href="http://evemaps.dotlan.net/system/{{ $system }}">{{ $mapItem->itemName }}</a>
            </td>
            <td style="{{ $style }}">
                <?php $label_class = 'default'; ?>
                @switch($timer->structureType)
                    @case('3')
                    @case('4')
                        <?php $label_class = 'info'; ?>
                        @break
                    @case('5')
                        <?php $label_class = 'danger'; ?>
                        @break
                @endswitch
                {!! img('type', $timer->structureType, 64, ['class' => 'img-icon eve-icon medium-icon'],false) !!}
                <label class="label label-{{ $label_class }}">{{ \Seat\Kassie\Calendar\Models\Timers::$structureTypes[$timer->structureType] }}</label>
            </td>
            <td style="{{ $style }}">
                {{--Display timer type as an icon to help with red/green colorblind users--}}
                @if ($timer->timerType === '0')
                    <label class="label label-warning" title="Offensive Timer"><span class="glyphicon glyphicon-plane"></span></label>
                @else
                    <label class="label label-warning" title="Defensive Timer"><span class="glyphicon glyphicon-tower"></span></label>
                @endif
                <label class="label label-{{ ($timer->structureStatus === '1' ? 'primary' : 'danger') }}">{{ \Seat\Kassie\Calendar\Models\Timers::$structureStatus[$timer->structureStatus] }}</label>
            </td>
            <td style="{{ $style }}" width="20%">
                <div class="timer" style="height: 50px;" data-date="{{ Carbon\Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->toISO8601String() }}"></div>
            </td>
            <td style="{{ $style }}">
                <abbr class="moment" data-moment="{{ Carbon\Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->toISO8601String() }}">{{ date('Y-m-d H:i:s e', strtotime($timer->timeExiting)) }}</abbr>
            </td>
            <td style="{{ $style }}">
                @if($timer->bashed === '0')
                    <label class="label label-info">NO DATA</label>
                @else
                    @if($timer->outcome === '1')
                            <label class="label label-success">WIN</label>
                    @elseif($timer->outcome === '2')
                        ?><label class="label label-danger">LOSS</label>
                    @else
                        <label class="label label-info">Outcome Needed</label>
                    @endif
                @endif
            </td>
            <td>{!! img('character', $timer->user->character->character_id ?? 0, 64, ['class' => 'img-icon eve-icon medium-icon'],false) !!} {{ $timer->user->character->name ?? '' }}</td>
            <!-- <td style="{{ $style }}"> {{ $user->character_name }} ({{ $user->alliance_name }}) </td> -->
            <td style="{{ $style }}" class="text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNotes-{{ $timer->id }}">
                    <i class="fa fa-file "></i>&nbsp;&nbsp;
                </button>
                @if($minutes > -15 and $timer->outcome == 0)
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete-{{ $timer->id }}">
                        <i class="fa fa-trash-o"></i>&nbsp;&nbsp;
                    </button>
                @elseif($minutes <= -15 and $timer->outcome == 0)
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalWin-{{ $timer->id }}">
                        <i class="fa fa-flag"></i>&nbsp;&nbsp;
                    </button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalLoss-{{ $timer->id }}">
                        <i class="fa fa-thumbs-down"></i>&nbsp;&nbsp;
                    </button>
                @endif

                @include('calendar::timers.modals.notes', ['timer' => $timer])
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@if(isset($paginate) and $paginate == true)
    {{ $timers->links() }}
@endif
