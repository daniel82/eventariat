<div class="d-inline-block">
  {{ Form::open(['action' => [ $controller. '@destroy', $object->id ], 'method' => 'DELETE', 'class' => 'ib' ]) }}
      <button type="submit" class="btn btn-danger" title="L&ouml;schen">
      <i class="fa fa-trash-o" aria-hidden="true"></i>
      <span class="d-none">L&ouml;schen</span>
      </button>
  {{ Form::close() }}
</div>