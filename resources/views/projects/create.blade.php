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
                    <label for="registration_number" class="sr-only">Реєстраційний номер: </label>
                    <input type="number" name="registration_number" id="registration_number"
                           placeholder="Реєстраційний номер" min="1" max="{{ PHP_INT_MAX }}"
                           value="{{ old('registration_number') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('registration_number')
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
                    <label for="supervisor" class="sr-only">Вид роботи: </label>
                    <input list="supervisors" name="supervisor" id="supervisor"
                           placeholder="Науковий керівник" value="{{ old('supervisor') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    <datalist id="supervisors">
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->name }}"></option>
                        @endforeach
                    </datalist>
                    @error('supervisor')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="project_type" class="sr-only">Вид роботи: </label>
                    <input list="project_types" name="project_type" id="project_type"
                           placeholder="Вид роботи" value="{{ old('project_type') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    <datalist id="project_types">
                        @foreach($types as $type)
                            <option value="{{ $type->name }}"></option>
                        @endforeach
                    </datalist>
                    @error('project_type')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="group" class="sr-only">Вид роботи: </label>
                    <input list="groups" name="group" id="group"
                           placeholder="Група" value="{{ old('group') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    <datalist id="groups">
                        @foreach($groups as $group)
                            <option value="{{ $group->name }}"></option>
                        @endforeach
                    </datalist>
                    @error('group')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="registered_at" class="sr-only">Дата реєстрації: </label>
                    <input type="date" name="registered_at" id="registered_at" placeholder="Дата реєстрації"
                           value="{{ old('registered_at') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('registered_at')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="defended_at" class="sr-only">Дата захисту: </label>
                    <input type="date" name="defended_at" id="defended_at" placeholder="Дата захисту"
                           value="{{ old('defended_at') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    @error('defended_at')
                    <div class="text-red-700 px-4">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label for="grade" class="sr-only">Оцінка: </label>
                    <input list="grades" name="grade" id="grade" min="60" max="100"
                           placeholder="Оцінка" value="{{ old('grade') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-md">
                    <datalist id="grades">
                        @for($grade = 60; $grade <= 100; $grade++)
                            <option value="{{ $grade }}"></option>
                        @endfor
                    </datalist>
                    @error('grade')
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
