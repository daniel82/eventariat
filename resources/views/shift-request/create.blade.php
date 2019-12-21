@extends("master")

@section("content")
    <div class="form-inner">
        <h2>Antrag speichern</h2>
        <script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>
        <div id="ev-shift-request-app">
            {{  Form::open (['action' => ['ShiftRequestFrontendController@store' ], 'method' => 'POST' ]) }}
                <div class="row">
                    @component('admin.slots.form-col-left')
                        @include("shift-request.form", ["action"=>"create"])
                    @endcomponent

                    @component('admin.slots.form-col-right')
                        @include("admin.common.save-button")
                    @endcomponent
                </div>
            {{  Form::close() }}
        </div>
    </div>
@endsection