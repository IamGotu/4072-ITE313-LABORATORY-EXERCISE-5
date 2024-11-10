<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Fields -->
        <div class="flex space-x-4">
            <div class="w-1/3">
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $user->first_name)" required autofocus placeholder="{{ __('First Name') }}" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div class="w-1/3">
                <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name', $user->middle_name)" placeholder="{{ __('Middle Name (Optional)') }}" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <div class="w-1/3">
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $user->last_name)" required placeholder="{{ __('Last Name') }}" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Suffix Field (Optional) -->
        <div class="mt-4">
            <x-text-input id="suffix" class="block mt-1 w-full" type="text" name="suffix" :value="old('suffix', $user->suffix)" placeholder="{{ __('Suffix (Optional)') }}" />
            <x-input-error :messages="$errors->get('suffix')" class="mt-2" />
        </div>

        <!-- Birthdate Fields -->
        <div class="flex space-x-4 mt-4">
            <!-- Month -->
            <div class="w-1/3">
                <x-input-label for="birth_month" :value="__('Month')" />
                <select id="birth_month" name="birth_month" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="">{{ __('Select Month') }}</option>
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" {{ old('birth_month', \Carbon\Carbon::parse($user->birth_date)->format('m')) == $month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $month, 10)) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('birth_month')" class="mt-2" />
            </div>

            <!-- Day -->
            <div class="w-1/3">
                <x-input-label for="birth_day" :value="__('Day')" />
                <select id="birth_day" name="birth_day" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="">{{ __('Select Day') }}</option>
                    @foreach (range(1, 31) as $day)
                        <option value="{{ $day }}" {{ old('birth_day', \Carbon\Carbon::parse($user->birth_date)->format('d')) == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('birth_day')" class="mt-2" />
            </div>

            <!-- Year -->
            <div class="w-1/3">
                <x-input-label for="birth_year" :value="__('Year')" />
                <select id="birth_year" name="birth_year" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="">{{ __('Select Year') }}</option>
                    @foreach (range(date('Y'), 1900, -1) as $year)
                        <option value="{{ $year }}" {{ old('birth_year', \Carbon\Carbon::parse($user->birth_date)->format('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('birth_year')" class="mt-2" />
            </div>
        </div>

        <!-- Gender Dropdown -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" required onchange="togglePronounsField()">
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="custom" {{ old('gender', $user->gender) == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
            </select>

            <!-- Pronouns Dropdown (shown when "Custom" is selected) -->
            <div class="mt-2" id="custom-pronouns" style="display: {{ old('gender', $user->gender) == 'custom' ? 'block' : 'none' }};">
                <x-input-label for="pronouns" :value="__('Pronouns')" />
                <select id="pronouns" name="pronouns" class="block mt-1 w-full p-2 border border-gray-300 rounded-md">
                    <option value="she/her" {{ old('pronouns', $user->pronouns) == 'she/her' ? 'selected' : '' }}>{{ __('She/Her') }}</option>
                    <option value="he/his" {{ old('pronouns', $user->pronouns) == 'he/his' ? 'selected' : '' }}>{{ __('He/His') }}</option>
                    <option value="they/them" {{ old('pronouns', $user->pronouns) == 'they/them' ? 'selected' : '' }}>{{ __('They/Them') }}</option>
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
        </div>
    </form>
    <script>
        // JavaScript function to toggle the visibility of the Pronouns dropdown based on Gender selection
        function togglePronounsField() {
            var genderField = document.getElementById("gender");
            var pronounsField = document.getElementById("custom-pronouns");

            if (genderField.value === "custom") {
                pronounsField.style.display = "block";
            } else {
                pronounsField.style.display = "none";
                pronounsField.value = '';
            }
        }

        // Initialize the pronouns field visibility on page load based on the current selection
        document.addEventListener("DOMContentLoaded", function() {
            togglePronounsField();
        });
    </script>
</section>