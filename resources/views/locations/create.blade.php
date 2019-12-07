@extends("master")

@section("content")
    <div class="form-inner">
    <h2>Lokalität anlegen</h2>

    {{ Form::open (['action' => ['LocationController@store' ], 'method' => 'POST' ]) }}

        <div class="row">
            @component('admin.slots.form-col-left')
                @include("locations.form")
            @endcomponent

            @component('admin.slots.form-col-right')
                @include("admin.common.save-button")
            @endcomponent
        </div>
    {{ Form::close() }}

    </div>
@endsection