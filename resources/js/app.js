/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import iro from "@jaames/iro";

require('./bootstrap');

window.Vue = require('vue');

function _log( message ){
  console.log(message);
}



function docReady(fn) {
  // see if DOM is already available
  if (document.readyState === "complete" || document.readyState === "interactive")
  {
    // call on next available tick
    setTimeout(fn, 1);
  }
  else
  {
    document.addEventListener("DOMContentLoaded", fn);
  }
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));



// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


docReady(function()
 {
  startCalendarApp();
  startShiftRequestApp();
  startColorPicker();
});

function startShiftRequestApp()
{
  if ( !document.getElementById("ev-shift-request-app") )
  {
    return false;
  }


  const app = new Vue(
  {
    el: '#ev-shift-request-app',
    data: ev_app_data,
    methods:
    {
      validateDates : function ()
      {
        if ( this.date_from && !this.date_to || this.date_from && this.date_to &&  this.date_from  > this.date_to )
        {
          this.date_to = this.date_from;
        }
      },
    },
    created : function()
    {
      if ( this.status != 0 && !this.is_admin )
      {
        $("input, select, textarea").attr("disabled", true);
      }
    }
  });
}

function startCalendarApp()
{
  if ( !document.getElementById("ev-calendar-app") )
  {
    return false;
  }


  const app = new Vue(
  {
    el: '#ev-calendar-app',
    data: ev_app_data,

    computed :
    {
      actionsToggled : function()
      {
        return ( this.actions_toggled ) ? "toggled" : "";
      }
    },

    methods:
    {
      toggleDropdownMenu : function (type){
        if ($("."+type).hasClass("show")){
          $("."+type).removeClass("show");
          // this.updateList();
        }
        else{
          $(".advanced-search__fieldset").removeClass("show");
          $("."+type).addClass("show");
        }
      },

      closeAndUpdate : function ( event ){
        $(".advanced-search__fieldset").removeClass("show");
        // this.updateList();
      },

      getRequestData : function()
      {
        let rd =
        {
          "date_from" : this.date_from,
          "date_to"   : this.date_to,
          "users"     : this.user_ids,
          "locations" : this.location_ids
        };

        return rd;
      },

      getItems : function()
      {
        this.ajaxRequest(this.getRequestData());
      },


      nextMonth : function()
      {
        let request_data = this.getRequestData();
        request_data.nav = "next";
        this.ajaxRequest(request_data);
      },


      prevMonth : function()
      {
        let request_data = this.getRequestData();
        request_data.nav = "prev";
        this.ajaxRequest(request_data);
      },


      thisWeek : function()
      {
        let request_data = this.getRequestData();
        request_data.nav = "today";
        this.ajaxRequest(request_data);
      },


      getItemDuration: function( appointment )
      {
        if ( appointment.type_class !== "event" && appointment.time_from && appointment.time_to )
        {
          return appointment.time_from+"-"+appointment.time_to;
        }
      },


      updateItems : function()
      {
        this.getItems();
      },

      ajaxRequest : function(request_data)
      {
        this.busy = "busy";
        $.ajax(
        {
          url: "/api/appointments",
          type: 'GET',
          data: request_data,
          dataType: 'JSON',
          success: this.updateItems_ajaxCallback,
          error: this.updateItems_ajaxCallback
          }
        );
      },


      updateItems_ajaxCallback : function( response )
      {
        // _log(response);
        if ( typeof response === "object" )
        {
          this.items     = response.items;
          this.date_from = response.date_from;
          this.date_to   = response.date_to;
        }

        this.busy = "";
      },

      locationClass : function( location_id )
      {
        location_id =( !isNaN(location_id) ) ? location_id : "none";
        return "location-"+location_id
      },

      isBirthday : function (type)
      {
        return (type === "birthday" );
      },


      isLeaveDay : function (type)
      {
        return (type === "leave-day" );
      },

      isFreeDay : function (type)
      {
        return (type === "free-day" );
      },

      toggleActions : function()
      {
        this.actions_toggled = (this.actions_toggled) ? false : true;
      },

      validateDates : function ()
      {
        if ( this.apt_date_from && !this.apt_date_to || this.apt_date_from && this.apt_date_to &&  this.apt_date_from  > this.apt_date_to )
        {
          this.apt_date_to = this.apt_date_from;
        }
      },

      validateTimes : function ()
      {
        if ( this.time_from && !this.time_to || this.time_from && this.time_to &&  this.time_from  > this.time_to )
        {
          this.time_to = this.time_from;
        }
      },


      isFutureDate : function( date )
      {
        return ( date >= this.today );
      },

      createAppointment : function( date )
      {
        this.resetForm();
        this.apt_date_from = this.apt_date_to = date;
        this.toggleLayer();
      },


      resetForm : function()
      {
        this.resetMessage();

        _log(this.today);
        this.appointment_id  = null;
        this.location_id     = "";
        this.user_id         = "";
        this.type            = "4";
        this.description     = "";
        this.apt_date_from   = this.today;
        this.apt_date_to     = this.today;
        this.time_from       = "08:00";
        this.time_to         = "16:30";
        this.note            = "";
      },

      resetMessage : function()
      {
        this.message         = null;
        this.message_type    = null;
      },

      editAppointment : function(date, key)
      {
        _log(date);
        _log(key);
        // set appointment details before showing layer
        if ( typeof this.items[date].appointments[key] !== "undefined" )
        {
          this.resetMessage();

          _log(this.items[date].appointments[key]);

          this.appointment_id  = this.items[date].appointments[key].id;
          this.location_id     = this.items[date].appointments[key].location_id;
          this.user_id         = this.items[date].appointments[key].user_id;
          this.type            = this.items[date].appointments[key].type;
          this.description     = this.items[date].appointments[key].description;
          this.apt_date_from   = this.items[date].appointments[key].date_from;
          this.apt_date_to     = this.items[date].appointments[key].date_to;
          this.time_from       = this.items[date].appointments[key].time_from;
          this.time_to         = this.items[date].appointments[key].time_to;
          this.note            = this.items[date].appointments[key].note;
        }

        this.toggleLayer();
      },

      saveAppointment : function ( action )
      {
        // _log("saveAppointment");

        action = ( typeof action !== "undefined" ) ? action : null;

        let request_data =
        {
          _token      : csrf_token,
          location_id : this.location_id,
          user_id     : this.user_id,
          type        : this.type,
          description : this.description,
          date_from   : this.apt_date_from,
          time_from   : this.time_from,
          time_to     : this.time_to,
          date_to     : this.apt_date_to,
          note        : this.note,
          action      : action
        };


        let url = "/api/appointments";
        let method = "POST";
        if ( this.appointment_id )
        {
           url += "/"+this.appointment_id;
           method = "PATCH";
        }

        // _log(url);
        // _log(method);
        // _log(request_data);
        $.ajax(
        {
          url:       url,
          type:      method,
          data:      request_data,
          dataType:  'JSON',
          success:   this.saveAppointment_ajaxCallback,
          error:     this.saveAppointment_ajaxCallback
          }
        );

      },

      saveAppointment_ajaxCallback : function( response)
      {
        _log("saveAppointment_ajaxCallback");
        _log(response);
        if ( typeof response === "object" )
        {
          if ( response.status && response.message )
          {
            this.message_type = (response.status === "ok" ) ? "alert-success" : "alert-danger";
            this.message = response.message;
          }


        }
        this.updateItems();
      },


      deleteAppointment : function (  )
      {
        _log("deleteAppointment...");
        _log(this.appointment_id);
        _log( this.getApiUrl() );
        $.ajax(
        {
          url:       this.getApiUrl(),
          type:      "DELETE",
          data:      {_token : csrf_token },
          dataType:  'JSON',
          success:   this.saveAppointment_ajaxCallback,
          error:     this.saveAppointment_ajaxCallback
          }
        );

        this.resetForm();
      },


      getApiUrl : function()
      {
        let url = "/api/appointments";
        if ( this.appointment_id )
        {
          url += "/"+this.appointment_id;
        }

        return url;
      },


      toggleLayer : function()
      {
        this.showLayer = (this.showLayer) ? false : true;
        this.actions_toggled=false;
      },

      hideLayer : function( event )
      {
        if ( typeof event.target.id !== "undefined" && event.target.id === "ev-layer")
        {
          this.toggleLayer();
        }
      },

      buildWeatherIcon : function(icon)
      {
        return "/images/icons/"+icon;
      },

      isToday : function(date)
      {
        return ( date === this.today ) ? "is-today" : "";
      }


    },

    created : function()
    {
      _log("created");
      this.getItems();
    },
  });

}



function startColorPicker()
{
  if ( !document.getElementById("colorWheelDemo") )
  {
    return false;
  }

  var colorWheel = iro.ColorPicker("#colorWheelDemo", {
    width: 300,
    height: 300,
    color: '#fff',
    padding: 6,
    borderWidth: 0,
    borderColor: '#fff',
    handleRadius: 8,
    // handleSvg: null, // Custom handle SVG
    // handleOrigin: {
    //   x: 0,
    //   y: 0
    // },
    wheelLightness: true,
    wheelAngle: 0, // starting angle
    wheelDirection: 'anticlockwise', // clockwise/anticlockwise
    // sliderHeight: undefined,
    sliderMargin: 12,
    display: 'inline-block', // CSS display value
    // layout: {} // Custom Layouts
  });

  colorWheel.on('color:change', function(color, changes){
    document.getElementById("color").value = color.hexString;
  });

  colorWheel.on('color:init', function(color){
    console.log(document.getElementById("color").value);
    color.set( document.getElementById("color").value );
  });
}



