<div class="advanced-search__fieldset users-filter users" >
  @include("appointment.partials.filter-btn", [ "filter_label"=>"Mitarbeiter", "filter"=>"users" ])

  <div class="advanced-search__dropdown-menu wf-dropdown-menu dropdown-menu">
    @foreach( $users as $employment => $items )
      @foreach( $items as $key => $item )

        @if ( !$key )
          <span class="px-2 d-block font-weight-bold">{{ $employment_types[$employment] }}</span>
        @endif
        <div class="wf-dropdown-item ev-select">
          {{
            Form::checkbox('user_ids[]', $item->id, false,
              [
                "id" => 'user_id-'.$item->id,
                "v-model" => "user_ids"
              ] )
          }}

          {{
            Form::label("user_id-".$item->id, $item->first_name." ".$item->last_name,
              [
                "id" => 'user_id-'.$item->id
              ]
            )
          }}
        </div>
      @endforeach
    @endforeach

    @include("appointment.partials.update-btn")
  </div>

</div>