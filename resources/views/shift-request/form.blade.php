<div class="form-group">
  {{ Form::label("first_name", "Vorname", ["class" => "is-required"])}}
  {{Form::text("first_name", $user->first_name, ["class" => "form-control", "required" => "required", "readonly" => true ] )}}
</div>

<div class="form-group">
  {{ Form::label("last_name", "Nachname", ["class" => "is-required"])}}
  {{Form::text("last_name", $user->last_name, ["class" => "form-control", "required" => "required" , "readonly" => true] )}}
</div>


<div class="form-group">
  {{ Form::label("type", "Antragsart", ["class"=> "d-block is-required"]) }}
  <select name="type" id="type" class="form-control d-block" v-model="type">
    @foreach( config("shift-request.type") as $item )
      <option value="{{ $item["id"] }}" >{{ $item["text"] }}</option>
    @endforeach
  </select>
</div>



<div class="form-group">
  <span>Von</span>
  <div class="d-flex ">
  {{ Form::date("date_from", null,
    [
      "class"    => "d-block form-control",
      "v-model"  =>"date_from",
      "min"      => $today,
      "@change"  => "validateDates"
    ])
  }}
  </div>


  <span>Bis</span>
  <div class="d-flex">
    {{
      Form::date("date_to", null,
      [
        "class"    => "d-block form-control",
        "v-model"  =>"date_to",
        "min"      => $today,
        "@change"  => "validateDates"
      ])
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
      "v-model" => "note"
    ])
   }}
</div>