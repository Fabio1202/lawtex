<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('projects.index') }}">
            <button
                class="hover:opacity-60 transition-all w-full xs:w-auto px-10 h-full rounded-md text-black dark:text-white">
                <i class="fa-solid fa-arrow-left mr-2"></i>{{__('Back to overview')}}
            </button>
        </a>
    </x-slot>
    <span x-data="{showExport: false, showCreate: {{ !empty($errors->get('create.url')) ? 'true' : 'false' }} }">
        <!-- Create Modal -->
        <div
            class="transition-all fixed w-screen h-screen top-0 left-0 flex justify-center items-center text-black dark:text-white z-20"
            x-show="showCreate" x-cloak>
            <span class="bg-gray-900 opacity-40 absolute w-full h-full top-0 left-0"
                  @click="showCreate = false;"></span>
            <form
                class="z-20 justify-around sm:justify-start w-full sm:w-auto h-full sm:h-auto rounded-md bg-gray-100 shadow-lg dark:bg-gray-700 p-10 flex items-center flex-col gap-1"
                method="POST" action="{{ route('laws.store', $project) }}">
                    <span class="w-full flex flex-col items-center ">
                        <h1 class=" text-center text-4xl font-bold sm:px-32">New Law</h1>
                        <label for="name" class="text-left w-full text-md mt-5">Law URL</label>
                        <input name="url" required type="url"
                               class="w-full h-10 rounded-md border-none bg-white dark:bg-gray-800"
                               placeholder="https://example.com/bgb/115"/>
                        <x-input-error :messages="$errors->get('create.url')" class="w-full mt-1"/>
                    </span>
                <div class="mt-7 flex flex-col gap-4 sm:flex-row justify-between w-full">
                    @csrf
                    <button type="button" @click="showCreate=false" before="{{ trans('Cancel') }}"
                            class="before:content-[attr(before)] bg-white dark:bg-gray-600 rounded-md px-5 py-2 hover:bg-gray-200 hover:dark:bg-gray-500 w-full"></button>
                    <button type="submit" before="{{ trans('Create') }}"
                            class="before:content-[attr(before)] text-white bg-blue-500 rounded-md px-5 py-2 hover:bg-blue-400 w-full"></button>
                </div>
            </form>
        </div>

        <!-- Export Modal -->
        <div
            class="transition-all fixed w-screen h-screen top-0 left-0 flex justify-center items-center text-black dark:text-white"
            x-show="showExport" x-cloak>
            <span class="bg-gray-900 opacity-40 absolute w-full h-full top-0 left-0"
                  @click="showExport = false;"></span>
            <div
                class="justify-around sm:justify-start w-full sm:w-auto h-full sm:h-auto rounded-md bg-gray-100 shadow-lg dark:bg-gray-700 p-10 flex items-center flex-col gap-1 z-20"
                >
                    <span class="w-full flex flex-col items-center ">
                        <h1 class=" text-center text-4xl font-bold sm:px-32">Export</h1>
                        <label for="name" class="text-left w-full text-md mt-5">Magic link</label>
                        <input name="url" required type="url"
                               class="w-full h-10 rounded-md border-none bg-white dark:bg-gray-800"
                               value="{{ \Illuminate\Support\Facades\URL::signedRoute('projects.latex', $project) }}"/>
                    </span>
                <div class="mt-7 flex flex-col gap-4 sm:flex-row justify-between w-full">
                    <button type="button" @click="showExport=false" before="{{ trans('Cancel') }}"
                            class="before:content-[attr(before)] bg-white dark:bg-gray-600 rounded-md px-5 py-2 hover:bg-gray-200 hover:dark:bg-gray-500 w-full"></button>
                    <a class="block w-full" href="{{ \Illuminate\Support\Facades\URL::signedRoute('projects.latex', $project) }}"><button type="button" before="{{ trans('Download') }}"
                            class="before:content-[attr(before)] px-5 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-400 w-full"></button></a>
                </div>
            </div>
        </div>


        <div class="py-12 w-full px-2 sm:px-8">
            <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                    <span class="flex flex-col gap-1">
                        <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
                        <h2>{{ __('# of laws') }}: {{ $project->laws->count() }}</h2>
                    </span>
                    {{--<input name="name" required type="text" class="w-1/3 h-10 rounded-md border-none bg-gray-100 dark:bg-gray-800" placeholder="Pied Piper OS"/>--}}
                    <div class="w-full xs:w-auto flex flex-col xs:flex-row gap-3">
                        <button @click="showCreate = true" before="{{ trans('Add Law') }}"
                                class="whitespace-nowrap text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                            <i class="fa-regular fa-plus"></i></button>
                        <button @click="showExport = true" before="{{ trans('Export') }}"
                                class="text-white before:content-[attr(before)] before:mr-2 bg-blue-900 rounded-md px-5 py-2 hover:bg-blue-800">
                            <i class="fa-solid fa-file-export"></i></button>
                    </div>
                </div>

                <div class="mt-5">
                    @if($project->laws->isEmpty())
                        <span
                            class="z-10 flex-col gap-3 md:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all">
                            <span class="flex content-around flex-col">
                                <h1 class="text-lg opacity-60 flex items-center"><i
                                        class="fa-solid fa-section mr-4 text-3xl"></i>{{ __('Nothing here... Start to add your first law!') }}</h1>
                            </span>
                        </span>
                    @else
                        @foreach($project->lawBooks() as $lawBooks)
                            <h1 class="text-xl font-bold {{ $loop->first ? '' : 'mt-5' }}">{{ $lawBooks->name }} ({{ $lawBooks->short }})</h1>
                            @foreach($lawBooks->laws->where('project_id', $project->id) as $law)
                                <span
                                    class="z-10 cursor-pointer flex-col gap-3 md:flex-row px-3 items-center justify-between flex w-full py-5 border-gray-200 dark:border-gray-700 transition-all hover:bg-gray-100 hover:dark:bg-gray-700 {{ ($loop?->last ?? false) ? "" : "border-b-2" }}">
                                <span class="flex content-around flex-col">
                                    <h1 class="text-lg">{{ $law->prefix }} {{ $law->slug }} {{$law->name}}</h1>
                                </span>
                                <span class="inline-block">
                                    <a target="_blank" href="{{ $law->url }}"><button
                                            class="text-white before:content-['Open'] before:mr-2 bg-blue-500 rounded-md px-2 py-1 hover:bg-blue-400"><i
                                                class="fa-solid fa-link"></i></button></a>
                                    <form method="post" class="inline" action="{{ route('laws.destroy', ['project' => $project, 'law' => $law]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                               class="text-white before:content-['Delete'] before:mr-2 bg-red-500 rounded-md px-2 py-1 hover:bg-red-400"><i
                                                class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                </span>
                            </span>
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </span>
</x-app-layout>
