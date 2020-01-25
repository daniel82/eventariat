<div class="advanced-search__fieldset appointment_types-filter appointment_types" >
  @include("appointment.partials.filter-btn", [ "filter_label"=>"Terminart", "filter"=>"appointment_types" ])

  <div class="advanced-search__dropdown-menu wf-dropdown-menu dropdown-menu">

    @foreach( $appointment_types as $item )
      <div class="wf-dropdown-item ev-select">
        {{ Form::checkbox('appointment_types[]', $item["id"], false,
          [
            "id" => 'appointment_type-'.$item["id"],
            "v-model" => "appointment_types"
            ]
          )
        }}
        {{ Form::label("appointment_type-".$item["id"], $item["text"],
          [
            "id" => 'appointment_type-'.$item["id"]
          ]
          )
        }}
      </div>
    @endforeach

    @include("appointment.partials.update-btn")
  </div>
</div>

