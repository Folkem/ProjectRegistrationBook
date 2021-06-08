@extends('layouts.app')

@section('title', 'Додавання керівника')

@section('body')
    @include('layouts.header')

    <div class="flex w-full h-5/6">
        <div class="w-1/4 h-auto flex flex-col gap-4 m-auto rounded-3xl overflow-hidden p-4 border-black border-2">
            <div class="text-3xl text-center">Додавання керівника</div>
            @if(session()->has('message'))
                <div class="w-full p-2 text-center text-lg rounded-xl bg-green-400">
                    {{ session('message') }}
                </div>
            @endif
            <form action="{{ route('supervisors.store') }}" method="post" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="name" class="sr-only">П.І.Б.: </label>
                    <input type="text" name="name" id="name" placeholder="П.І.Б." value="{{ old('name') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('name')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 p-2 text-lg text-white">
                        Додати
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
