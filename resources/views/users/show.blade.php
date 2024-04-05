<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('users.index') }}">
            <button
                class="hover:opacity-60 transition-all w-full xs:w-auto px-10 h-full rounded-md text-black dark:text-white">
                <i class="fa-solid fa-arrow-left mr-2"></i>{{__('Back to overview')}}
            </button>
        </a>
    </x-slot>
    <span x-data="{showMode: true, showDelete: false}">
        <div class="transition-all fixed w-screen h-screen top-0 left-0 flex justify-center items-center text-black dark:text-white" x-show="showDelete" x-cloak>
            <span class="bg-gray-900 opacity-40 absolute w-full h-full top-0 left-0" @click="showDelete = false;"></span>
            <div class="justify-around sm:justify-start w-full sm:w-auto h-full sm:h-auto rounded-md bg-gray-100 shadow-lg dark:bg-gray-700 p-10 flex items-center flex-col gap-1 z-10">
                <span class="flex flex-col items-center ">
                    <span class="text-5xl text-red-500 -z-10"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <h1 class=" text-center text-4xl font-bold">Are you sure?</h1>
                    <h1 class="text-center text-md ">{{ trans('Are you sure that you want to delete the user') }} <br>"{{ $user->name }}"</h1>
                </span>
                <form class="mt-7 flex flex-col gap-4 sm:flex-row justify-between w-full" method="POST" action="{{ route('users.destroy', $user) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDelete = false" before="{{ trans('Cancel') }}"
                            class="before:content-[attr(before)] bg-white dark:bg-gray-600 rounded-md px-5 py-2 hover:bg-gray-200 hover:dark:bg-gray-500 w-full"></button>
                    <button type="submit" before="{{ trans('Delete') }}"
                            class="before:content-[attr(before)] text-white bg-red-500 rounded-md px-5 py-2 hover:bg-red-400 w-full"></button>
                </form>
            </div>
        </div>


        <form method="post" action="{{ route('users.update', $user) }}">
            @CSRF
            @method('PUT')
            <div class="py-12 w-full px-2 sm:px-8">
                <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                    <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                        <h1 class="text-2xl font-bold">{{ $user->name}}</h1>
                        {{--<input name="name" required type="text" class="w-1/3 h-10 rounded-md border-none bg-gray-100 dark:bg-gray-800" placeholder="Pied Piper OS"/>--}}
                        <div>
                            <a>
                                <button type="button" x-show="showMode" x-on:click="showMode = false;" before="{{ __('Edit') }}"
                                        class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                                    <i class="fa-solid fa-user-pen"></i></button>
                                <button type="button" x-cloak x-show="!showMode" x-on:click="window.location = window.location" before="{{ __('Cancel Edit') }}"
                                        class="text-black before:content-[attr(before)] before:mr-2 bg-gray-200 dark:bg-gray-600 hover:dark:bg-gray-700 dark:text-white rounded-md px-5 py-2 hover:bg-gray-300">
                                    <i class="fa-solid fa-user-pen"></i></button>
                                <button type="button" x-on:click="showDelete = true" before="{{ __('Delete') }}"
                                        class="text-white before:content-[attr(before)] before:mr-2 bg-red-900 rounded-md px-5 py-2 hover:bg-red-800">
                                    <i class="fa-solid fa-trash"></i></button>
                            </a>
                        </div>
                    </div>
                    <div class="mt-5">
                        <span class="w-full flex flex-col items-center ">
                            <label for="name" class="font-bold text-left w-full text-md mt-5">Name</label>
                            <input name="name" required type="text"
                                   class="w-full h-10 rounded-md border-none bg-gray-100 dark:bg-gray-600 disabled:bg-white dark:disabled:bg-gray-800"
                                   placeholder="https://example.com/bgb/115"
                                   value="{{ $user->name }}"
                                   x-bind:disabled="showMode"
                            />
                            <x-input-error :messages="$errors->get('name')" class="w-full mt-1"/>
                        </span>

                        <span class="w-full flex flex-col items-center ">
                            <label for="email" class="font-bold text-left w-full text-md mt-5">E-Mail</label>
                            <input name="email" required type="email"
                                   class="w-full h-10 rounded-md border-none bg-gray-100 dark:bg-gray-600 disabled:bg-white dark:disabled:bg-gray-800"
                                   placeholder="https://example.com/bgb/115"
                                   value="{{ $user->email }}"
                                   x-bind:disabled="showMode"
                            />
                            <x-input-error :messages="$errors->get('email')" class="w-full mt-1"/>
                        </span>

                        <span class="w-full flex flex-row-reverse flex-wrap justify-end items-center mt-5">
                            <label for="admin" class="font-bold text-left text-md">Administrator</label>
                            <input name="admin" type="checkbox"
                                   class="mr-3 h-5 w-5 text-blue-800 disabled:text-gray-500 dark:disabled:bg-gray-800 dark:bg-gray-600 rounded-md disabled:bg-white bg-gray-100"
                                   placeholder="https://example.com/bgb/115"
                                   {{ $user->isAdmin() ? 'checked' : '' }}
                                   x-bind:disabled="showMode"
                            />
                            <x-input-error :messages="$errors->get('admin')" class="w-full mt-1"/>
                        </span>

                        <button type="button" x-on:click="" before="{{ __('Reset password') }}"
                                class="mt-8 text-gray-800 before:content-[attr(before)] before:mr-2 bg-gray-200 rounded-md px-5 py-2 hover:bg-gray-300 dark:bg-blue-900 dark:hover:bg-blue-800 dark:text-white">
                                    <i class="fa-solid fa-lock"></i></button>
                    </div>
                </div>

                <div class="mt-5 w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                    <button x-bind:disabled="showMode" type="submit" before="{{ trans('Update') }}"
                            class="before:content-[attr(before)] text-white disabled:bg-gray-600 bg-blue-500 rounded-md px-5 py-2 hover:bg-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800 disabled:hover:bg-gray-600"></button>
                    <a href="{{ route('users.index') }}">
                        <button type="button" before="{{ trans('Cancel') }}"
                                class="before:content-[attr(before)] bg-white dark:bg-gray-800 rounded-md px-5 py-2 hover:text-gray-500 hover:dark:text-gray-400">
                        </button></a>
                </div>
            </div>
        </form>
    </span>
</x-app-layout>
