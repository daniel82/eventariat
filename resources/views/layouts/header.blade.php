<div class="bg-black">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <h1 class="ev-header__title">Dienstplan</h1>
      </div>

      <div class="col-lg-9">
        <nav class="navbar navbar-expand-lg ev-header">
          <button class="navbar-toggler btn btn-light mt-2 py-3 w-100" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars" aria-hidden="true"></i> Men&uuml;
          </button>

          <div class="collapse navbar-collapse ev-nav" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item {{ activeHeaderItem("admin/home") }}" >
                <a class="nav-link" href="/home">Start</a>
              </li>

              {{-- {{ Route::has('dienstplan') }} --}}


              <li class="nav-item {{ activeHeaderItem("dienstplan") }}">
                <a class="nav-link" href="{{ action("AppointmentController@index") }}"><span>Kalender</span></a>
              </li>

              <li class="nav-item {{ activeHeaderItem("admin/shift-requests") }}" >
                <a class="nav-link" href="{{ action("ShiftRequestController@index") }}"><span>Antr&auml;ge</span></a>
              </li>

              @if($head["user"]->isAdmin())
                <li class="nav-item {{ activeHeaderItem("admin/users") }}">
                  <a class="nav-link" href="{{ action("UserController@index") }}"><span>Mitarbeiter</span></a>
                </li>

                <li class="nav-item {{ activeHeaderItem("admin/locations") }}">
                  <a class="nav-link" href="{{ action("LocationController@index") }}"><span>Lokalit&auml;t</span></a>
                </li>
              @endif

              {{-- logout --}}
              <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Abmelden</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                   @csrf
                </form>
              </li>
            </ul>
          </div>
        </nav>
      </div>

      <div class="col-lg-3">
        <div class="navbar ">
          <div class="text-right d-block w-100">
            <span class="ev-logged-as">
              <a href="{{ action("UserFrontendController@edit") }}" class="btn btn-light">
                 <i class="fa fa-user-circle" aria-hidden="true"></i> {{$head["user"]->getFullName()}}
              </a>
            </span>
          </div>
        </div>
      </div>

    </div>



  </div>
</div>