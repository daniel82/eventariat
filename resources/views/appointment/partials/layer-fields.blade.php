@if ( $locations && $locations->count() )
  <div class="form-group">
    {{ Form::label("location_id", "Lokalität", ["class"=> "d-block"]) }}
    <select name="location_id" id="location_id" class="form-control d-block" v-model="location_id">
      <option value="">---</option>
      @foreach( $locations as $item )
        <option value="{{ $item->id }}">{{ $item->name }}</option>
      @endforeach
    </select>
  </div>
@endif

<div class="form-group">
  {{ Form::label("description", "Freitext", ["class"=> "d-block"]) }}
  {{
    Form::text("description", null,
    [
      "class"      => "form-control",
      "placeholder"=>"F&uuml;r Termine ohne Lokalität",
      "v-model"    =>"description"
    ])
  }}
</div>


@if ( $users && $users->count() )
  <div class="form-group">
    {{ Form::label("user_id", "Mitarbeiter", ["class"=> "d-block"]) }}
    <select name="user_id" id="user_id" class="form-control d-block" v-model="user_id">
      <option value="">---</option>
      @foreach( $users as $item )
        <option value="{{ $item->id }}">{{ $item->getCalendarName() }}</option>
      @endforeach
    </select>
  </div>
@endif


<div class="form-group">
  {{ Form::label("type", "Terminart", ["class"=> "d-block is-required"]) }}
  <select name="type" id="type" class="form-control d-block" v-model="type">
    @foreach( config("appointment.type") as $item )
      <option value="{{ $item["id"] }}">{{ $item["text"] }}</option>
    @endforeach
  </select>
</div>


<div class="form-group">
  <span>Von</span>
  <div class="d-flex ">
    {{ Form::date("apt_date_from", null,
      [
        "class"    => "d-block form-control ev-date-field ",
        "v-model"  =>"apt_date_from",
        "min"      => $today,
        "@change"  => "validateDates"
      ])
    }}

    {{ Form::select("time_from", $hours, null,
      [
        "class"    => "form-control ev-date-field ml-3",
        "v-model"  => "time_from",
        "@change"  => "validateTimes"
      ])
    }}
  </div>


    <span>Bis</span>
    <div class="d-flex">
      {{
        Form::date("apt_date_to", null,
        [
          "class"    => "d-block form-control ev-date-field ",
          "v-model"  =>"apt_date_to",
          "min"      => $today,
          "@change"  => "validateDates"
        ])
      }}

      {{
        Form::select("time2_to", $hours, null,
          [
            "class"    =>"form-control ev-date-field  ml-3",
            "v-model"  =>"time_to",
            "@change"  => "validateTimes"
          ]
         )
       }}
    </div>

</div>

<div class="form-group">
  {{ Form::label("note", "Nachricht", ["class"=> "d-block"]) }}
  {{
    Form::textarea("note", null,
    [
      "class"=> "d-block form-control",
      "rows"=>3,
      "v-model" =>"note"
    ])
   }}
</div>


<div class="form-group text-right">
  <div class="btn-group ev-btn-group ">
  <button type="button" class="btn btn-success" @click="saveAppointment()">Speichern</button>
  <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Weitere Aktionen</span>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item color-green" href="#" @click="saveAppointment('email')">
      <i class="fa fa-envelope" aria-hidden="true"></i>
      Speichern & Email
    </a>
    {{-- <a class="dropdown-item" href="#" @click="saveAppointment('sms')">Speichern & SMS</a>  --}}
    <div class="dropdown-divider"></div>
    <a class="dropdown-item color-red" href="#" @click="deleteAppointment()" v-if="appointment_id">
      <i class="fa fa-trash" aria-hidden="true"></i> L&ouml;schen
    </a>
  </div>
</div>
</div>
