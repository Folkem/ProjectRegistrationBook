@extends('layouts.app')

@section('title')
    Проекти - Сторінка №{{ $projects->currentPage() }}
@endsection

@section('body')
    @include('layouts.header')

    <div class="w-2/3 h-5/6 flex flex-row mx-auto my-6 rounded-3xl overflow-hidden border-2 border-black">
        <div class="w-2/3 h-full bg-white p-4 flex flex-col gap-4 border-r-2 border-black">
            <div class="text-center w-full text-3xl">Проекти</div>
            @if($projects->count() == 0)
                <div class="text-center mt-6 text-lg italic">
                    Проектів не було знайдено.
                </div>
            @else
                <div class="flex flex-col gap-4 overflow-y-scroll p-2 border-2 border-gray-200 rounded-lg">
                    @foreach($projects as $project)
                        <div class="border-2 border-gray-400 rounded-md w-full p-2 text-lg">
                            <div><b>Студент:</b> {{ $project->student }}</div>
                            <div><b>Керівник:</b> {{ $project->supervisor }}</div>
                            <div><b>Група:</b> {{ $project->group }}</div>
                            <div><b>Тема:</b> {{ $project->theme }}</div>
                            <div><b>Тип:</b> {{ $project->projectType->name }}</div>
                            <div><b>Дата реєстрації:</b> {{ $project->created_at }}</div>
                            <div class="mt-4">
                                <form action="{{ route('projects.destroy', $project) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" title="Видалити даний проект">
                                        <img src="{{ asset('delete-icon.png') }}" alt="Видалити"
                                             class="w-10 h-10 rounded-lg p-1 bg-red-400 hover:bg-red-700">
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div>
                    {{ $projects->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
        <div class="w-1/3 h-full bg-white p-4 flex flex-col gap-4">
            <div class="text-center w-full text-3xl">Фільтри</div>
            <form action="{{ route('projects.index') }}" method="get"
                  class="flex flex-col gap-4">
                <div>
                    <label for="student" class="sr-only">Студент: </label>
                    <input type="text" name="student" id="student" placeholder="Студент"
                           class="border-2 border-gray-300 rounded-md p-2 w-full"
                           value="{{ request('student') }}">
                </div>
                <div>
                    <label for="supervisor" class="sr-only">Керівник: </label>
                    <input type="text" name="supervisor" id="supervisor" placeholder="Керівник"
                           class="border-2 border-gray-300 rounded-md p-2 w-full"
                           value="{{ request('supervisor') }}">
                </div>
                <div>
                    <label for="theme" class="sr-only">Тема: </label>
                    <input type="text" name="theme" id="theme" placeholder="Тема"
                           class="border-2 border-gray-300 rounded-md p-2 w-full"
                           value="{{ request('theme') }}">
                </div>
                <div>
                    <label for="group" class="sr-only">Група: </label>
                    <input type="text" name="group" id="group" placeholder="Група"
                           class="border-2 border-gray-300 rounded-md p-2 w-full"
                           value="{{ request('group') }}">
                </div>
                <div>
                    <label for="project_type_id" class="sr-only">Тип: </label>
                    <select name="project_type_id" id="project_type_id"
                            class="border-2 border-gray-300 rounded-md px-1 py-2 w-full">
                        <option selected></option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}"
                                {{ request('project_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="{{ route('projects.index', ['page' => $projects->currentPage()]) }}"
                       class="block text-center w-full bg-gray-400 hover:bg-gray-500 text-white py-2">
                        Скинути фільтри
                    </a>
                </div>
                <div>
                    <button class="w-full bg-green-400 hover:bg-green-500 text-white py-2"
                            type="submit">Пошук
                    </button>
                </div>
                <div>
                    <button type="submit" formaction="{{ route('projects.export') }}"
                            class="w-full bg-blue-400 hover:bg-blue-500 text-white py-2">
                        Експортувати
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
