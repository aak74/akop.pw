@extends('layouts.default')

@section('content')
<h1>Приложение: {{ $item->name }}</h1>
<p>{!! $item->description !!}</p>

<h2>Как установить</h2>
<div class="app__how-to-install">
	{!! $item->installation !!}
</div>
@stop
