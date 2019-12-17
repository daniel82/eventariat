<div class="appointments" :class="busy">
  <div class="appointment-row">
    <div v-for="(col, date) in items" class="appointment-col">
      <h4>@{{ col.date }}</h4>

      <div class="appointment-col__items ">
        <div v-for="(appointment, key) in col.appointments" class="ev-appointment" :class="appointment.type_class" >

          <span v-if="isBirthday(appointment.type_class)">
            <i class="fa fa-birthday-cake" aria-hidden="true"></i> @{{ appointment.title }} (@{{ appointment.age}})
          </span>

          <button v-else @click="editAppointment(date,key)" :class="locationClass(appointment.location_id)">@{{ buildEntry(appointment) }}</button>
        </div>
      </div>
    </div>
  </div>
</div>