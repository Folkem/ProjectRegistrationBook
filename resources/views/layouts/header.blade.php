<div class="static w-full py-4 px-8 flex flex-col bg-gray-300 text-xl">
    <div class="flex flex-row gap-8 justify-end">
        <a href="{{ route('project-types.index') }}">Види проектів</a>
        <a href="{{ route('groups.index') }}">Групи</a>
        <a href="{{ route('supervisors.index') }}">Керівники</a>
        <a href="{{ route('settings.index') }}">Налаштування</a>
        <a href="{{ route('logout') }}">Вийти</a>
    </div>
    <div class="flex flex-row gap-8 justify-end">
        <a href="{{ route('projects.create') }}">Реєстрація</a>
        <a href="{{ route('projects.upload') }}">Завантаження через excel-файл</a>
        <a href="{{ route('projects.index') }}">Журнал реєстрації</a>
    </div>
</div>
