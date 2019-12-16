@extends('master')


@section('content')

  {{-- <div class="text-right"><a href="{{ action("UserController@create") }}" class="ev-add-btn "><span class="d-none">Neuer Mitarbeiter</span><i class="fa fa-plus-circle" aria-hidden="true"></i>
</a></div> --}}
  <style type="text/css">
    @foreach($locations as $location )
      .location-{{ $location->id }}
      {
        color: {{ $location->color }};
      }
    @endforeach
  </style>

  <script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>
  <div id="ev-calendar-app">
    @include("appointment.partials.filters")

    <div class="appointments" :class="busy">
      <div class="appointment-row">
        <div v-for="(col, date) in items" class="appointment-col">
          <h4>@{{ col.date }}</h4>

          <div class="appointment-col__items ">
            <div v-for="(appointment, key) in col.appointments" class="ev-appointment" :class="appointment.type" >

              <span v-if="isBirthday(appointment.type)">
                <i class="fa fa-birthday-cake" aria-hidden="true"></i> @{{ appointment.description }} (@{{ appointment.age}})
              </span>

              <a v-else href="#" :class="locationClass(appointment.location_id)">@{{ appointment.time + " "+ appointment.description }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection