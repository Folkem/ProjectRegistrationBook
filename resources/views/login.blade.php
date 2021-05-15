@extends('layouts.app')

@section('title', 'Авторизація')

@section('body')
    <div class="flex w-full h-full">
        <div class="bg-white m-auto rounded-2xl w-3/12 h-auto p-8 flex flex-col gap-4">
            <div class="text-2xl font-bold text-center">
                Авторизація
            </div>
            @error('message')
            <div class="font-bold text-center bg-red-400 text-white rounded-lg p-2">
                {{ $message }}
            </div>
            @enderror
            <div class="mt-2">
                <form action="{{ route('login') }}" method="post" autocomplete="on" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label for="name" class="sr-only">Ваше ім'я: </label>
                        <input type="text" name="name" id="name" required placeholder="Ім'я"
                               class="w-full border-gray-300 border-2 bg-gray-100 p-3 rounded-lg">
                        @error('name')
                        <div class="text-red-600 px-4 mt-2 break-words">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="sr-only">Ваш пароль: </label>
                        <input type="password" name="password" id="password" required placeholder="Пароль"
                               class="w-full border-gray-300 border-2 bg-gray-100 p-3 rounded-lg">
                        @error('password')
                        <div class="text-red-600 px-4 mt-2 break-words">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full bg-green-400 hover:bg-green-500 py-2 text-lg">Увійти
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
