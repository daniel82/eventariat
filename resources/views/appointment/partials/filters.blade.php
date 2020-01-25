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
      <a :href="exportPdfUrl" id="ev-pdf-export-link" class="btn btn-danger ev-refresh-btn"><i class="fa fa-download" aria-hidden="true"></i></a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="ev-calendar-nav ">
        <button class="btn btn-secondary" @click="prevMonth()"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
        <button class="btn btn-secondary btn-today" @click="thisWeek()">Heute</button>
        <button class="btn btn-secondary" @click="nextMonth()"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>

</div>