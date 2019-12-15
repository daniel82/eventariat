<div class="bg-black">
  <div class="container">

    <nav class="navbar navbar-expand-lg ev-header">

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse ev-nav" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Start</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="#">Kalender</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ action("UserController@index") }}">Mitarbeiter</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ action("LocationController@index") }}">Lokalit&auml;t</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Antr&auml;ge</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Abmelden</a></li>
        </ul>
      </div>
    </nav>
  </div>
</div>