<div class="form-group">
  {{ Form::label("status", "Status", ["class"=> "d-block"]) }}
  <select name="status" id="status" class="form-control d-block" v-model="status" :disabled="!is_admin">
    @foreach( config("shift-request.status") as $item )
      <option value="{{ $item["id"] }}" >{{ $item["text"] }}</option>
    @endforeach
  </select>
</div>