<div class="appointment-tooltip" id="appointment-tooltip"  :style="tooltipStyles" >
  <p><span class="appointment-tooltip__icon"><i class="fa fa-user" aria-hidden="true"></i></span> @{{ tooltip_title }}</p>
  <p><span class="appointment-tooltip__icon"><i class="fa fa-clock-o" aria-hidden="true"></i></span> @{{ tooltip_time }}</p>
  <p><span class="appointment-tooltip__icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span> @{{ tooltip_location }}</p>

{{--   <p v-if="tooltip_info">
    <strong class="appointment-tooltip__icon"><i class="fa fa-info-circle" aria-hidden="true"></i> </strong> @{{ tooltip_info }}
  </p> --}}

  <p><span class="appointment-tooltip__icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span> @{{tooltip_work_load}}</p>
  <p><span class="appointment-tooltip__icon"><i class="fa fa-sun-o" aria-hidden="true"></i></span> @{{tooltip_leave_days}}</p>
</div>