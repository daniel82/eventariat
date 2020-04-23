<div class="d-inline-block">
  {{ Form::open(['action' => [ $controller. '@restore'], 'method' => 'PATCH', 'class' => 'ib' ]) }}
    {{ Form::hidden("user_id", $object->id ) }}
    <button type="submit" class="btn btn-info" title="Wiederherstellen"><i class="fa fa-recycle" aria-hidden="true"></i><span class="d-none">Wiederherstellen</span></button>
  {{ Form::close() }}
</div>