@extends("master")

@section("content")
    <div class="form-inner">
        <h2>Antrag bearbeiten</h2>
        <script>ev_app_data = {!! json_encode($ev_app_data) !!}</script>
        <div id="ev-shift-request-app">
            @include("shift-request.ajax-alert")

            {{ Form::open (['action' => ['ShiftRequestController@update', $object->id ], 'method' => 'PATCH']) }}
                <div class="row">
                    @component('admin.slots.form-col-left')
                        @include("shift-request.status")
                        @include("shift-request.form", ["action"=>"edit"])
                    @endcomponent

                    @component('admin.slots.form-col-right')
                        @if ( !$object->status )
                            @include("admin.common.save-button")
                        @endif
                    @endcomponent
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection