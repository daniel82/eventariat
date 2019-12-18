<div class="advanced-search__fieldset users-filter users" >
  @include("appointment.partials.filter-btn", [ "filter_label"=>"Mitarbeiter", "filter"=>"users" ])

  <div class="advanced-search__dropdown-menu wf-dropdown-menu dropdown-menu">
    @foreach( $users as $item )
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
  </div>
</div>