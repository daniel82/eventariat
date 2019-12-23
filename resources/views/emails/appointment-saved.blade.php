@component('mail::message')

# Neuer Termin


<table>
  <tr>
    <td style="width: 140px">Wer:</td>
    <td>{{$user->first_name}} {{$user->last_name}}</td>
  </tr>


  <tr>
    <td style="width: 140px">Was:</td>
    <td>{{ $appointment->getTypeHumanReadable() }}</td>
  </tr>

  @if (is_object($appointment->location))
  <tr>
    <td style="width: 140px">Wo:</td>
    <td>{{ $appointment->location->name }}</td>
  </tr>
  @endif

  <tr>
    <td style="width: 140px">Von:</td>
    <td>{{ formatDate($appointment->date_from, "d.m.Y @H:i") }}</td>
  </tr>


  <tr>
    <td style="width: 140px">Bis:</td>
    <td>{{ formatDate($appointment->date_to, "d.m.Y @H:i") }}</td>
  </tr>

  @if( $appointment->note )
    <tr>
      <td style="width: 140px">Hinweis:</td>
      <td>{{$appointment->note}}</td>
    </tr>
  @endif


</table>

@endcomponent