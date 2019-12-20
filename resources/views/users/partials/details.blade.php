<hr class="my-5" />
<h4>Kontaktdaten</h4>
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
