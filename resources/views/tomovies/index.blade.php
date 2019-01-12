<!doctype html>
<html>
<head>
    <title>Laravel</title>
</head>
<body>

@if (session('message'))
    <p>{{session('message')}}</p>
@endif
<h1>Request A Movie</h1>
{!! Form::open(['action' => 'ToMovieController@getFromMovieDb', 'method' => 'GET']) !!}
{{ Form::bsText('title') }}
{{ Form::bsSubmit('Submit', ['class' => 'btn btn-primary']) }}
{!! Form::close() !!}
<table>
    <tr>
        <th>Movies Owned</th>
    </tr>
    @foreach($movieTitles as $movieTitle)
    <tr>
        <td>{{ $movieTitle }}</td>
    </tr>
    @endforeach
</table>
</body>
</html>