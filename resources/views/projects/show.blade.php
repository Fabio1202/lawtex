<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('projects.index') }}">
            <button class="hover:opacity-60 transition-all w-full xs:w-auto px-10 h-full rounded-md text-black dark:text-white">
                <i class="fa-solid fa-arrow-left mr-2"></i>{{__('Back to overview')}}
            </button>
        </a>
    </x-slot>
        <div class="py-12 w-full px-2 sm:px-8">
            <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                    <span class="flex flex-col gap-1">
                        <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
                        <h2>{{ __('# of laws') }}: {{ $project->laws->count() }}</h2>
                    </span>
                    {{--<input name="name" required type="text" class="w-1/3 h-10 rounded-md border-none bg-gray-100 dark:bg-gray-800" placeholder="Pied Piper OS"/>--}}
                    <div class="w-full xs:w-auto flex flex-col xs:flex-row gap-3">
                            <button before="{{ trans('Add Law') }}"
                                    class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                                <i class="fa-regular fa-plus"></i></button>
                            {{--<button before="{{ trans('Export') }}"
                                    class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                                <i class="fa-solid fa-file-export"></i></button>--}}
                    </div>
                </div>

                <div class="mt-5">
                    @if($project->laws->isEmpty())
                        <span class="z-10 flex-col gap-3 md:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all">
                            <span class="flex content-around flex-col">
                                <h1 class="text-lg opacity-60 flex items-center"><i class="fa-solid fa-section mr-4 text-3xl"></i>{{ __('Nothing here... Start to add your first law!') }}</h1>
                            </span>
                        </span>
                    @else
                        @foreach($project->lawBooks() as $lawBooks)
                            <h1 class="text-xl font-bold {{ $loop->first ? '' : 'mt-5' }}">{{ $lawBooks->name }}</h1>
                            @foreach($lawBooks->laws->where('project_id', $project->id) as $law)
                                <span class="z-10 cursor-pointer flex-col gap-3 md:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all hover:bg-gray-100 hover:dark:bg-gray-700 {{ ($loop?->last ?? false) ? "" : "border-b-2" }}">
                                <span class="flex content-around flex-col">
                                    <h1 class="text-lg">§ {{ $law->slug }} {{$law->name}}</h1>
                                </span>
                                <span class="inline-block z-20">
                                    <a target="_blank" href="{{ $law->url }}"><button
                                            class="text-white before:content-['Open'] before:mr-2 bg-blue-500 rounded-md px-2 py-1 hover:bg-blue-400"><i class="fa-solid fa-link"></i></button></a>
                                    <button @click=""
                                            class="text-white before:content-['Delete'] before:mr-2 bg-red-500 rounded-md px-2 py-1 hover:bg-red-400"><i
                                            class="fa-regular fa-trash-can"></i></button>
                                </span>
                            </span>
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
</x-app-layout>
