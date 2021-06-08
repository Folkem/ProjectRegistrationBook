@extends('layouts.app')

@section('title')
    Журнал реєстрації
@endsection

@section('body')
    @include('layouts.header')

    <div class="w-full flex h-5/6">
        <div class="w-full h-full flex flex-col mx-4 my-auto rounded-3xl overflow-hidden border-2 border-black">
            <!-- Фільтри -->
            <div class="w-full h-2/6 bg-white p-4 flex flex-col gap-4">
                <div class="text-center w-full text-3xl">Фільтри</div>
                <form action="{{ route('projects.index') }}" method="get"
                      class="flex flex-col gap-4">
                    <div class="flex flex-row flex-wrap gap-4">
                        <div>
                            <label for="student" class="sr-only">Студент: </label>
                            <input type="text" name="student" id="student" placeholder="Студент"
                                   class="border-2 border-gray-300 rounded-md p-2 w-full"
                                   value="{{ request('student') }}">
                        </div>
                        <div>
                            <label for="theme" class="sr-only">Тема: </label>
                            <input type="text" name="theme" id="theme" placeholder="Тема"
                                   class="border-2 border-gray-300 rounded-md p-2 w-full"
                                   value="{{ request('theme') }}">
                        </div>
                        <div>
                            <label for="supervisor_id" class="sr-only">Керівник: </label>
                            <select name="supervisor_id" id="supervisor_id"
                                    class="border-2 border-gray-300 rounded-md px-1 py-2 w-full">
                                <option selected value="null">&lt;Керівник&gt;</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}"
                                            {{ request('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="group_id" class="sr-only">Група: </label>
                            <select name="group_id" id="group_id"
                                    class="border-2 border-gray-300 rounded-md px-1 py-2 w-full">
                                <option selected value="null">&lt;Група&gt;</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                            {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="project_type_id" class="sr-only">Вид проекту: </label>
                            <select name="project_type_id" id="project_type_id"
                                    class="border-2 border-gray-300 rounded-md px-1 py-2 w-full">
                                <option selected value="null">&lt;Вид проекту&gt;</option>
                                @foreach($projectTypes as $projectType)
                                    <option value="{{ $projectType->id }}"
                                            {{ request('project_type_id') == $projectType->id ? 'selected' : '' }}>
                                        {{ $projectType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="year_from" class="sr-only">Зареєстровано від року: </label>
                            <input type="number" name="year_from" id="year_from"
                                   placeholder="Зареєстровано від якого року (включно)"
                                   class="border-2 border-gray-300 rounded-md p-2 w-full" min="1900" max="9999"
                                   value="{{ request('year_from') ?: now()->year }}">
                        </div>
                        <div>
                            <label for="year_to" class="sr-only">Зареєстровано до року: </label>
                            <input type="number" name="year_to" id="year_to"
                                   placeholder="Зареєстровано до якого року (включно)"
                                   class="border-2 border-gray-300 rounded-md p-2 w-full" min="1900" max="9999"
                                   value="{{ request('year_to') ?: now()->year }}">
                        </div>
                    </div>
                    <div class="flex flex-row gap-4 mx-auto">
                        <div>
                            <a href="{{ route('projects.index') }}"
                               class="block text-center w-full bg-gray-500 hover:bg-gray-700 text-white py-2 px-8 font-bold">
                                Скинути фільтри
                            </a>
                        </div>
                        <div>
                            <button class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-8 font-bold"
                                    type="submit">Пошук
                            </button>
                        </div>
                        <div>
                            <button type="submit" formaction="{{ route('projects.export') }}"
                                    class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-8 font-bold">
                                Експортувати
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Таблиця -->
            <div class="w-full h-5/6 bg-white p-4 flex flex-col gap-4 border-t-2 border-black">
                <div class="text-center w-full text-3xl">Журнал реєстрації</div>
                @if($projects->count() == 0)
                    <div class="text-center mt-6 text-lg italic">
                        Проектів не було знайдено.
                    </div>
                @else
                    <div class="flex flex-col overflow-y-scroll h-2/3 gap-4 overflow-y-scroll p-2 border-2 border-gray-200 rounded-lg">
                        <table class="w-max" id="table">
                            <tbody>
                            <tr class="font-bold">
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Реєстраційний номер
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Студент
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Група
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Керівник
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Тема
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Вид проекту
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Дата реєстрації
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Дата захисту
                                    <img src="{{ asset('sort-solid.svg') }}" alt="Сортування" data-sort="defense"
                                         class="h-4 inline" onclick="sortByDefense(SORT.DOWN)">
                                    <img src="{{ asset('sort-down-solid.svg') }}" alt="Сортування" data-sort="defense"
                                         class="h-4 inline hidden" onclick="sortByDefense(SORT.UP)">
                                    <img src="{{ asset('sort-up-solid.svg') }}" alt="Сортування" data-sort="defense"
                                         class="h-4 inline hidden" onclick="sortByDefense(SORT.NONE)">
                                </th>
                                <th class="py-1 px-4 border-r-2 border-black">
                                    Оцінка
                                </th>
                                <th class="py-1 px-4">
                                    Кнопки
                                </th>
                            </tr>
                            @foreach($projects as $project)
                                <tr data-defended_at="{{ $project->defended_at }}" data-id="{{ $project->id }}"
                                    class="border-t-2 border-black">
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->registration_number }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->student }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->group->name }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->supervisor->name }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->theme }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->projectType->name }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->registered_at }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->defended_at }}</td>
                                    <td class="text-center px-4 border-r-2 border-black"> {{ $project->grade }}</td>
                                    <td class="flex flex-row gap-4 px-4">
                                        <form action="{{ route('projects.destroy', $project) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" title="Видалити даний проект">
                                                <img src="{{ asset('delete-icon.png') }}" alt="Видалити"
                                                     class="w-10 h-10 rounded-lg p-1.5 bg-red-400 hover:bg-red-700">
                                            </button>
                                        </form>
                                        <a href="{{ route('projects.edit', $project) }}"
                                           title="Перейти до форми редагування">
                                            <img src="{{ asset('edit-icon.png') }}"
                                                 alt="Перейти до форми редагування"
                                                 class="w-10 h-10 rounded-lg p-1.5 bg-yellow-400 hover:bg-yellow-500">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const SORT = {
            NONE: 1,
            DOWN: 2,
            UP: 3,
        };

        const table = document.querySelector('#table');
        const defenseSortButtons = document.querySelectorAll('[data-sort="defense"]');
        const projects = Array.from(document.querySelectorAll('[data-defended_at]'));

        function sortByDefense(side) {
            switch (side) {
                case SORT.NONE:
                    projects.sort((first, second) => {
                        const firstDefendedAt = first.getAttribute('data-id');
                        const secondDefendedAt = second.getAttribute('data-id');

                        if (firstDefendedAt > secondDefendedAt) {
                            return 1;
                        } else if (firstDefendedAt < secondDefendedAt) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                    defenseSortButtons.forEach(b => b.classList.toggle('hidden', true));
                    defenseSortButtons.item(0).classList.toggle('hidden', false);
                    break;
                case SORT.DOWN:
                    projects.sort((first, second) => {
                        const firstDefendedAt = first.getAttribute('data-defended_at');
                        const secondDefendedAt = second.getAttribute('data-defended_at');

                        if (firstDefendedAt > secondDefendedAt) {
                            return 1;
                        } else if (firstDefendedAt < secondDefendedAt) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                    defenseSortButtons.forEach(b => b.classList.toggle('hidden', true));
                    defenseSortButtons.item(1).classList.toggle('hidden', false);
                    break;
                case SORT.UP:
                    projects.sort((second, first) => {
                        const firstDefendedAt = first.getAttribute('data-defended_at');
                        const secondDefendedAt = second.getAttribute('data-defended_at');

                        if (firstDefendedAt > secondDefendedAt) {
                            return 1;
                        } else if (firstDefendedAt < secondDefendedAt) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                    defenseSortButtons.forEach(b => b.classList.toggle('hidden', true));
                    defenseSortButtons.item(2).classList.toggle('hidden', false);
                    break;
            }

            projects.forEach(project => project.remove());
            table.append(...projects);
        }
    </script>
@endsection
