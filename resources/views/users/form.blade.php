<div class="form-group">
    {{ Form::label("first_name", "Vorname", ["class" => "is-required"])}}
    {{Form::text("first_name", $object->first_name, ["class" => "form-control", "required" => "required" ] )}}
</div>

<div class="form-group">
    {{ Form::label("last_name", "Nachname", ["class" => "is-required"])}}
    {{Form::text("last_name", $object->last_name, ["class" => "form-control", "required" => "required" ] )}}
</div>

<div class="form-group">
    {{ Form::label("birthday", "Geburtstag", ["class" => ""])}}
    {{Form::date("birthday", $object->birthday, ["class" => "form-control"] )}}
</div>

<hr class="my-5" />

<div class="form-group">
    {{ Form::label("email", "E-Mail", ["class" => ""])}}
    {{Form::email("email", $object->email, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("mobile", "Mobilnummer", ["class" => ""])}}
    {{Form::text("mobile", $object->mobile, ["class" => "form-control"] )}}
</div>

<div class="form-group">
    {{ Form::label("phone", "Festnetznummer", ["class" => ""])}}
    {{Form::text("phone", $object->phone, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("street", "Stra&szlig;e", ["class" => ""])}}
    {{Form::text("street", $object->street, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("zipcode", "PLZ", ["class" => ""])}}
    {{Form::text("zipcode", $object->zipcode, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("city", "Ort", ["class" => ""])}}
    {{Form::text("city", $object->city, ["class" => "form-control"] )}}
</div>


<hr class="my-5" />


<div class="form-group">
    {{ Form::label("leave_days", "Urlaubstage pro Jahr", ["class" => ""])}}
    {{Form::number("leave_days", $object->leave_days, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("hours_of_work", "Stunden pro Woche", ["class" => ""])}}
    {{Form::number("hours_of_work", $object->hours_of_work, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("role", "Benutzerrolle", ["class" => ""])}}
    {{Form::select("role", config("users.roles"), $object->role, ["class" => "form-control"] )}}
</div>


<div class="form-group">
    {{ Form::label("employment", "Anstellungsart", ["class" => ""])}}
    {{Form::select("employment", config("users.employment"), $object->employment, ["class" => "form-control"] )}}
</div>