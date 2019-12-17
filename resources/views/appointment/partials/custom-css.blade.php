<style type="text/css">
  @foreach($locations as $location )
    .location-{{ $location->id }}
    {
      color: {{ $location->color }};
    }
  @endforeach
</style>