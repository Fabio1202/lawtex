<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('users.index') }}">
            <button
                class="hover:opacity-60 transition-all w-full xs:w-auto px-10 h-full rounded-md text-black dark:text-white">
                <i class="fa-solid fa-arrow-left mr-2"></i>{{__('Back to overview')}}
            </button>
        </a>
    </x-slot>
    <span>
        <div class="py-12 w-full px-2 sm:px-8">
            <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                    <h1 class="text-2xl font-bold">{{ $user->name}}</h1>
                    {{--<input name="name" required type="text" class="w-1/3 h-10 rounded-md border-none bg-gray-100 dark:bg-gray-800" placeholder="Pied Piper OS"/>--}}
                    <div>
                        <a>
{{--                            <button @click="" before="{{ __('New user') }}"--}}
{{--                                    class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">--}}
{{--                                <i class="fa-regular fa-pen-to-square"></i></button>--}}
                        </a>
                    </div>
                </div>
                <div class="mt-5">

                </div>
            </div>
        </div>
    </span>
</x-app-layout>
