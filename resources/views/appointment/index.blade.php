@extends('master')


@section('content')
  @include("appointment.partials.custom-css")
  <script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>
  <div id="ev-calendar-app" >

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