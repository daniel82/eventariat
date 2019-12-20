<hr class="my-5" />
<h4>Passwort</h4>
<div class="form-group">
    {{ Form::label("new_password", "Neues Passwort" ) }}
    {{ Form::password("new_password",
        [
            "class" => "form-control",
            "required" => ($action==="create") ? true : false
        ])
    }}
</div>

<div class="form-group">
    {{ Form::label("confirm_password", "Neues Passwort wiederholen" ) }}
    {{ Form::password("confirm_password",["class" => "form-control"]) }}
</div>