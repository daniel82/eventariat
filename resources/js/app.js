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
        if ( this.date_from && !this.date_to || this.date_from && this.date_to && this.date_from > this.date_to )
        {
          this.date_to = this.date_from;
        }

        this.checkAppointments();
      },

      checkAppointments : function()
      {
        // _log("checkAppointments...");

        if ( this.date_from && this.date_to )
        {
          let request_data =
          {
            users : [this.user_id],
            date_from : this.date_from,
            date_to : this.date_to,
          };

          this.ajaxRequest(request_data);
        }

      },

      ajaxRequest : function(request_data)
      {
        this.show_alert = false;
        this.count_appointments = 0;

        this.busy = "busy";
        $.ajax(
        {
          url: "/api/users/"+this.user_id+"/appointments",
          type: 'GET',
          data: request_data,
          dataType: 'JSON',
          success: this.checkAppointments_ajaxCallback,
          error: this.checkAppointments_ajaxCallback
          }
        );
      },

      checkAppointments_ajaxCallback : function( response )
      {
        if ( typeof response === "object" && response.length )
        {
          this.count_appointments = response.length;
          this.show_alert = true;
        }
      },

      validateTimes : function ()
      {
        if ( this.time_from && !this.time_to || this.time_from && this.time_to &&  this.time_from  > this.time_to )
        {
          this.time_to = this.time_from;
        }
      },


    },
    created : function()
    {
      if ( this.status != 0 )
      {
        $("input, select, textarea").attr("disabled", true);
      }
      else
      {
        this.checkAppointments();
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
      },

      tooltipStyles: function()
      {
        return {
          'left': (this.tooltip_x-270) + 'px',
          'top': (this.tooltip_y-0) + 'px',
        };
      },

      exportPdfUrl: function()
      {
        return this.pdf_url+"?"
          +"date_from="+this.date_from
          +"&date_to="+this.date_to

          + ((this.user_ids.length) ? "&users[]="+this.user_ids : '')
          + ((this.location_ids.length) ? "&locations[]="+this.location_ids : '');
      }
    },

    methods:
    {
      toggleDropdownMenu : function (type)
      {
        if ($("."+type).hasClass("show"))
        {
          $("."+type).removeClass("show");
          this.updateItems();
        }
        else
        {
          $(".advanced-search__fieldset").removeClass("show");
          $("."+type).addClass("show");
        }
      },

      closeAndUpdate : function ( event ){
        $(".advanced-search__fieldset").removeClass("show");
        this.updateItems();
      },

      getRequestData : function()
      {
        let rd =
        {
          "date_from" : this.date_from,
          "date_to"   : this.date_to,
          "users"     : this.user_ids,
          "locations" : this.location_ids,
          "types"      : this.appointment_types
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
        $(".advanced-search__fieldset").removeClass("show");
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
        // _log("updateItems_ajaxCallback");
        if ( typeof response === "object" )
        {
          this.items     = response.items;
          this.date_from = response.date_from;
          this.date_to   = response.date_to;
          this.weeks     = response.weeks;

          $(function () {
            $('[data-toggle="popover"]').popover()
          })

          setTimeout(this.equalHeightItems, 1000);
        }

        this.busy = "";
      },


      equalHeightItems : function()
      {
        if ( $(window).width() <= 1024 )
        {
          return false;
        }

        let date = null;
        let week_number = null;

        for( week_number in this.weeks)
        {
          let max_height = 140;
          let column_query = ".week-"+week_number+" .appointment-col__items";
          $(column_query).height(max_height);

          let columns = $(column_query);

          $.each ( columns, function(key, element )
          {
            let tmp_height = element.scrollHeight;
            max_height = ( (tmp_height) > max_height ) ? tmp_height : max_height;
            // max_height -= 26;
          });


          // _log(column_query);
          // _log("max_height");
          // _log(max_height);

          $(column_query).height(max_height+26);
        }

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


      isSick : function (type)
      {
        return (type === "sick" );
      },

      isPrivate : function (type)
      {
        return (type === "private" );
      },


      isFewo : function (type)
      {
        return (type === "fewo" );
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
        this.adminGetUserData();
      },

      increaseDateTo : function()
      {
        let to_date = new Date(this.apt_date_to);
        let time_ms = to_date.getTime();

        // add 1 day
        to_date.setTime( time_ms+(60*60*24*1000) );

        let day = to_date.getDate();
        let month = to_date.getMonth()+1;
        day = (day<10) ? "0"+day : day;
        month = (month<10) ? "0"+month : month;
        this.apt_date_to = to_date.getFullYear()+"-"+month+"-"+day;
      },


      validateTimes : function ()
      {

        if ( this.time_to === "24:00")
        {
          this.increaseDateTo();
          this.time_to = "00:00";
        }

        if ( this.time_from && !this.time_to || this.time_from && this.time_to && this.time_from > this.time_to && this.apt_date_from == this.apt_date_to )
        {
          this.time_to = this.time_from;
        }
      },

      presetTimes : function()
      {
        if ( this.type == 1 || this.type == 6 || this.type == 7)
        {
          this.time_from = this.time_to = "";
        }
        else
        {
           this.time_from = this.default_time_from;
           this.time_to   = this.default_time_to;
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

      buildAppointmentId: function( appointment )
      {
        return "appointment-"+appointment.id;
      },


      getUserData : function(appointment)
      {
        // _log("getUserData");
        // _log(this.is_admin);
        // _log(appointment.user_id);
        // _log(this.current_user);

        if ( !this.is_admin && appointment.user_id != this.current_user )
        {
          this.tooltip_work_load = "";
          this.tooltip_leave_days = "";
          return false;
        }

        if ( this.ajax_active )
        {
          this.ajax_active.abort();
        }

        this.ajaxGetUserData( appointment.user_id, appointment.date_from );
      },

      adminGetUserData: function()
      {
        this.ajaxGetUserData(this.user_id, this.apt_date_from );
      },


      ajaxGetUserData : function( user_id, date )
      {

        if ( typeof user_id !== "undefined" && user_id )
        {
          let request_data =
          {
            date : date,
          };

          this.ajax_active = $.ajax(
          {
            url:       "/api/users/"+user_id,
            type:      "GET",
            data:      request_data,
            dataType:  'JSON',
            success:   this.getUserData_ajaxCallback,
            error:     this.getUserData_ajaxCallback
            }
          );
        }

      },


      getUserData_ajaxCallback : function( response )
      {
        if (  typeof response === "object" )
        {
          this.tooltip_work_load = response.tooltip_work_load;
          this.tooltip_leave_days = response.tooltip_leave_days;
        }
        else
        {
          this.tooltip_work_load = "";
          this.tooltip_leave_days = "";
        }
      },


      getPosition : function offset(el)
      {
        var rect = el.getBoundingClientRect(),
        scrollLeft   = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop    = window.pageYOffset || document.documentElement.scrollTop;

        return {
          top: rect.top + scrollTop,
          left: rect.left + scrollLeft,
        };
      },


      showTooltip : function(appointment)
      {
        if ( appointment.type == 4 )
        {
          // load tool tip data
          this.loadTooltip(appointment);

          // set tool tip position
          let element = document.getElementById(this.buildAppointmentId(appointment));
          let position = this.getPosition(element);
          let style = "left";
          if ( position.left < 400 )
          {
            position.left += (document.getElementById("appointment-"+appointment.id).offsetWidth+280);
            style = "right";
          }

          this.setToolTip(position.left,position.top, style);
        }
      },


      loadTooltip : function ( appointment )
      {
        this.tooltip_title    = appointment.tooltip_title;
        this.tooltip_time     = this.getItemDuration(appointment);
        this.tooltip_location = appointment.tooltip_location;
        this.tooltip_info     = appointment.note;

        this.getUserData(appointment);
      },


      hideTooltip : function(appointment)
      {
        this.setToolTip(0,0);
        document.getElementById("appointment-tooltip").classList.remove("place-right");
      },


      setToolTip : function(x, y, style )
      {
        this.tooltip_x = parseInt(x);
        this.tooltip_y = parseInt(y);

        if ( style === "right" )
        {
          document.getElementById("appointment-tooltip").classList.add("place-right");
        }
      },


      editAppointment : function(date, key)
      {
        // set appointment details before showing layer
        if ( typeof this.items[date].appointments[key] !== "undefined" )
        {

          this.resetMessage();
          let appointment = this.items[date].appointments[key];

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

          this.loadTooltip(appointment);
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

      saveAppointment_ajaxCallback : function(response)
      {
        // _log("saveAppointment_ajaxCallback");
        // _log(response);
        if ( typeof response === "object" )
        {
          if ( response.status && response.message )
          {
            this.message_type = (response.status === "ok" ) ? "alert-success" : "alert-danger";
            this.message = response.message;
            this.appointment_id = response.id;
          }
        }

        this.adminGetUserData( this.user_id, this.apt_date_from );
        this.updateItems();


        if ( this.message_type === "alert-success" )
        {
          this.hideLayer(null, true);
        }

      },


      messageFadeOut : function()
      {
        this.message = null;
      },


      deleteAppointment : function ( action )
      {
        // _log("deleteAppointment...");
        // _log(this.appointment_id);
        // _log( this.getApiUrl() );
        action = ( typeof action !== "undefined" ) ? action : null;
        $.ajax(
        {
          url:       this.getApiUrl(),
          type:      "DELETE",
          data:      {_token : csrf_token, action : action },
          dataType:  'JSON',
          success:   this.saveAppointment_ajaxCallback,
          error:     this.saveAppointment_ajaxCallback,
        });

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

      hideLayer : function( event, force )
      {
        let force_hide = (typeof force === "boolean" && force === true) ? true : false;
        if ( force_hide )
        {
          this.toggleLayer();
          setTimeout( this.messageFadeOut, 2000 );
        }
        else if ( typeof event.target.id !== "undefined" && event.target.id === "ev-layer" )
        {
          this.messageFadeOut();
          this.toggleLayer();
        }
      },

      buildWeatherIcon : function(icon)
      {
        return "/images/icons/"+icon;
      },


      getCssClasses : function(date, week)
      {
        let css_class =  ( date === this.today ) ? " is-today" : "";

        return css_class += ' week-'+week;
      },

      // isToday : function(date)
      // {
      //   return ( date === this.today ) ? "is-today" : "";
      // },


      // weekNumber : function(date)
      // {
      //   return 'week';
      // },

      isNewLocation : function( location_id, date )
      {
        let is_new = false;
        let hash = date+"-"+location_id;
        if ( location_id && group != hash )
        {
          group = hash;
          is_new = true;
        }

        return is_new;
      }

    },

    created : function()
    {
      // _log("created");
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



