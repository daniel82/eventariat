<div class="advanced-search__fieldset locations-filter locations" >
  @include("appointment.partials.filter-btn", [ "filter_label"=>"Lokalitäten", "filter"=>"locations" ])

  <div class="advanced-search__dropdown-menu wf-dropdown-menu dropdown-menu">
    @foreach( $locations as $item )
      <div class="wf-dropdown-item ev-select">
        {{ Form::checkbox('location_ids[]', $item->id, false,
          [
            "id" => 'location_id-'.$item->id,
            "v-model" => "location_ids"
            ]
          )
        }}
        {{ Form::label("location_id-".$item->id, $item->name,
          [
            "id" => 'location_id-'.$item->id
          ]
          )
        }}
      </div>
    @endforeach

    @include("appointment.partials.update-btn")
  </div>
</div>

