<div class="ev-app-filters mb-5">

  <div class="row">

    @if ( $users && $users->count() )
      <div class="col-md-3">
        @include("appointment.partials.users-filter")
      </div>
    @endif


    @if ( $locations && $locations->count() )
      <div class="col-md-3">
        @include("appointment.partials.locations-filter")
      </div>
    @endif


    <div class="col-md-3">
        @include("appointment.partials.types-filter")
    </div>


    <div class="col-md-3 ">
      <button @click="updateItems" class="btn btn-primary ev-refresh-btn"><i class="fa fa-refresh" aria-hidden="true"></i></button>
      <a :href="exportPdfUrl" id="ev-pdf-export-link" class="btn btn-danger ev-refresh-btn d-none d-lg-inline-block d-xl-inline-block
"><i class="fa fa-download" aria-hidden="true"></i></a>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-9">
      <div class="text-center">
        <h3 id="appointment-range" class="pt-2 appointment-range">@{{date_from_hr}} - @{{date_to_hr}}</h3>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="ev-calendar-nav row">
        <div class="col nav-btn">
          <button class="btn btn-secondary d-block w-100" @click="prevMonth()"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
        </div>

        <div class="col">
          <button class="btn btn-secondary btn-today d-block w-100" @click="thisWeek()">Heute</button>
        </div>

        <div class="col nav-btn">
          <button class="btn btn-secondary d-block w-100" @click="nextMonth()"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>

</div>