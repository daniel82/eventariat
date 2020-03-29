<div class="form-group">
    {{ Form::label("name", "Name", ["class" => "is-required"])}}
    {{Form::text("name", $object->name, ["class" => "form-control", "required" => "required" ] )}}
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


<div class="form-group">
    {{ Form::label("color", "Farbe", ["class" => ""])}}
    {{Form::text("color", $object->color, ["class" => "form-control", "id"=>"color" ] )}}

</div>

<div class="wheel" id="colorWheelDemo"></div>
