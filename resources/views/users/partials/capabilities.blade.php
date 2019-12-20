<hr class="my-5" />
<h4>Rechte</h4>
<div class="form-group">
    {{ Form::checkbox("can_see_other_appointments", 1, $object->can_see_other_appointments ) }}
    {{ Form::label("can_see_other_appointments", "Kann andere Termine sehen", ["class" => ""]) }}
</div>


<div class="form-group">
    <div class="d-flex justify-content-start">
        @foreach( config("users.appointment_types") as $key  => $label )
            <div class="d-flex-item w-25">
                {{ Form::checkbox("appointment_types[]", $key, in_array($key, $appointment_types), ["id" => $key ] ) }}
                {{ Form::label($key, $label) }}
            </div>
        @endforeach
    </div>
</div>