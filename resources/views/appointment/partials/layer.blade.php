<div class="ev-layer" v-if="showLayer" id="ev-layer" @click="hideLayer">
  <div class="container container--slim">
    <div class="ev-appointment-form card">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Termin bearbeiten</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="toggleLayer()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <div class="modal-body">
        @if ( $locations && $locations->count() )
          <div class="form-group">
            {{ Form::label("location_id", "Lokalität", ["class"=> "d-block"]) }}
            <select name="location_id" id="location_id" class="form-control d-block" v-model="location_id">
              <option value="">---</option>
              @foreach( $locations as $item )
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
        @endif


        @if ( $users && $users->count() )
          <div class="form-group">
            {{ Form::label("user_id", "Mitarbeiter", ["class"=> "d-block"]) }}
            <select name="user_id" id="user_id" class="form-control d-block" v-model="user_id">
              <option value="">---</option>
              @foreach( $users as $item )
                <option value="{{ $item->id }}">{{ $item->getCalendarName() }}</option>
              @endforeach
            </select>
          </div>
        @endif


        <div class="form-group">
          {{ Form::label("type", "Terminart", ["class"=> "d-block is-required"]) }}
          <select name="type" id="type" class="form-control d-block" v-model="type">
            @foreach( config("appointment.type") as $item )
              <option value="{{ $item["id"] }}">{{ $item["text"] }}</option>
            @endforeach
          </select>
        </div>


        <div class="form-group">
          {{ Form::label("description", "Freitext", ["class"=> "d-block"]) }}
          {{ Form::text("description", null, [
              "class"      => "form-control",
              "placeholder"=>"F&uuml;r Termine ohne Lokalität",
              "v-model"    =>"description"
              ]) }}
        </div>

        <div class="form-group">
          <span>Von</span>
          <div class="d-flex ">
            {{ Form::date("apt_date_from", null,
              [
                "class"=> "d-block form-control w-30",
                "v-model" =>"apt_date_from",
                "min"=> $today
              ])
            }}

            {{ Form::select("time_from", $hours, null,
              [
                "class"=>"form-control w-30 ml-3",
                "v-model" =>"time_from"
              ])
            }}

          </div>


            <span>Bis</span>
            <div class="d-flex">
              {{
                Form::date("apt_date_to", null,
                [
                  "class"=> "d-block form-control w-30",
                  "v-model" =>"apt_date_to",
                  "min"=> $today
                  ]) }}

              {{
                Form::select("time2_to", $hours, null,
                  [
                    "class"=>"form-control w-30  ml-3",
                    "v-model" =>"time_to"
                  ]
                 )
               }}
            </div>

        </div>

        <div class="form-group">
          {{ Form::label("note", "Nachricht", ["class"=> "d-block"]) }}
          {{
            Form::textarea("note", null,
            [
              "class"=> "d-block form-control",
              "rows"=>5,
              "v-model" =>"note"
            ])
           }}
        </div>


        <div class="form-group text-right">
          <div class="btn-group ev-btn-group">
            <ul class="list-unstyled ev-actions" :class="actionsToggled">
              <li class="default">
                <button type="button" class="ev-btn save" @click="saveAppointment()">Speichern</button>
                <button type="button" class="ev-btn toggle-actions" @click="toggleActions">
                  <span class="closed"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                  <span class="opened"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                </button>
              </li>
              <li class="sub-li">
                <button type="button" class="ev-btn save save-and-email" @click="saveAppointment('email')">Speichern & Email</button>
              </li>
              <li class="sub-li">
                <button  type="button" class="ev-btn save save-and-sms" @click="saveAppointment('sms')">Speichern & SMS</button>
              </li>
              <li class="sub-li">
                <button  type="button" class="ev-btn delete" @click="deleteAppointment()">L&ouml;schen</button>
              </li>
            </ul>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>