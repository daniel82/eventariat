<hr class="my-5" />
<h4>Rechte</h4>
<div class="form-group mb-4">
    {{ Form::label("can_see_other_appointments", "Kann andere Termine sehen", ["class" => "d-block"]) }}
    {{Form::select("can_see_other_appointments", config("users.can_see_other_appointments"), $object->can_see_other_appointments, ["id"=>"can_see_other_appointments", "class"=>"form-control"] )}}
</div>


<div class="form-group">
    <div class="    ev-checkboxes">
        @foreach( config("users.appointment_types") as $key  => $label )
            <div class="d-flex-item w-25">
                {{ Form::checkbox("appointment_types[]", $key, in_array($key, $appointment_types), ["id" => $key ] ) }}
                {{ Form::label($key, $label) }}
            </div>
        @endforeach
    </div>
</div>