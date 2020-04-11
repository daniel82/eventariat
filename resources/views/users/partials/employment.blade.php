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
    {{ Form::select("employment", config("users.employment"), $object->employment, ["class"=>"form-control", "v-model"=>"employment"] )}}
</div>


@if ( isset($locations) && count($locations) )
    <div v-if="employment==='permanent' || employment==='training'">
        <h5>Lokalit&auml;ten</h5>
        <div class="d-flex justify-content-start flex-wrap">
            @foreach( $locations as $location )
                <div class="form-group w-50 " >
                    {{ Form::checkbox("location_ids[]", $location->id, $user_locations->contains($location->id), ["id"=>"location-".$location->id] )}}
                    {{ Form::label("location-".$location->id, $location->name )}}
                </div>
            @endforeach
        </div>
    </div>
@endif
