@extends('layouts.app')

@section('title')
    Види проектів
@endsection

@section('body')
    @include('layouts.header')

    <div class="w-1/3 h-5/6 flex flex-row mx-auto my-6 rounded-3xl overflow-hidden border-2 border-black">
        <div class="h-full w-full bg-white p-4 flex flex-col gap-4 border-r-2 border-black">
            <div class="text-center w-full text-3xl">Види проектів</div>
            <div class="text-center w-full text-xl underline">
                <a href="{{ route('project-types.create') }}">Додати</a>
            </div>
            @if($projectTypes->count() == 0)
                <div class="text-center mt-6 text-lg italic">
                    Видів проектів не було знайдено.
                </div>
            @else
                <div class="flex flex-col gap-4 overflow-y-scroll p-2 border-2 border-gray-200 rounded-lg">
                    @foreach($projectTypes as $projectType)
                        <div class="border-2 border-gray-400 rounded-md w-full p-2 text-lg">
                            <div><b>Вид:</b> {{ $projectType->name }}</div>
                            <div><b>Кількість проектів:</b> {{ $projectType->projects_count }}</div>
                            <div class="mt-4 flex flex-row gap-4">
                                <form action="{{ route('project-types.destroy', $projectType) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" title="Видалити даний вид проекту">
                                        <img src="{{ asset('delete-icon.png') }}" alt="Видалити"
                                             class="w-10 h-10 rounded-lg p-1.5 bg-red-400 hover:bg-red-700">
                                    </button>
                                </form>
                                <a href="{{ route('project-types.edit', $projectType) }}"
                                   title="Перейти до форми редагування">
                                    <img src="{{ asset('edit-icon.png') }}" alt="Перейти до форми редагування"
                                         class="w-10 h-10 rounded-lg p-1.5 bg-yellow-400 hover:bg-yellow-500">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('load', () => {
            @error('delete')
            alert('{{ $message }}')
            @enderror
        });
    </script>
@endsection
