@extends('layouts.test')

@section('test')
    <section>
        <h1>Tests Index </h1>
        <ul>
            @foreach ($users as $user)
                <li >{{ $user->name }} <a href="{{route('tests.show',$user->id)}}">More</a></li>
            @endforeach
        </ul>
    </section>
@endsection
