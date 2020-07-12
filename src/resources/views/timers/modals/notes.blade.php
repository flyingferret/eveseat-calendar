<div class="modal fade" role="dialog" id="modalNotes-{{$timer->id}}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-navy">
        <h4 class="modal-title">
          <i class="fa fa-file"></i>
          Notes
        </h4>
      </div>
      <div class="modal-body">
        NOTES GO HERE
      </div>
      <div class="modal-footer bg-navy">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
          {{ trans('calendar::seat.close') }}
        </button>
        <button type="button" class="btn btn-success pull-right" data-dismiss="modal">
          Create New Note
        </button>
      </div>
    </div>
  </div>
</div>

@push('javascript')
  <script type="text/javascript">

  </script>
@endpush