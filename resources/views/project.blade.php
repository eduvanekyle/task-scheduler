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
                <div class="bg-white  shadow-sm sm:rounded-lg h-[200px]">
                    <div class="p-6">
                        <div class="flex flex-col">
                            <div class="-m-1.5">
                                <div class="p-1.5 min-w-full inline-block align-middle">
                                    <div class="">
                                        <div class="header mb-5 justify-between align-center">
                                            <h2 class="font-inter font-bold text-2xl text-gray-800 mb-5">Projects</h2>

                                            <div class="flex gap-2">
                                                <x-dropdown align="left" width="48">
                                                    <x-slot name="trigger">
                                                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-inter font-medium rounded-md text-gray-600 bg-white hover:text-gray-700 transition ease-in-out duration-150">
                                                            <div>Select a Project</div>

                                                            <div class="ms-1">
                                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </x-slot>

                                                    <x-slot name="content">
                                                        @foreach($projects as $key => $project)
                                                        <x-dropdown-link :href="route('profile.edit')" class="font-inter font-medium text-sm text-gray-600 overflow-hidden">
                                                            {{ $project->name }}
                                                        </x-dropdown-link>
                                                        @endforeach
                                                    </x-slot>
                                                </x-dropdown>

                                                <form class="flex gap-2" action="{{ route('project.store') }}" method="POST">
                                                    @csrf

                                                    <x-text-input id="project-name" class="w-50 h-9 font-inter font-medium text-sm" type="text" name="name" placeholder="New Project" required />

                                                    <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                    </button>
                                                </form>

                                            </div>

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



    </div>



</body>

@vite(['resources/js/task.js'])

</html>