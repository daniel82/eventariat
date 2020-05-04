<div class="form-group">
  {{ Form::label("note", "Nachricht", ["class"=> "d-block "]) }}
  {{
    Form::textarea("note", null,
    [
      "class"=> "d-block form-control ev-text-area ",
      "rows"=>3,
      "v-model" =>"note"
    ])
   }}
</div>