@extends('master')


@section('content')

  <div class="text-right"><a href="{{ action("UserController@create") }}" class="ev-add-btn "><span class="d-none">Neuer Mitarbeiter</span><i class="fa fa-plus-circle" aria-hidden="true"></i>
</a></div>

  @if ( $items )
    <table class="table table-striped">
      <thead>
        <tr>
          <th></th>
          <th>Vorname</th>
          <th>Nachname</th>
          <th>Mobilnr</th>
          <th>E-Mail</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($items as $key => $item )
          <tr>
            <td>
                @include("admin.common.edit-button", ["resource" => "users", "object" => $item])
            </td>
            <td>{{$item->first_name}}</td>
            <td>{{$item->last_name}}</td>
            <td><a href="tel:{{$item->mobile}}">{{$item->mobile}}</a></td>
            <td>{{$item->email}}</td>
            <td class="text-right" style="width: 200px">
                @include("admin.common.delete-button", ["controller" => "UserController", "object" => $item])
                <a href="/admin/login-as/?user_id={{$item->id}}" class="btn btn-warning" title="Anmelden als {{ $item->first_name }} {{ $item->last_name }}"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
            </td>
          </tr>
        @endforeach
      </tbody>

    </table>
  @endif

@endsection