@component('mail::message')

# Neuer Antrag

<table>
  <tr>
    <td style="width: 140px">Wer:</td>
    <td>{{$shiftRequest->user->first_name}} {{$shiftRequest->user->last_name}}</td>
  </tr>

  <tr>
    <td style="width: 140px">Was:</td>
    <td>{{ $shiftRequest->getTypeHumanReadable() }}</td>
  </tr>


  <tr>
    <td style="width: 140px">Von:</td>
    <td>{{ formatDate($shiftRequest->date_from, "d.m.Y") }}</td>
  </tr>


  <tr>
    <td style="width: 140px">Bis:</td>
    <td>{{ formatDate($shiftRequest->date_to, "d.m.Y") }}</td>
  </tr>

  @if( $shiftRequest->note )
    <tr>
      <td style="width: 140px">Nachricht:</td>
      <td>{{$shiftRequest->note}}</td>
    </tr>
  @endif


  @component('mail::button', ['url' => config("app.url")."/admin/shift-requests/".$shiftRequest->id."/edit" ])
    Bearbeiten
  @endcomponent



</table>

@endcomponent