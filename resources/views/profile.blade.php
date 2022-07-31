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

            <span class="font-semibold text-lg text-gray-700">{{ __('misc.labels.headings.personal') }}</span>

            <div class="mt-4 flex items-center space-x-8">
                <div class="w-1/2">
                    <x-forms.label for="name">{{ __('misc.labels.name') }}</x-forms.label>

                    <x-forms.input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $user->name }}" required />
                </div>

                <div class="w-1/2">
                    <x-forms.label for="phone">{{ __('misc.labels.phone') }}</x-forms.label>

                    <x-forms.input id="phone" class="block mt-1 w-full" type="text" name="phone" value="{{ $user->profile->phone }}" required />
                </div>
            </div>

            <div class="mt-8 border-t border-gray-100"></div>

            <span class="mt-8 font-semibold text-lg text-gray-700">{{ __('misc.labels.headings.emails') }}</span>

            <div class="mt-4 flex items-center space-x-8">
                <div class="w-1/2">
                    <x-forms.label for="azure_email">{{ __('misc.labels.emails.azure') }}</x-forms.label>

                    <x-forms.input id="azure_email" class="block mt-1 w-full" type="email" name="azure_email" value="{{ $user->profile->azure_email }}" required :disabled="true" />
                </div>

                <div class="w-1/2">
                    <x-forms.label for="current_email">{{ __('misc.labels.emails.personal') }}</x-forms.label>

                    <x-forms.input id="current_email" class="block mt-1 w-full" type="text" name="current_email" value="{{ $user->email }}" required />
                </div>
            </div>

            <div class="mt-8 border-t border-gray-100"></div>

            <span class="mt-8 font-semibold text-lg text-gray-700">{{ __('misc.labels.headings.guardian') }}</span>

            <div class="mt-4 flex items-center space-x-8">
                <div class="w-1/2">
                    <x-forms.label for="guardian_email">{{ __('misc.labels.emails.guardian') }}</x-forms.label>

                    <x-forms.input id="guardian_email" class="block mt-1 w-full" type="email" name="guardian_email" value="{{ $user->profile->guardian_email }}" required />
                </div>

                <div class="w-1/2">
                    <x-forms.label for="guardian_phone">{{ __('misc.labels.phones.guardian') }}</x-forms.label>

                    <x-forms.input id="guardian_phone" class="block mt-1 w-full" type="text" name="guardian_phone" value="{{ $user->profile->guardian_phone }}" required />
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
