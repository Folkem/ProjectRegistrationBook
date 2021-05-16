@extends('layouts.app')

@section('title', 'Завантаження через excel-файл')

@section('body')
    @include('layouts.header')

    <div class="flex w-full h-5/6">
        <div class="w-auto h-auto flex flex-col gap-4 m-auto rounded-3xl overflow-hidden p-4 border-black border-2">
            <div class="text-3xl text-center">Завантаження через файл</div>
            @csrf
            <input type="file" name="excel-file" id="excel-file" class="mx-auto"
                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
            <button class="w-full bg-green-400 hover:bg-green-500 p-2 text-lg"
                    onclick="uploadFileForPreview()" id="upload-file-button">
                Завантажити файл для передпоказу
            </button>
            <div class="p-4 mx-auto">
                <label for="project-type" class="text-lg font-bold p-2">Тип проекту: </label>
                <select name="project-type" id="project-type" class="p-2 bg-gray-50 hover:bg-gray-100 text-lg">
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="p-2 bg-yellow-400 hover:bg-yellow-500 hidden" id="toggle-all"
                    onclick="toggleAll()" data-state="enable-all">
                Обрати усі
            </button>
            <div id="preview-projects-container" class="border-2 border-black w-full hidden h-96">
                <div id="preview-projects-list" class="h-5/6 overflow-y-scroll bg-gray-400 flex flex-col gap-1"></div>
                <button class="h-1/6 w-full bg-blue-400 hover:bg-blue-500"
                        onclick="uploadDataForUpload()">
                    Зареєструвати обрані
                </button>
            </div>
        </div>
    </div>

    <script>
        const excelFileInput = document.querySelector('#excel-file');
        const previewProjectsContainer = document.querySelector('#preview-projects-container');
        const previewProjectsList = document.querySelector('#preview-projects-list');
        const checkAllButton = document.querySelector('#toggle-all');

        function uploadFileForPreview() {
            if (excelFileInput.files[0] === undefined) {
                alert('Завантажити можливо лише xlsx-файл!');
                return;
            }

            fetch('{{ route('projects.upload.preview') }}', {
                method: 'post',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Request-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('input[name="_token"]').value,
                },
                body: (() => {
                    const formData = new FormData();

                    formData.append('excel-file', excelFileInput.files[0]);

                    return formData;
                })(),
            })
                .then(response => response.json())
                .then(response => {
                    if (response['status'] === 'failure') {
                        alert(response['message']);
                        return;
                    }

                    checkAllButton.classList.remove('hidden');
                    previewProjectsContainer.classList.remove('hidden');
                    previewProjectsList.innerHTML = '';

                    for (const previewProjectObject of response['data']) {
                        const previewProjectElement = document.createElement('div');
                        previewProjectElement.className = 'p-4 w-full bg-gray-100 hover:bg-gray-200 flex flex-row';

                        const previewProjectCheckboxContainer = document.createElement('div');
                        previewProjectCheckboxContainer.className = 'w-1/12 flex';

                        const previewProjectCheckbox = document.createElement('input');
                        previewProjectCheckbox.name = 'projects[]';
                        previewProjectCheckbox.type = 'checkbox';
                        previewProjectCheckbox.className = 'w-6 h-6 m-auto cursor-pointer';

                        previewProjectCheckboxContainer.append(previewProjectCheckbox);

                        const previewProjectText = document.createElement('div');
                        previewProjectText.className = 'w-11/12';

                        const previewProjectTextStudentTitle = document.createElement('span');
                        previewProjectTextStudentTitle.innerHTML = '<b>Студент: </b>';
                        const previewProjectTextStudent = document.createElement('span');
                        previewProjectTextStudent.innerHTML = previewProjectObject['student'];
                        previewProjectTextStudent.setAttribute('aria-role', 'student');
                        const previewProjectTextGroupTitle = document.createElement('span');
                        previewProjectTextGroupTitle.innerHTML = '<b>Група: </b>';
                        const previewProjectTextGroup = document.createElement('span');
                        previewProjectTextGroup.innerHTML = previewProjectObject['group'];
                        previewProjectTextGroup.setAttribute('aria-role', 'group');
                        const previewProjectTextSupervisorTitle = document.createElement('span');
                        previewProjectTextSupervisorTitle.innerHTML = '<b>Керівник: </b>';
                        const previewProjectTextSupervisor = document.createElement('span');
                        previewProjectTextSupervisor.innerHTML = previewProjectObject['supervisor'];
                        previewProjectTextSupervisor.setAttribute('aria-role', 'supervisor');
                        const previewProjectTextThemeTitle = document.createElement('span');
                        previewProjectTextThemeTitle.innerHTML = '<b>Тема: </b>';
                        const previewProjectTextTheme = document.createElement('span');
                        previewProjectTextTheme.innerHTML = previewProjectObject['theme'];
                        previewProjectTextTheme.setAttribute('aria-role', 'theme');

                        previewProjectText.append(previewProjectTextStudentTitle);
                        previewProjectText.append(previewProjectTextStudent);
                        previewProjectText.append(document.createElement('br'));
                        previewProjectText.append(previewProjectTextGroupTitle);
                        previewProjectText.append(previewProjectTextGroup);
                        previewProjectText.append(document.createElement('br'));
                        previewProjectText.append(previewProjectTextSupervisorTitle);
                        previewProjectText.append(previewProjectTextSupervisor);
                        previewProjectText.append(document.createElement('br'));
                        previewProjectText.append(previewProjectTextThemeTitle);
                        previewProjectText.append(previewProjectTextTheme);

                        previewProjectElement.append(previewProjectCheckboxContainer, previewProjectText);

                        previewProjectsList.append(previewProjectElement);
                    }
                })
                .catch(e => {
                    console.error(e);
                    alert('Виникла помилка. Зверніться до розробника');
                });
        }

        function uploadDataForUpload() {
            const projectForUploadInputs = Array.from(document.querySelectorAll('input[type=checkbox][name=projects\\[\\]]'));
            const checkedProjectForUploadInputs = projectForUploadInputs.filter(value => value.checked);

            const projectsForUpload = checkedProjectForUploadInputs.map(input => {
                const children = Array.from(input.parentElement.parentElement.children.item(1).children);

                return {
                    student: children.find(element =>
                        element.getAttribute('aria-role') === 'student').innerHTML.trim(),
                    group: children.find(element =>
                        element.getAttribute('aria-role') === 'group').innerHTML.trim(),
                    supervisor: children.find(element =>
                        element.getAttribute('aria-role') === 'supervisor').innerHTML.trim(),
                    theme: children.find(element =>
                        element.getAttribute('aria-role') === 'theme').innerHTML.trim(),
                };
            });

            console.log(projectsForUpload);

            fetch('{{ route('projects.upload.store') }}', {
                method: 'post',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Request-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('input[name="_token"]').value,
                },
                body: (() => {
                    const formData = new FormData();

                    formData.append('project-type', document.querySelector('#project-type').value);
                    formData.append('projects', JSON.stringify(projectsForUpload));

                    return formData;
                })(),
            })
                .then(response => response.json())
                .then(response => alert(response['message']))
                .catch(e => {
                    console.error(e);
                    alert('Виникла помилка під час відправки обраних проектів');
                })
        }

        function toggleAll() {
            const projectForUploadInputs = Array.from(document.querySelectorAll('input[type=checkbox][name=projects\\[\\]]'));

            const state = checkAllButton.getAttribute('data-state');

            if (state === 'disable-all') {
                checkAllButton.setAttribute('data-state', 'enable-all');
                checkAllButton.innerHTML = 'Обрати усі';

                projectForUploadInputs.forEach(input => {
                    input.checked = false;
                });
            } else {
                checkAllButton.setAttribute('data-state', 'disable-all');
                checkAllButton.innerHTML = 'Зняти усі галочки';

                projectForUploadInputs.forEach(input => {
                    input.checked = true;
                });
            }
        }
    </script>
@endsection
