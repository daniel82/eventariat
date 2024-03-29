<div class="form-group row">
  <span class="col-xs-4">Von</span>

  <div class="col-xs-8">
    {{-- date timefrom --}}
    <div class="d-flex ">
      {{ Form::date("apt_date_from", null,
        [
          "class"    => "d-block form-control ev-date-field ",
          "v-model"  =>"apt_date_from",
          ":min"      => "getDateMin()",
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
          ":min"      => "getDateMin()",
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

<div class="form-group row" v-if="type==5||type==4">
  {{ Form::label("recurring", "Wiederholung", ["class"=> "d-block col-xs-4"]) }}
  <div class="col-xs-8">
    <select name="type" id="type" class="form-control d-block" v-model="recurring" @change="previewRecurringFutureDates">
      @foreach( config("appointment.recurring") as $item )
        <option value="{{ $item["id"] }}">{{ $item["text"] }}</option>
      @endforeach
    </select>
  </div>
</div>

<div v-if="recurring=='weekly' && (type==5 || type==4)" class="form-group row" >
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

<div v-if="future_events && recurring==='weekly'" class="form-group row">
  <span class="col-xs-4">Termine:</span>
  <div class="col-xs-8">
    <div v-for="(event, index) in future_events" class="d-block w-100">
      <span class="d-inline-block w-50">@{{ event }}</span>
      <span class="d-inline-block">@{{ time_from }}-@{{ time_to }} Uhr</span>
    </div>
  </div>
</div>

<div v-if="future_events && recurring==='daily'" class="form-group row">
  <span class="col-xs-4">Termine:</span>
  <div class="col-xs-8 d-flex">
    <div v-for="(event, date) in future_events" class="d-inline-block">
      <label class="text-center" :class="(date===apt_date_from) ? 'font-weight-bold color-red' : '' ">
        <input type="checkbox" value="date" class="form-control" name="recurring_dates[]" :value="date" v-model="recurring_dates">@{{ event }}
      </label>
    </div>
  </div>
</div>