@extends('layouts.app')

@section('title')
    Проекти - Сторінка №{{ $projects->currentPage() }}
@endsection

@section('body')
    @include('layouts.header')

    <div class="w-2/3 h-5/6 flex flex-row mx-auto my-6 rounded-3xl overflow-hidden border-2 border-black">
        <div class="w-2/3 h-full bg-white p-4 flex flex-col gap-4 border-r-2 border-black">
            <div class="text-center w-full text-3xl">Проекти</div>
            <div class="flex flex-col gap-4 overflow-y-scroll p-2 border-2 border-gray-200 rounded-lg">
                @foreach($projects as $project)
                    <div class="border-2 border-gray-400 rounded-md w-full p-2 text-lg">
                        <div><b>Студент:</b> {{ $project->student }}</div>
                        <div><b>Керівник:</b> {{ $project->supervisor }}</div>
                        <div><b>Група:</b> {{ $project->group }}</div>
                        <div><b>Тема:</b> {{ $project->theme }}</div>
                        <div><b>Дата реєстрації:</b> {{ $project->created_at }}</div>
                    </div>
                @endforeach
            </div>
            <div>
                {{ $projects->onEachSide(1)->links() }}
            </div>
        </div>
        <div class="w-1/3 h-full bg-white p-4">
            <div class="text-center w-full text-3xl">Фільтри</div>
        </div>
    </div>
@endsection
