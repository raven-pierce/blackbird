<x-app-layout>
    <x-slot name="header">
        <x-links.tertiary href="{{ route('enrollments.index') }}">
            <x-assets.icons.chevron-left class="h-5 w-5" />
            Back To Dashboard
        </x-links.tertiary>

        <span class="mt-4">Your Profile</span>
    </x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">On this page, you'll be able to update your <span
                class="font-semibold text-indigo-500">profile information</span>, such as your <span
                class="font-semibold text-indigo-500">phone number</span>.</p>
    </x-slot>

    <div class="mt-16 grid">
        <!--TODO: profile page + update method-->
        <form method="POST" action="{{ route('profile.update') }}" class="max-w-2xl flex flex-col">
            @csrf
            @method('UPDATE')

            <span class="font-semibold text-lg text-gray-700">Your Information</span>

            <div class="mt-4 flex items-center space-x-8">
                <div class="w-1/2">
                    <x-forms.label for="name" value="{{ __('Name') }}" />

                    <x-forms.input id="name" class="block mt-1 w-full" type="text" name="name"
                        value="{{ $user->name }}" required />
                </div>

                <div class="w-1/2">
                    <x-forms.label for="phone" value="{{ __('Phone Number') }}" />

                    <x-forms.input id="phone" class="block mt-1 w-full" type="text" name="phone"
                        value="{{ $user->profile->phone }}" required />
                </div>
            </div>

            <div class="mt-8 border-t border-gray-100"></div>

            <span class="mt-8 font-semibold text-lg text-gray-700">Guradian's Information</span>

            <div class="mt-4 flex items-center space-x-8">
                <div class="w-1/2">
                    <x-forms.label for="guardian_email" value="{{ __('Guardian\'s Email') }}" />

                    <x-forms.input id="guardian_email" class="block mt-1 w-full" type="email" name="guardian_email"
                        value="{{ $user->profile->guardian_email }}" required />
                </div>

                <div class="w-1/2">
                    <x-forms.label for="guardian_phone" value="{{ __('Guardian\'s Phone Number') }}" />

                    <x-forms.input id="guardian_phone" class="block mt-1 w-full" type="text" name="guardian_phone"
                        value="{{ $user->profile->guardian_phone }}" required />
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
