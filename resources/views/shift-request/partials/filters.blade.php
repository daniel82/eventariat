{{ Form::open(['action' => ['ShiftRequestController@index' ], 'method' => 'GET']) }}
  <div class="row">

    <div class="col-md-3">
      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
          <option value="">alle</option>
          @foreach($statuses as $s)
            <option value="{{ $s["id"]}}" {{ isSelected($s["id"], $status ) }} >{{ $s["text"] }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label for="user_ids">Mitarbeiter</label>
        <select name="user_id" id="user_id" class="form-control">
          <option value="">alle</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}" {{ isSelected( $user->id, $user_id ) }}>{{ $user->first_name }} {{ $user->last_name }} </option>
          @endforeach
        </select>
      </div>
    </div>


    <div class="col-md-3">
      <div class="form-group">
        <label for="type">Typ</label>
        <select name="type" id="type" class="form-control">
          <option value="">alle</option>
          @foreach($types as $t)
            <option value="{{ $t["id"]}}" {{ isSelected($t["id"], $type ) }}>{{ $t["text"] }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-3">
      <label style="opacity: 0" class="d-block w-100">Aktualisieren</label>
      <button  type="submit" class="btn btn-primary ev-refresh-btn"><i class="fa fa-refresh" aria-hidden="true"></i></button>
    </div>



  </div>
{{ Form::close() }}