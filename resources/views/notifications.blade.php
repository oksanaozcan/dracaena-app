<x-app-layout>
    <x-slot name="header">
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Notifications') }}
      </h2>
      <small class="text-amber-700">Weekly, read notifications are automatically removed.</small>
    </x-slot>

    <div class="flex flex-row h-screen">
      <div class="p-6 text-white basis-1/6 bg-cyan-900">
        @include('includes.sidebar')
      </div>
      <div class="overflow-hidden shadow-sm basis-5/6">
        <div class="p-6 text-gray-900">
            <ul>
                @foreach (auth()->user()->notifications as $notification)
                <li>
                    @if ($notification->read_at != null)
                        <div class="flex p-4 rounded-lg bg-gray-50 dark:bg-gray-800" role="alert">
                            <i class="fa-solid fa-info"></i>
                            <div class="ml-3 text-sm font-medium">{{$notification->data['message']}}</div>
                            <div class="ml-auto -mx-1.5 -my-1.5 bg-gray-50 text-gray-500 rounded-lg focus:ring-2 focus:ring-gray-400 p-1.5 hover:bg-gray-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                                <i class="fa-solid fa-check"></i>
                            </div>

                        </div>
                    @else
                        <div class="flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <i class="fa-solid fa-info"></i>
                            <div class="ml-3 text-sm font-medium">{{$notification->data['message']}}</div>
                            <a
                                href="{{route('markasread', $notification->id)}}"
                                class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                                aria-label="Close"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>
                    @endif
                </li>
                @endforeach
            </ul>

        </div>
      </div>
    </div>
</x-app-layout>
