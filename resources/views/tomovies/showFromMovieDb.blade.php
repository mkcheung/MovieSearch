<!DOCTYPE html>
<html>
<head>
    <title>{{ $page_title }}</title>
</head>
<body>
<h2>
    {{ $page_title }}
</h2>

    <form action="/tomovies/" method="POST">
        @csrf
        <table>
            <tr>
                <th>Owned</th><th>Title</th><th>Release Date</th><th>Overview</th>
            </tr>
            @foreach ($movies as $movie)
            <tr>
    @if($movie['owned'])

            <td>
                <input id="{{$movie['id']}}" type="checkbox" class="film" name="movie_owned_list[]" value="{{$movie['title']}}_{{$movie['release_date']}}" checked="checked" />
                <input id="{{$movie['id']}}_hidden" type='checkbox' style="opacity:0;" value="{{$movie['title']}}_{{$movie['release_date']}}" name="movie_not_owned_list[]" />
            </td>

        @else
            <td>
                <input id="{{$movie['id']}}" type="checkbox" class="film" name="movie_owned_list[]" value="{{$movie['title']}}_{{$movie['release_date']}}"  />
                <input id="{{$movie['id']}}_hidden" type='checkbox' style="opacity:0;" value="{{$movie['title']}}_{{$movie['release_date']}}" name="movie_not_owned_list[]" />
            </td>

    @endif
        <td>{{ $movie['title'] }}</td>
        <td>{{ $movie['release_date'] }}</td>
        <td>{{ $movie['overview'] }}</td>
    </tr>
    @endforeach
    </table>
    <input type="submit" />
    </form>
    </body>
    <br/>
    <div>
    <span>Total Results: {{$numMatches}}</span>
    </div>


<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

<script type="text/javascript">

$('.film').change(function() {
// console.log($(this).is(":checked"));
let id = ($(this).attr('id'))+'_hidden';
// console.log($(this).is(":checked"));
if(!$(this).is(":checked")){
$(this).attr('checked', false);
$('#'+($(this).attr('id'))+'_hidden').attr('checked', true);
}
else {
$(this).attr('checked', true);
$('#'+($(this).attr('id'))+'_hidden').attr('checked', false);
}
});



</script>
</html>