<div class="flash-message">
  @if ( $fm = session('flash_message') )
    <div class="alert alert-success">{!! $fm !!}</div>
  @endif

  @if ( $fm = session('flash_message_info') )
    <div class="alert alert-info">{{ $fm }}</div>
  @endif

  @if ( $fm = session('flash_message_error') )
    <div class="alert alert-danger">{{ $fm }}</div>
  @endif
</div>