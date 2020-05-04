<div class="form-group row">
  <span class="col-xs-4">Von</span>

  <div class="col-xs-8">
    {{-- date timefrom --}}
    <div class="d-flex ">
      {{ Form::date("apt_date_from", null,
        [
          "class"    => "d-block form-control ev-date-field ",
          "v-model"  =>"apt_date_from",
          "min"      => $today,
          "@change"  => "validateDates"
        ])
      }}
      {{ Form::select("time_from", $from_hours, null,
        [
          "class"    => "form-control ev-date-field ml-3",
          "v-model"  => "time_from",
          "@change"  => "validateTimes"
        ])
      }}
    </div>
  </div>

</div>

<div class="form-group row">
  <span class="col-xs-4">Bis</span>
  <div class="col-xs-8">
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
        Form::select("time2_to", $to_hours, null,
          [
            "class"    =>"form-control ev-date-field  ml-3",
            "v-model"  =>"time_to",
            "@change"  => "validateTimes"
          ]
         )
       }}
    </div>
  </div>
</div>


<div class="form-group row" v-if="type==4">
  {{ Form::label("recurring", "Wiederholung", ["class"=> "d-block col-xs-4"]) }}
  <div class="col-xs-8">
    <select name="type" id="type" class="form-control d-block" v-model="recurring" @change="previewRecurringFutureDates" >
      @foreach( config("appointment.recurring") as $item )
        <option value="{{ $item["id"] }}">{{ $item["text"] }}</option>
      @endforeach
    </select>
  </div>
</div>


<div v-if="recurring!=0 && type==4" class="form-group row" >
  <span class="col-xs-4">Letzter Termin</span>
  <div class="col-xs-8">
    <div class="d-flex">
      {{
        Form::date("apt_date_to", null,
        [
          "class"    => "d-block form-control ev-date-field ",
          "v-model"  =>"apt_repeat_until",
          "@change"  =>"previewRecurringFutureDates",
          "min"      => $today,
        ])
      }}
    </div>
  </div>
</div>




<div v-if="future_events" class="form-group row">
  <span class="col-xs-4">Termine:</span>
  <div class="col-xs-8">
    <div v-for="(event, index) in future_events" class="d-block w-100 ">
      <span class="d-inline-block w-50">@{{ event }}</span>
      <span class="d-inline-block">@{{ time_from }}-@{{ time_to }} Uhr</span>

    </div>
  </div>
</div>


