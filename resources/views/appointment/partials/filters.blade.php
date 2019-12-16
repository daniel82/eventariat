<div class="row">

  @if ( $users && $users->count() )
    <div class="col-md-4">
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
  @endif


  @if ( $locations && $locations->count() )
    <div class="col-md-4">
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
    </div>
  @endif


  <div class="col-md-4">
    <button @click="updateItems" class="btn btn-primary">Aktualisieren</button>

  </div>

</div>