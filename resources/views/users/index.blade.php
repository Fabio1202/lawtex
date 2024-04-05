<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{__('Users')}}
        </h2>
    </x-slot>
    <span>
        <div class="py-12 w-full px-2 sm:px-8">
            <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                    <h1 class="text-2xl font-bold">{{ __('All users') }}</h1>
                    {{--<input name="name" required type="text" class="w-1/3 h-10 rounded-md border-none bg-gray-100 dark:bg-gray-800" placeholder="Pied Piper OS"/>--}}
                    <div>
                        <a>
                            <button @click="" before="{{ __('New user') }}"
                                    class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                                <i class="fa-regular fa-pen-to-square"></i></button>
                        </a>
                    </div>
                </div>
                <div class="mt-5">
                    @foreach($users as $user)
                        <a href="{{ route('users.show', $user) }}" class="project z-10 cursor-pointer flex-col gap-3 md:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all hover:bg-gray-100 hover:dark:bg-gray-700 {{ $loop->last ? "" : "border-b-2" }}">
                            <span url="{{ route('users.show', $user) }}" class="flex items-center md:items-start flex-col justify-center" @click="window.location = $event.currentTarget.getAttribute('url')">
                                <h1 class="text-lg font-bold flex items-center gap-1">
                                    {{ $user->name }}
                                    @if($user->isAdmin())
                                        <span class="text-xs px-2 py-0.5 border-2 rounded-full dark:border-gray-600">
                                            Admin
                                            <i class="fa-solid fa-crown text-yellow-500"></i>
                                        </span>
                                    @endif
                                </h1>
                                <h2 class="text-sm">{{ $user->email }}</h2>
                            </span>
                        </a>
                    @endforeach
                    @if($users->hasPages())
                        <span class="block h-10"></span>
                    @endif
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </span>
</x-app-layout>
