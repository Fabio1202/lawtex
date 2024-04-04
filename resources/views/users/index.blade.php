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
                        <span url="" @click.self="window.location = $event.target.getAttribute('url')" class="project z-10 cursor-pointer flex-col gap-3 md:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all hover:bg-gray-100 hover:dark:bg-gray-700 {{ $loop->last ? "" : "border-b-2" }}">
                            <span url="" class="flex items-center md:items-start flex-col justify-center" @click="window.location = $event.currentTarget.getAttribute('url')">
                                <h1 class="text-lg font-bold">{{ $user->name }}</h1>
                                <h2 class="text-sm">{{ $user->email }}</h2>
                            </span>
                            <span class="inline-block">
                                {{--  <button @click="showDeletePopUp({{ $project }})"
                                        class="text-white before:content-['Share'] before:mr-2 bg-yellow-500 rounded-md px-2 py-1 hover:bg-yellow-400"><i class="fa-solid fa-share-from-square"></i></button>
                                --}}
{{--                              <button @click="showRenamePopup({{ $project }})" before="{{ trans('Rename') }}"--}}
{{--                                      class="before:content-[attr(before)] text-white before:mr-2 bg-blue-500 rounded-md px-2 py-1 hover:bg-blue-400"><i--}}
{{--                                      class="fa-regular fa-pen-to-square"></i></button>--}}
{{--                                <button @click="showDeletePopUp({{ $project }})"--}}
{{--                                        class="text-white before:content-['Delete'] before:mr-2 bg-red-500 rounded-md px-2 py-1 hover:bg-red-400"><i--}}
{{--                                        class="fa-regular fa-trash-can"></i></button>--}}
                            </span>
                        </span>
                    @endforeach
                    <span class="block h-10"></span>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </span>
</x-app-layout>
