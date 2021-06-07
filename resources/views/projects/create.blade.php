@extends('layouts.app')

@section('title', 'Реєстрація')

@section('body')
    @include('layouts.header')

    <div class="flex w-full h-5/6">
        <div class="w-1/4 h-auto flex flex-col gap-4 m-auto rounded-3xl overflow-hidden p-4 border-black border-2">
            <div class="text-3xl text-center">Реєстрація</div>
            @if(session()->has('message'))
                <div class="w-full p-2 text-center text-lg rounded-xl bg-green-400">
                    {{ session('message') }}
                </div>
            @endif
            <form action="{{ route('projects.store') }}" method="post" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="reg-number" class="sr-only">Реєстраційний номер: </label>
                    <input type="number" name="reg-number" id="reg-number" placeholder="Реєстраційний номер"
                           value="{{ old('reg-number') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('reg-number')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="student" class="sr-only">Студент: </label>
                    <input type="text" name="student" id="student" placeholder="Студент" value="{{ old('student') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('student')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="supervisor" class="sr-only">Керівник: </label>
                    <input type="text" name="supervisor" id="supervisor" placeholder="Керівник"
                           value="{{ old('supervisor') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('supervisor')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="theme" class="sr-only">Тема: </label>
                    <input type="text" name="theme" id="theme" placeholder="Тема" value="{{ old('theme') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('theme')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="project-type" class="sr-only">Вид роботи: </label>
                    <input list="project-types" name="project-type" id="project-type"
                           placeholder="Вид роботи" value="{{ old('project-type') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    <datalist id="project-types">
                        @foreach($types as $type)
                            <option value="{{ $type->name }}"></option>
                        @endforeach
                    </datalist>
                    @error('project-type')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="group" class="sr-only">Група: </label>
                    <input type="text" name="group" id="group" placeholder="Група" value="{{ old('group') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('group')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 p-2 text-lg text-white">
                        Зареєструвати
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
