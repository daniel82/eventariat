@extends('master')


@section('content')

  @if (  $items->count() )
    <table class="table table-striped">
      <thead>
        <tr>
          <th></th>
          <th>Status</th>
          <th>Mitarbeiter</th>
          <th>Type</th>
          <th>Datum</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($items as $key => $item )
          <tr>
            <td>
                @if ( $user->isAdmin() or !$user->isAdmin() && $item->status == 0 )
                  @include("admin.common.edit-button", ["resource" => "shift-requests", "object" => $item])
                @else
                  @include("admin.common.show-button", ["resource" => "shift-requests", "object" => $item])
                @endif
            </td>
            <td><span class="ev-shift-request__status status-{{ $item->status }}"><i class="fa fa-circle" aria-hidden="true"></i></span></td>
            <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
            <td>{{ $item->type_hr}}</td>
            <td>{{ formatDate($item->date_from, "d.m.Y")  }} - {{ formatDate($item->date_to, "d.m.Y") }}</td>
            <td class="text-right">
              @if ($item->status == 0)
                @include("admin.common.delete-button", ["controller" => "ShiftRequestController", "object" => $item])
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>

    </table>
  @else
    Noch keine Antraege vorhanden.
  @endif

@endsection