<div class="form-group row">
  {{ Form::label("type", "Terminart", ["class"=> "d-block is-required col-xs-4"]) }}
  <div class="col-xs-8">
    <select name="type" id="type" class="form-control d-block " v-model="type" @change="presetTimes">
      @foreach( config("appointment.type") as $item )
        <option value="{{ $item["id"] }}">{{ $item["text"] }}</option>
      @endforeach
    </select>
  </div>
</div>