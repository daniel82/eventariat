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
                <td class="text-right">
                    @include("admin.common.delete-button", ["controller" => "UserController", "object" => $item])
                </td>
            </tr>
        @endforeach
      </tbody>

    </table>
  @endif

@endsection