<!DOCTYPE html>
<html>
<head>
    <title>PPATH QR Scanner</title>
</head>
<body>

  <h1>PPATH QR Scanner</h1>

<h3>Select Attendance Table</h3>

<select>

@foreach($activities as $activity)

<option value="{{ $activity->id }}">

{{ $activity->title }}

</option>

@endforeach

</select>
</body>
</html>
