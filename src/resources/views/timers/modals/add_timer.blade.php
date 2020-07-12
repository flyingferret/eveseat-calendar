<div class="modal fade" role="dialog" id="modalAddTimer">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-navy">
        <h4 class="modal-title">
          <i class="fa fa-stopwatch"></i>
          Add Timer
        </h4>
      </div>
      <div class="modal-body">
        <div class="modal-errors alert alert-danger hidden">
          <ul></ul>
        </div>
        <form id="formAddTimer" method="POST" action="{{ route('timers.add') }}">
          <div class="form-group">
            <label for="itemID">Celestial Name</label>
            <select name="itemID" id="select_map_item" style="width: 100%;"></select>
          </div>

          <div class="form-inline">
            <div class="input-group">
              <label for="daysLeft">Days</label>
              <select class="form-control" name="days">@for($i=0;$i<31;$i++)<option value="{{$i}}">{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>@endfor</select>
            </div>
            <div class="input-group">
              <label for="daysLeft">Hours</label>
              <select class="form-control" name="hours">@for($i=0;$i<25;$i++)<option value="{{$i}}">{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>@endfor</select>
            </div>
            <div class="input-group">
              <label for="daysLeft">Minutes</label>
              <select class="form-control" name="mins">@for($i=0;$i<61;$i++)<option value="{{$i}}">{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>@endfor</select>
            </div>
          </div>

          <div class="form-group">
            <label for="timerType">Timer Type</label>
            <select name="timerType" id="select_timer_type" style="width: 100%;">
              <option value=""></option>
              @foreach(\Seat\Kassie\Calendar\Models\Timers::$timerType as $key => $timerType)
                <option value="{{ $key }}">{{ $timerType }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="structureType">Structure Type</label>
            <select name="structureType" id="select_structure_type" style="width: 100%;">
              <option value=""></option>
              @foreach(\Seat\Kassie\Calendar\Models\Timers::$structureTypes as $key => $structureType)
                <option value="{{ $key }}">{{ $structureType }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="structureStatus">Structure Status</label>
            <select name="structureStatus" id="select_structure_status" style="width: 100%;">
              <option value=""></option>
              @foreach(\Seat\Kassie\Calendar\Models\Timers::$structureStatus as $key => $structureStatus)
                @if($key == 0) @continue @endif
                <option value="{{ $key }}">{{ $structureStatus }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-navy">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
          {{ trans('calendar::seat.close') }}
        </button>
        <button type="button" class="btn btn-success" id="create_timer_submit">
          Create This Timer
        </button>
      </div>
    </div>
  </div>
</div>

@push('javascript')
  <script type="text/javascript">
    $("#select_map_item").select2({
        placeholder: "Search for Celestials",
        minimumInputLength: 3,
        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
            url: "{{ route('timers.search_map') }}",
            dataType: 'json',
        }
    });

    $('#select_timer_type').select2({
        placeholder: "Select Timer Type",
        allowClear: false
    });

    $('#select_structure_type').select2({
        placeholder: "Select Structure Type",
        allowClear: false
    });

    $('#select_structure_status').select2({
        placeholder: "Select Structure Status",
        allowClear: false
    });
  </script>
@endpush