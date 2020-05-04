@if ( $users && $users->count() )
  <div class="form-group row" v-if="type!=2 && type!=3">
    {{ Form::label("user_id", "Mitarbeiter", ["class"=> "d-block col-xs-4"]) }}

    <div class="col-xs-8">
      <select name="user_id" id="user_id" class="form-control d-block " v-model="user_id" @change="adminGetUserData">
        <option value="">---</option>
        @foreach( $users as $employment => $items )
          <optgroup label="{{ $employment_types[$employment] }}">
            @foreach( $items as $key => $item )
              <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>

      <div class="d-flex ml-2 mt-1 ">
        <div class="w-50"><span class="appointment-tooltip__icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span> @{{tooltip_work_load}}</div>
        <div class="w-50"><span class="appointment-tooltip__icon"><i class="fa fa-sun-o" aria-hidden="true"></i></span> @{{tooltip_leave_days}}</div>
      </div>
    </div>

  </div>
@endif