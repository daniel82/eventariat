/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import iro from "@jaames/iro";

require('./bootstrap');

window.Vue = require('vue');

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

// const app = new Vue({
//     el: '#app',
// });



docReady(function() {
    // DOM is loaded and ready for manipulation here
  startColorPicker();
});



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



