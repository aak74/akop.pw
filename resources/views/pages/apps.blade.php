@extends('layouts.default')

@section('content')
<h1>Приложения</h1>
<?//dd(app('request')->route())?>

@foreach ($items as $item)
	<a href="/apps/{{ $item['code'] }}"><h2>{{ $item['name'] }}</h2></a>
    <span>{!! $item['description'] !!}</span>
@endforeach

@stop
