@if ( $action === "edit" )
  <hr class="my-5" />
  <h4>Passwort</h4>
  <div class="form-group">
      {{ Form::label("password", "Neues Passwort" ) }}
      {{ Form::password("password",
          [
            "class" => "form-control",
            "required" => false
          ])
      }}
  </div>

  <div class="form-group">
      {{ Form::label("password_confirmation", "Neues Passwort wiederholen" ) }}
      {{ Form::password("password_confirmation",["class" => "form-control"]) }}
  </div>
@endif