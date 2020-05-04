@if ( $locations && $locations->count() )
  <div class="form-group row" v-if="type==4">
    {{ Form::label("location_id", "Lokalität", ["class"=> "d-block col-xs-4"]) }}

    <div class="col-xs-8">
      <select name="location_id" id="location_id" class="form-control" v-model="location_id">
        <option value="">---</option>
        @foreach( $locations as $item )
          <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
@endif