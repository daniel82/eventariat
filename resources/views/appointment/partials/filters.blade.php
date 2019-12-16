<div class="ev-app-filters mb-5">

  <div class="row">

    @if ( $users && $users->count() )
      <div class="col-md-3">
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
      <div class="col-md-3">
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


    <div class="col-md-3">
      <button @click="updateItems" class="btn btn-primary">Aktualisieren</button>
    </div>


    <div class="col-md-3">
      <div class="ev-calendar-nav">
        <button class="btn btn-secondary" @click="prevMonth()"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
        <button class="btn btn-secondary btn-today" @click="thisWeek()">Heute</button>
        <button class="btn btn-secondary" @click="nextMonth()"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
      </div>
    </div>

  </div>

</div>