@component('mail::message')

# Neues Konto


<table>
  <tr>
    <td style="width: 140px">Wer:</td>
    <td>{{$user->first_name}} {{$user->last_name}}</td>
  </tr>


  <tr>
    <td style="width: 140px">Benutzer:</td>
    <td>{{ $user->email }}</td>
  </tr>

  @if (is_object($appointment->location))
  <tr>
    <td style="width: 140px">Wo:</td>
    <td>{{ $appointment->location->name }}</td>
  </tr>
  @endif




</table>

@endcomponent