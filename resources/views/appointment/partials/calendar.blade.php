@include("appointment.partials.tooltip")

<div class="appointments" :class="busy">
  <div class="appointment-row">
    <div v-for="(col, date) in items" class="appointment-col" :class="isToday(date)">
      <h4 class="appointment-col__title px-1 ">
        <span>@{{ col.date }}</span>
        <div class="weater-forecast" v-if="col.forecast">
          @{{ col.forecast.temperature }}° <span class="xxx"><img class="weater-forecast__icon" :src="buildWeatherIcon(col.forecast.icon)" /></span>
        </div>
      </h4>

      <div class="appointment-col__items ">

        <div v-if="col.appointments.length">
          <div  v-for="(appointment, key) in col.appointments" class="ev-appointment mb-1" :class="appointment.type_class" >

            <div v-if="isLeaveDay(appointment.type_class)">
              <button v-if="is_admin" @click="editAppointment(date,key)" >
                <i class="fa fa-sun-o" aria-hidden="true"></i> @{{ appointment.title }}
              </button>
              <span v-else >
                <i class="fa fa-sun-o" aria-hidden="true"></i> @{{ appointment.title }}
              </span>
            </div>


            <div v-else-if="isFreeDay(appointment.type_class)">
              <button v-if="is_admin" @click="editAppointment(date,key)" >
                <i class="fa fa-sun-o" aria-hidden="true"></i> @{{ appointment.title }}
              </button>
              <span v-else >
                <i class="fa fa-sun-o" aria-hidden="true"></i> @{{ appointment.title }}
              </span>
            </div>

            <span v-else-if="isBirthday(appointment.type_class)">
              <i class="fa fa-birthday-cake" aria-hidden="true"></i> @{{ appointment.title }} (@{{ appointment.age}})
            </span>

            <button v-else-if="is_admin" :id="buildAppointmentId(appointment)" @click="editAppointment(date,key)" :class="locationClass(appointment.location_id)" @mouseover="showTooltip(appointment)" @mouseout="hideTooltip()">
              <span class="mr-1 d-inline-block ev-appointment__duration">@{{ getItemDuration(appointment) }}</span>@{{ appointment.title }}
              <i class="fa fa-info-circle ev-appointment__note-info" aria-hidden="true"  v-if="appointment.note"></i>
            </button>

            <div v-else :class="locationClass(appointment.location_id)" class="readonly-entry" @mouseover="showTooltip(appointment)" @mouseout="hideTooltip()">
              <span class="mr-2 d-inline-block ev-appointment__duration">@{{ getItemDuration(appointment) }}</span>@{{ appointment.title }}
              <i class="fa fa-info-circle ev-appointment__note-info" aria-hidden="true"  v-if="appointment.note"></i>
            </div>

          </div>
        </div>
        <div v-else>
          <span class="px-1">keine Termine</span>
        </div>


        <div class="appointment-col__new-item" v-if="isFutureDate(date) && is_admin">
          <button class="ev-inline-add-btn" type="button" @click=createAppointment(date)><i class="fa fa-plus-circle" aria-hidden="true"></i>
           <span>Neuer Termin</span>
         </button>
       </div>

      </div>
    </div>
  </div>
</div>