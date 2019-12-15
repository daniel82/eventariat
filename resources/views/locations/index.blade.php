@extends('master')


@section('content')
  <div class="text-right"><a href="{{ action("LocationController@create") }}" class="ev-add-btn "><span class="d-none">Neuer Lokalität</span><i class="fa fa-plus-circle" aria-hidden="true"></i>
</a></div>

  @if ( $items )
    <table class="table table-striped">
      <thead>
        <tr>
          <th></th>
          <th>Farbe</th>
          <th>Name</th>
          <th>Straße</th>
          <th>Festnetznummer</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($items as $key => $item )
            <tr>
                <td>
                  @include("admin.common.edit-button", ["resource" => "locations", "object" => $item])
                </td>
                <td><span class="ev-color-square" style="background:{{$item->color}};"></span></td>
                <td>{{$item->name}}</td>
                <td>{{$item->street}}</td>
                <td><a href="tel:{{$item->phone}}">{{$item->phone}}</a></td>
                <td class="text-right">
                    @include("admin.common.delete-button", ["controller" => "LocationController", "object" => $item])
                </td>
            </tr>
        @endforeach
      </tbody>

    </table>
  @endif

@endsection