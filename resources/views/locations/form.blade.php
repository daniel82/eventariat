<ul class="nav nav-tabs" id="location-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Details</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false">Mitarbeiter</a>
  </li>

</ul>


<div class="tab-content" id="locationTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      @include("locations.form-fields")
  </div>
  <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
        @if ( $users = $object->users()->orderBy("first_name")->get() )
            <ul class="list-group mt-3">
                @foreach( $users as $user )
                    <li class="list-group-item">
                        <a class="d-block" href="{{ action("UserController@edit", $user->id) }}">{{ $user->first_name }} {{ $user->last_name }}</a>
                    </li>
                @endforeach
            </ul>
      @endif
  </div>
</div>