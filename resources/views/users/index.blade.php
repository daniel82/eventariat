@extends('master')


@section('content')

  <div class="text-right"><a href="{{ action("UserController@create") }}" class="ev-add-btn "><span class="d-none">Neuer Mitarbeiter</span><i class="fa fa-plus-circle" aria-hidden="true"></i>
</a></div>

@include("users.partials.filters")

  @if ( $items )
    <table class="table table-striped ev-admin-table">
      <thead>
        <tr>
          <th class="ev-edit-col"></th>
          <th>Vorname</th>
          <th>Nachname</th>
          <th>Rolle</th>
          <th>Mobilnr</th>
          <th>E-Mail</th>
          <th>Geburtstag</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($items as $key => $item )
          <tr>
            <td class="ev-edit-col">
              @include("admin.common.edit-button", ["resource" => "users", "object" => $item])
            </td>
            <td>{{$item->first_name}}</td>
            <td>{{$item->last_name}}</td>
            <td class="hide-on-mobile">{{$item->role}}</td>
            <td>
              @if ( $item->mobile )
                <a href="tel:{{$item->mobile}}">{{$item->mobile}}</a>
              @else
                <span>---</span>
              @endif

            </td>
            <td>
              @if ( $item->email )
                <a href="mailto:{{$item->email}}">{{$item->email}}</a>
              @else
                <span>---</span>
              @endif
            </td>
            <td class="hide-on-mobile">{{ $item->birthdate }}</td>

            <td class="ev-action-col">
              @if ( !$item->deleted_at )
                @include("admin.common.delete-button", ["controller" => "UserController", "object" => $item])
                <a href="/admin/login-as/?user_id={{$item->id}}" class="btn btn-warning" title="Anmelden als {{ $item->first_name }} {{ $item->last_name }}"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
              @else
                @include("admin.common.restore-button", ["controller" => "UserController", "object" => $item])
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>

    </table>
  @endif

@endsection