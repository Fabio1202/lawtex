<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Projects
        </h2>
    </x-slot>

    <div class="py-12 w-full px-2 sm:px-8">
        <div class="w-full px-8 py-8 bg-gray-200 dark:bg-gray-800 text-black dark:text-white rounded-md">
            <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                <h1 class="text-2xl font-bold">All your projects</h1>
                <div>
                    <a><button before="{{ _('New Project') }}" class="before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800"><i class="fa-regular fa-pen-to-square"></i></button></a>
                </div>
            </div>
            <div class="mt-5">
                @foreach($projects as $project)
                    <span class="flex-col gap-3 sm:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all hover:dark:bg-gray-700 {{ $loop->last ? "" : "border-b-2" }}">
                        <span class="flex content-around flex-col">
                            <h1 class="text-lg font-bold">{{ $project->name }}</h1>
{{--                                <h2 class="text-sm">Owner: {{ $project->user->name }}</h2>--}}
                        </span>
                        <span class="inline-block">
                            <button class="before:content-['Edit'] before:mr-2 bg-blue-500 rounded-md px-2 py-1 hover:bg-blue-400"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="before:content-['Delete'] before:mr-2 bg-red-500 rounded-md px-2 py-1 hover:bg-red-400"><i class="fa-regular fa-trash-can"></i></button>
                        </span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
