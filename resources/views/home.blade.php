@extends('master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card mb-5">
                <div class="card-header">Neueste Antr&auml;ge</div>
                <div class="card-body">
                    @if ( $shift_requests->count() )
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-30">Mitarbeiter</th>
                                <th class="w-30">Periode</th>
                                <th class="w-30">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $shift_requests as $sr )
                                <tr>
                                    <td>{{ $sr->user->first_name}} {{$sr->user->last_name}}</td>
                                    <td>{{formatDate($sr->date_from, "d.m.Y")}} - {{formatDate($sr->date_to, "d.m.Y")}}</td>
                                    <td>{{$sr->type_hr}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <p>Keine offenen Antr&auml;ge</p>
                    @endif
                </div>
            </div>


            <div class="card mb-5">
                <div class="card-header">Kommende Urlaube</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-30">Mitarbeiter</th>
                                <th class="w-30">Von</th>
                                <th class="w-30">Bis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $leave_days as $ld )
                                <tr>
                                    <td>{{ $ld->user->first_name}} {{$ld->user->last_name}}</td>
                                    <td>{{formatDate($ld->date_from, "d.m.Y")}}</td>
                                    <td>{{formatDate($ld->date_to, "d.m.Y")}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="card mb-5">
                <div class="card-header">Kommende Geburtstage</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-30">Mitarbeiter</th>
                                <th class="w-30">Datum</th>
                                <th class="w-30">Alter</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $birthday_kids as $bk )
                                <tr>
                                    <td>{{ $bk->first_name}} {{$bk->last_name}}</td>
                                    <td>{{formatDate($bk->birthdate, "d.m.Y")}}</td>
                                    <td>{{$bk->age}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
