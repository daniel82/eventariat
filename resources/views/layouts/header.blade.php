<div class="bg-black fixed-header">
  <div class="container">

    <nav class="navbar navbar-expand-lg navbar-dark ev-header">
      <a class="navbar-brand" href="#">Dienstplan</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation"><i class="fa fa-bars" aria-hidden="true"></i> Menü</button>

      @if ( isset($head["user"]) && is_object($head["user"]) )
        <div class="collapse navbar-collapse" id="navbarColor01">
          <ul class="navbar-nav mr-auto">
            @if($head["user"]->isAdmin())
                <li class="nav-item {{ activeHeaderItem("admin/home") }}" >
                  <a class="nav-link" href="/home"><span class="nav-text">Start</span></a>
                </li>
            @endif

            <li class="nav-item {{ activeHeaderItem("dienstplan") }}">
              <a class="nav-link" href="{{ action("AppointmentController@index") }}"><span class="nav-text">Kalender</span></a>
            </li>


            @if($head["user"]->isAdmin())
              <li class="nav-item {{ activeHeaderItem("admin/shift-requests") }}" >
                <a class="nav-link" href="{{ action("ShiftRequestController@index") }}">
                  <div class="nav-text">Antr&auml;ge
                    @if ( $count_shift_requests )
                    <span class="badge {{  ( activeHeaderItem("admin/shift-requests") === "active" ) ?  'badge-dark' : 'badge-light' }}">{{$count_shift_requests}}
                    </span>
                    @endif
                  </div>
                </a>
              </li>

              <li class="nav-item {{ activeHeaderItem("admin/users") }}">
                <a class="nav-link" href="{{ action("UserController@index") }}"><span class="nav-text">Mitarbeiter</span></a>
              </li>

              <li class="nav-item {{ activeHeaderItem("admin/locations") }}">
                <a class="nav-link" href="{{ action("LocationController@index") }}"><span class="nav-text">Lokalit&auml;t</span></a>
              </li>
            @endif


            <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="nav-text">Abmelden</span></a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                 @csrf
              </form>
            </li>


            @if ( isset($head["return_name"]) && isset($head["return_link"]) )
              <li class="nav-item">
                <a class="nav-link" href="{{ $head["return_link"] }}">
                  <span class="nav-text"><i class="fa fa-sign-out" aria-hidden="true"></i> {{$head["return_name"]}}</span>
                </a>
              </li>
            @endif
          </ul>

          <div class="form-inline">
            <span class="ev-logged-as">
              <a href="{{ action("UserFrontendController@edit") }}" class="btn btn-light d-block">
                 <i class="fa fa-user-circle" aria-hidden="true"></i> {{$head["user"]->getFullName()}}
              </a>
            </span>
          </div>
        </div>
      @endif
    </nav>
  </div>
</div>