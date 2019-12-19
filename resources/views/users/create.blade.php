@extends("master")

@section("content")
    <div class="form-inner">
    <h2>Mitarbeiter anlegen</h2>

    {{ Form::open (['action' => ['UserController@store' ], 'method' => 'POST' ]) }}

        <div class="row">
            @component('admin.slots.form-col-left')
                @include("users.form", ["action"=>"create"])
            @endcomponent

            @component('admin.slots.form-col-right')
                @include("admin.common.save-button")
            @endcomponent
        </div>
    {{ Form::close() }}

    </div>
@endsection