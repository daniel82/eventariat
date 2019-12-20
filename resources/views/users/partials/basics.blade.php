<div class="form-group">
    {{ Form::label("first_name", "Vorname", ["class" => "is-required"])}}
    {{Form::text("first_name", $object->first_name, ["class" => "form-control", "required" => "required" ] )}}
</div>

<div class="form-group">
    {{ Form::label("last_name", "Nachname", ["class" => "is-required"])}}
    {{Form::text("last_name", $object->last_name, ["class" => "form-control", "required" => "required" ] )}}
</div>

<div class="form-group">
    {{ Form::label("birthdate", "Geburtstag", ["class" => ""])}}
    {{Form::date("birthdate", $object->birthdate, ["class" => "form-control"] )}}
</div>