@extends('layouts.guest')

@section('guest')
    <div class="grid grid-cols-4 gap-4 mx-10">
        <div class=" col-span-2 col-start-2 mt-6">
            <form action="{{ route('login.store') }}" method="POST"
                class="w-[100] text-center my-4 p-2 rounded-lg bg-gray-200 ">
                @csrf

                <h4 class="text-2xl p-2 text-red-400"> Login </h4>

                <div class="mb-4 ">
                    <input type="text" name="email" placeholder="email">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-4 ">
                    <input type="password" name="password" placeholder="password">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
                <input type="submit" value="Register" class="px-2 py-1 rounded bg-blue-300 w-[100px]">
            </form>
        </div>
    </div>
@endsection
