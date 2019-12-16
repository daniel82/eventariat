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
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
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
  startColorPicker();
});

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

    methods:
    {

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
        _log(response);
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
        return "location-"+location_id
      },

      isBirthday : function (type)
      {
        return (type === "birthday" );
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



