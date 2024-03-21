<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Projects
        </h2>
    </x-slot>
    <span x-data="{ showDelete: false, item: null, show(selItem) {this.item = selItem; this.showDelete = true}}">

        <div class="fixed w-screen h-screen top-0 left-0 flex justify-center items-center text-black dark:text-white" x-show.important="showDelete" x-cloak>
            <span class="bg-gray-900 opacity-40 absolute w-full h-full top-0 left-0" @click="showDelete = false;"></span>
            <div class="justify-around sm:justify-start w-full sm:w-auto h-full sm:h-auto rounded-md bg-gray-100 shadow-lg dark:bg-gray-700 p-10 flex items-center flex-col gap-1 z-10">
                <span class="flex flex-col items-center ">
                    <span class="text-5xl text-red-500 -z-10"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <h1 class=" text-center text-4xl font-bold">Are you sure?</h1>
                    <h1 class="text-center text-md ">{{ trans('Are you sure that you want to delete the project') }} <br>"<a x-text="item?.name ?? ''"></a>"</h1>
                </span>
                <form class="mt-7 flex flex-col gap-4 sm:flex-row justify-between w-full" method="POST" x-bind:action="'/projects/' + item?.id ?? ''">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDelete = false" before="{{ trans('Cancel') }}"
                            class="before:content-[attr(before)] bg-white dark:bg-gray-600 rounded-md px-5 py-2 hover:bg-gray-200 hover:dark:bg-gray-500 w-full"></button>
                    <button type="submit" before="{{ trans('Delete') }}"
                            class="before:content-[attr(before)] text-white bg-red-500 rounded-md px-5 py-2 hover:bg-red-400 w-full"></button>
                </form>
            </div>
        </div>

        <div class="py-12 w-full px-2 sm:px-8">
            <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                    <h1 class="text-2xl font-bold">All your projects</h1>
                    <div>
                        <a>
                            <button before="{{ trans('New Project') }}"
                                    class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                                <i class="fa-regular fa-pen-to-square"></i></button>
                        </a>
                    </div>
                </div>
                <div class="mt-5">
                    @foreach($projects as $project)
                        <span
                            class="flex-col gap-3 sm:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all hover:bg-gray-100 hover:dark:bg-gray-700 {{ $loop->last ? "" : "border-b-2" }}">
                        <span class="flex content-around flex-col">
                            <h1 class="text-lg font-bold">{{ $project->name }}</h1>
{{--                                <h2 class="text-sm">Owner: {{ $project->user->name }}</h2>--}}
                        </span>
                        <span class="inline-block">
                            <button
                                class="text-white before:content-['Edit'] before:mr-2 bg-blue-500 rounded-md px-2 py-1 hover:bg-blue-400"><i
                                    class="fa-regular fa-pen-to-square"></i></button>
                            <button @click="show({{ $project }})"
                                    class="text-white before:content-['Delete'] before:mr-2 bg-red-500 rounded-md px-2 py-1 hover:bg-red-400"><i
                                    class="fa-regular fa-trash-can"></i></button>
                        </span>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </span>
</x-app-layout>
