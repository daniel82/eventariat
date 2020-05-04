<div class="form-group text-right">
  <div class="btn-group ev-btn-group ">
  <button type="button" class="btn btn-success" @click="saveAppointment()">Speichern</button>
  <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Weitere Aktionen</span>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item color-green" href="#" @click="saveAppointment('email')">
      <i class="fa fa-envelope" aria-hidden="true"></i>
      Speichern & Email
    </a>
    {{-- <a class="dropdown-item" href="#" @click="saveAppointment('sms')">Speichern & SMS</a>  --}}
    <div class="dropdown-divider"></div>
    <a class="dropdown-item color-red" href="#" @click="deleteAppointment()" v-if="appointment_id">
      <i class="fa fa-trash" aria-hidden="true"></i> L&ouml;schen
    </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item color-red" href="#" @click="deleteAppointment('email')" v-if="appointment_id">
      <i class="fa fa-trash" aria-hidden="true"></i> L&ouml;schen & Email
    </a>
  </div>
</div>
</div>
