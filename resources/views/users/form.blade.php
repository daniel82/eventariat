<div id="ev-user-app">
  {{ Form::hidden("user_id", $object->id) }}
  @include("users.partials.basics")
  @include("users.partials.details")
  @include("users.partials.employment")
  @include("users.partials.capabilities")
  @include("users.partials.password")
</div>

<script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>