<div class="bg-black fixed-header">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <h1 class="ev-header__title"><a href="/" class="color-white">Dienstplan</a></h1>
      </div>

      @if ( isset($head["user"]) && is_object($head["user"]) )
        <div class="col-lg-9">
          <nav class="navbar navbar-expand-lg ev-header">
            <button class="navbar-toggler btn btn-light mt-2 py-3 w-100" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fa fa-bars" aria-hidden="true"></i> Men&uuml;
            </button>

            <div class="collapse navbar-collapse ev-nav" id="navbarSupportedContent">
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

                {{-- logout --}}
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
            </div>
          </nav>
        </div>

        <div class="col-lg-3">
          <div class="navbar ">
            <div class="text-right d-block w-100">
              <span class="ev-logged-as">
                <a href="{{ action("UserFrontendController@edit") }}" class="btn btn-light d-block">
                   <i class="fa fa-user-circle" aria-hidden="true"></i> {{$head["user"]->getFullName()}}
                </a>
              </span>
            </div>
          </div>
        </div>
      @endif

    </div>

  </div>
</div>