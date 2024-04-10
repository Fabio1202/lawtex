<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{__('Users')}}
        </h2>
    </x-slot>

    <span x-data="{showCreate: false}">
        <!-- Create User PopUp -->
        <div class="transition-all fixed w-screen h-screen top-0 left-0 flex justify-center items-center text-black dark:text-white" x-show="showCreate" x-cloak>
            <span class="bg-gray-900 opacity-40 absolute w-full h-full top-0 left-0" @click="showCreate = false;"></span>
            <form id="create-user-form" class="justify-around sm:justify-start w-full sm:w-auto h-full sm:h-auto rounded-md bg-gray-100 shadow-lg dark:bg-gray-700 p-10 flex items-center flex-col gap-1 z-10">
                <span class="flex flex-col items-center w-full">
                    <h1 class=" text-center text-4xl font-bold sm:px-16">New User</h1>
                    <label for="email" class="text-left w-full text-md mt-5">E-Mail</label>
                    <input id="create-user-email" name="email" required type="email" class="w-full h-10 rounded-md border-none bg-white dark:bg-gray-800" placeholder="stinson@gnb.com"/>
                </span>
                <div class="mt-7 flex flex-col gap-4 sm:flex-row justify-between w-full">
                    @csrf
                    <button type="button" @click="showCreate= false" before="{{ trans('Cancel') }}"
                            class="before:content-[attr(before)] bg-white dark:bg-gray-600 rounded-md px-5 py-2 hover:bg-gray-200 hover:dark:bg-gray-500 w-full"></button>
                    <button type="submit" before="{{ trans('Create Link') }}"
                            class="before:content-[attr(before)] text-white bg-blue-500 rounded-md px-5 py-2 hover:bg-blue-400 w-full"></button>
                </div>
            </form>
            <div id="user-created" class="hidden justify-around sm:justify-start w-full sm:w-auto h-full sm:h-auto rounded-md bg-gray-100 shadow-lg dark:bg-gray-700 p-10 items-center flex-col gap-1 z-10">
                <span class="flex-col items-center w-full flex">
                    <h1 class=" text-center text-4xl font-bold sm:px-16">User Created!</h1>
                    <label for="email" class="text-left w-full text-md mt-5">Link</label>
                    <input id="create-user-activation-link" name="url" required type="url" class="w-full h-10 rounded-md border-none bg-white dark:bg-gray-800"/>
                </span>
                <div class="mt-7 flex flex-col gap-4 sm:flex-row justify-between w-full">
                    <button type="button" @click="showCreate= false" before="{{ trans('Cancel') }}"
                            class="before:content-[attr(before)] bg-white dark:bg-gray-600 rounded-md px-5 py-2 hover:bg-gray-200 hover:dark:bg-gray-500 w-full"></button>
                </div>
            </div>
        </div>

        <div class="py-12 w-full px-2 sm:px-8">
            <div class="w-full px-8 py-8 bg-white dark:bg-gray-800 text-black dark:text-white rounded-md">
                <div class="flex-col gap-4 sm:flex-row w-full flex justify-between items-center">
                    <h1 class="text-2xl font-bold">{{ __('All users') }}</h1>
                    {{--<input name="name" required type="text" class="w-1/3 h-10 rounded-md border-none bg-gray-100 dark:bg-gray-800" placeholder="Pied Piper OS"/>--}}
                    <div>
                        <a>
                            <button @click="showCreate = true;" before="{{ __('New user') }}"
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

    <script nonce="{{ \App\Http\NonceManager::generateNonce() }}">
        function getActivationLink() {
            let email = document.getElementById('create-user-email').value;
            let url = "{{ route('users.create-activation-link') }}";
            let data = new FormData();
            data.append('email', email);
            fetch(url, {
                method: 'POST',
                body: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.link !== null) {
                        document.getElementById('create-user-activation-link').value = data.link;
                        document.getElementById('create-user-form').classList.add('hidden');
                        document.getElementById('user-created').classList.add('flex');
                        document.getElementById('user-created').classList.remove('hidden');
                    } else {
                        alert('An error occurred while creating the activation link');
                    }
                });
        }

        let createUserForm = document.getElementById('create-user-form');

        createUserForm.addEventListener('submit', (event) => {
            event.preventDefault();
            getActivationLink();
        });
    </script>
</x-app-layout>
