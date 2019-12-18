<div class="appointments" :class="busy">
  <div class="appointment-row">
    <div v-for="(col, date) in items" class="appointment-col">
      <h4>@{{ col.date }}</h4>

      <div class="appointment-col__items ">
        <div v-for="(appointment, key) in col.appointments" class="ev-appointment" :class="appointment.type_class" >

          <span v-if="isBirthday(appointment.type_class)">
            <i class="fa fa-birthday-cake" aria-hidden="true"></i> @{{ appointment.title }} (@{{ appointment.age}})
          </span>

          <button v-else @click="editAppointment(date,key)" :class="locationClass(appointment.location_id)">
            <span class="mr-1 d-inline-block ev-appointment__duration">@{{ getItemDuration(appointment) }}</span> @{{ appointment.title }}</button>
        </div>


        <div class="appointment-col__new-item" v-if="isFutureDate(date)">
          <button class="ev-inline-add-btn" type="button" @click=createAppointment(date)><i class="fa fa-plus-circle" aria-hidden="true"></i>
           <span>Neuer Termin</span>
         </button>
       </div>

      </div>
    </div>
  </div>
</div>