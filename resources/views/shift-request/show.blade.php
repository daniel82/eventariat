@extends("master")

@section("content")
    <div class="form-inner">
        <h2>Antrag</h2>
        <script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>
        <div id="ev-shift-request-app">

            <div class="row">
                @component('admin.slots.form-col-left')
                    @include("shift-request.form", ["action"=>"edit"])
                @endcomponent

                @component('admin.slots.form-col-right')
                    @include("shift-request.status")
                @endcomponent
            </div>

        </div>
    </div>
@endsection