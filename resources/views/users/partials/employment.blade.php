<hr class="my-5" />
<h4>Anstellung</h4>

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
