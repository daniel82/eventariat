{{ Form::open(['action' => ['UserController@index' ], 'method' => 'GET']) }}
  <div class="row">

    <div class="col-md-2">
      <div class="form-group">
        <label for="status">Status</label>
        <select name="trashed" id="trashed" class="form-control">
          <option value="">Aktive</option>
          <option value="1" {{ isSelected("1", $trashed) }} >Gelöschte</option>
        </select>
      </div>
    </div>

    <div class="col-md-2">
      <div class="form-group">
        {{ Form::label("first_name", "Vorname") }}
        {{ Form::text("first_name", $first_name, ["class"=>"form-control" ]) }}
      </div>
    </div>

    <div class="col-md-2">
      <div class="form-group">
        {{ Form::label("last_name", "Nachname") }}
        {{ Form::text("last_name", $last_name, ["class"=>"form-control" ]) }}
      </div>
    </div>


    <div class="col-lg-2 col-md-3">
      <div class="form-group">
        {{ Form::label("email", "E-Mail") }}
        {{ Form::text("email", $email, ["class"=>"form-control" ]) }}
      </div>
    </div>

    <div class="col-md-2">
      <label style="opacity: 0" class="d-block w-100">Aktualisieren</label>
      <button  type="submit" class="btn btn-primary ev-refresh-btn"><i class="fa fa-refresh" aria-hidden="true"></i></button>
    </div>

  </div>
{{ Form::close() }}