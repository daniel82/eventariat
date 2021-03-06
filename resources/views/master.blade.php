<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Eventariat</title>
        {{-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> --}}
        <link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset(mix('/css/app.css') ) }}">
        <script src="{{ asset(mix('js/app.js')) }}"></script>
        <script>csrf_token = "{{csrf_token()}}" ;</script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @include("layouts.header")

            <div class="container">
                <div class="content py-5">
                    <div class="row">
                        <div class="col-md-8">
                            @include("admin.messages.errors")
                            @include("admin.messages.flash")
                        </div>
                    </div>

                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>
