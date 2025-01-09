<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Scheduler</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col">
                            <div class="-m-1.5 overflow-x-auto">
                                <div class="p-1.5 min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">
                                        <div class="header mb-5 flex flex-row justify-between align-center">
                                            <h2 class="font-inter font-bold text-2xl text-gray-800">{{ $project->name }}</h2>
                                            <button id="new-task-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold h-8 rounded font-inter px-2 text-sm" onclick="showTaskForm()">
                                                New Task
                                            </button>

                                            <form class="hidden" id="new-task-form" action="{{ route('task.store', ['id' => $project->id]) }}" method="POST">
                                                @csrf
                                                <x-text-input id="task-name" class="w-full h-8 font-inter text-sm" type="text" name="name" placeholder="Task Name" required />
                                                <div class="flex gap-1">
                                                    <button type="submit" id="new-task-button" class="bg-green-500 hover:bg-green-700 text-white h-8 rounded font-inter px-2 text-sm">
                                                        Add
                                                    </button>
                                                    <button type="button" id="new-task-button" class="bg-red-500 hover:bg-red-700 text-white h-8 rounded font-inter px-2 text-sm" onclick="showTaskForm()">
                                                        Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <table class="min-w-full divide-y divide-gray-200 border font-inter">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Task Name</th>
                                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Priority</th>
                                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($tasks as $key => $task)
                                                <tr class="hover:bg-gray-100" draggable="true" ondragstart="drag(event)" ondragover="allowDrop(event)" ondrop="drop(event, '{{ $project->id }}')" data-priority="{{ $task->priority }}">
                                                    <td id="task-col-{{ $key }}" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 ">{{ $task->name }}</td>
                                                    <td class="hidden w-0" id="edit-task-form-{{ $key }}">
                                                        <form class="flex gap-3 px-5 py-3 m-0" id="" action="{{ route('task.update', ['id' => $task->id, 'project_id' => $project->id]) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <x-text-input id="task-name-{{ $key }}" name="name" class=" max-w-[400px] h-8 font-inter text-sm" type="text" name="name" placeholder="Task Name" value="{{ $task->name }}" required />
                                                            <div class="flex">
                                                                <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                    </svg>

                                                                </button>

                                                                <button type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-500" onclick="showEditTaskForm('{{ $key }}')">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                    </svg>
                                                                </button>

                                                            </div>
                                                        </form>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 ">{{ $task->priority }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                        <button type="button" id="edit-task-button-{{ $key }}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-500 disabled:text-gray-400" onclick="showEditTaskForm('{{ $key }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                            </svg>
                                                        </button>

                                                        <form class="inline-flex" action="{{ route('task.delete', ['id' => $task->id, 'project_id' => $project->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" id="delete-task-button-{{ $key }}" class="items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-500 disabled:text-gray-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        @if(session('success'))
                                        <p class="font-inter text-sm mt-5 text-green-600" id="success-message">{{ session('success') }}</p>

                                        <script>
                                            window.addEventListener('DOMContentLoaded', function() {
                                                var successMessage = document.getElementById('success-message');

                                                setTimeout(function() {
                                                    successMessage.style.display = 'none';
                                                }, 3000);
                                            });
                                        </script>
                                        @endif

                                        @if(session('error'))
                                        <p class="font-inter text-sm mt-5 text-red-600" id="error-message">{{ session('error') }}</p>

                                        <script>
                                            window.addEventListener('DOMContentLoaded', function() {
                                                var errorMessage = document.getElementById('error-message');

                                                setTimeout(function() {
                                                    errorMessage.style.display = 'none';
                                                }, 3000);
                                            });
                                        </script>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>



</body>

@vite(['resources/js/task.js'])

</html>
