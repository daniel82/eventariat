@extends('master')


@section('content')
  @include("appointment.partials.custom-css")
  <script>group = null;</script>
  <script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>
  <div id="ev-calendar-app" >
    <div class="message-box">
      <div class="ev-alert p-3 " :class="message_type" v-if="message && message_type">@{{ message }}</div>
    </div>

    @if ( $user->isAdmin() )
      <div class="text-right">
        <button class="ev-add-btn" @click="createAppointment()" ><span class="d-none">Neuer Termin</span><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
      </div>
    @endif

    @include("appointment.partials.filters")
    @include("appointment.partials.layer")
    @include("appointment.partials.calendar")
  </div>
@endsection