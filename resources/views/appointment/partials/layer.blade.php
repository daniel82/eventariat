<div class="ev-layer" v-if="showLayer" id="ev-layer" @click="hideLayer">
  <div class="container container--slim">
    <div class="ev-appointment-form card">
      @include("appointment.partials.layer-header")

      <div class="modal-body">
        <div class="alert " :class="message_type" v-if="message && message_type">@{{ message }}</div>

        @if ( $user->isAdmin() )
          @include("appointment.partials.layer-fields")
        @else
          @include("appointment.partials.tooltip-mobile")
        @endif


      </div>

    </div>
  </div>
</div>