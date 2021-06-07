@extends('layouts.app')

@section('title')
    Групи - Сторінка №{{ $groups->currentPage() }}
@endsection

@section('body')
    @include('layouts.header')

    <div class="w-2/3 h-5/6 flex flex-row mx-auto my-6 rounded-3xl overflow-hidden border-2 border-black">
        <div class="h-full w-full1 bg-white p-4 flex flex-col gap-4 border-r-2 border-black">
            <div class="text-center w-full text-3xl">Групи</div>
            @if($groups->count() == 0)
                <div class="text-center mt-6 text-lg italic">
                    Груп не було знайдено.
                </div>
            @else
                <div class="flex flex-col gap-4 overflow-y-scroll p-2 border-2 border-gray-200 rounded-lg">
                    @foreach($groups as $group)
                        <div class="border-2 border-gray-400 rounded-md w-full p-2 text-lg">
                            <div><b>Назва:</b> {{ $group->name }}</div>
                            <div class="mt-4 flex flex-row gap-4">
                                <form action="{{ route('groups.destroy', $group) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" title="Видалити даний проект">
                                        <img src="{{ asset('delete-icon.png') }}" alt="Видалити"
                                             class="w-10 h-10 rounded-lg p-1.5 bg-red-400 hover:bg-red-700">
                                    </button>
                                </form>
                                <a href="{{ route('groups.edit', $group) }}" title="Перейти до форми редагування">
                                    <img src="{{ asset('edit-icon.png') }}" alt="Перейти до форми редагування"
                                         class="w-10 h-10 rounded-lg p-1.5 bg-yellow-400 hover:bg-yellow-500">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div>
                    {{ $groups->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
