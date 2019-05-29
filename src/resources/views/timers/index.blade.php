@extends('web::layouts.grids.12')

@section('title', trans('calendar::seat.plugin_name') . ' | ' . trans('calendar::seat.operations'))
@section('page_header', 'Timer Board')

@section('full')

  @if ($flash_error = session('flash_error'))
    <div id="flash_error" class="alert alert-danger">{{ $flash_error }}</div>
  @endif

  @if ($flash_msg = session('flash_msg'))
    <div id="flash_error" class="alert alert-info">{{ $flash_msg }}</div>
  @endif

  @if($activeTimers->count() > 0)
    @foreach($activeTimers as $timer)
      @if (strtotime($timer->timeExiting) > time())
        <div id="flash_error" class="alert alert-success">
          Next Timer: <strong data-livestamp="{{ Carbon\Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->toISO8601String() }}">{{ Carbon\Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->diffForHumans() }}</strong>
        </div>
        @break
      @endif
    @endforeach
  @endif

  <div class="row">
    <div class="col-lg-12">
      <h3>
        @if(auth()->user()) {{-- TODO Add permissions for certain members --}}
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalAddTimer">
            <i class="fa fa-plus"></i>&nbsp;&nbsp;
            Add A New Timer
          </button>
        @endif
        Active Timers <label class="label label-default now time-now">{{ date('Y-m-d H:i:s e', time()) }}</label>
      </h3>
      <div>
        {!! $timer_table_new !!}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <h3>
        Expired Timers
      </h3>
      <div>
        {!! $timer_table_old !!}
      </div>
    </div>
  </div>

  @include('calendar::timers.modals.add_timer')

@stop

@push('head')
  <link rel="stylesheet" href="{{ asset('web/css/calendar.css') }}" />
  <link rel="stylesheet" href="{{ asset('web/css/TimeCircles.css') }}" />
@endpush

@push('javascript')
  <script src="{{ asset('web/js/jquery.autocomplete.min.js') }}"></script>
  <script src="{{ asset('web/js/calendar.js') }}"></script>
  <script src="{{ asset('web/js/TimeCircles.js') }}"></script>
  @include('web::includes.javascript.id-to-name')
  <script type="text/javascript">
      $(".timer").TimeCircles();
  </script>
@endpush
