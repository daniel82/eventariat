<div class="form-group row" v-if="type==2">
  {{ Form::label("description", "Freitext", ["class"=> "d-block col-xs-4"]) }}

  <div class="col-xs-8">
    {{
      Form::text("description", null,
      [
        "class"      => "form-control",
        "placeholder"=>"F&uuml;r Termine ohne Lokalität",
        "v-model"    =>"description"
      ])
    }}
  </div>
</div>