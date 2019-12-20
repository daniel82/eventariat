@extends("master")

@section("content")
    <div class="form-inner">
    <h2>Profil</h2>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Kontaktdaten</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="leave_days-tab" data-toggle="tab" href="#leave_days" role="tab" aria-controls="leave_days" aria-selected="false">Urlaub</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="shift_requests-tab" data-toggle="tab" href="#shift_requests" role="tab" aria-controls="shift_requests" aria-selected="false">Antr&auml;ge</a>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane py-3 fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            {{ Form::open (['action' => ['UserFrontendController@update', $object->id ], 'method' => 'PATCH']) }}
                <div class="row">
                    @component('admin.slots.form-col-left')
                        @include("users.account-form", ["action"=>"edit"])
                    @endcomponent

                    @component('admin.slots.form-col-right')
                        @include("admin.common.save-button")
                    @endcomponent
                </div>
            {{ Form::close() }}
        </div>
        <div class="tab-pane py-3 fade" id="leave_days" role="tabpanel" aria-labelledby="leave_days-tab">
            @include("users.partials.table-leave_days")
        </div>
        <div class="tab-pane py-3 fade" id="shift_requests" role="tabpanel" aria-labelledby="shift_requests-tab">
            @include("users.partials.table-shift_requests")
        </div>
    </div>




    </div>
@endsection

