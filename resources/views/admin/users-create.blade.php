@extends('layouts.admin')

@section('title', 'Add User')
@section('page-title', 'Add User')
@section('page-description', 'Create a new user and their profile')

@section('admin-content')
<div class="space-y-6">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6 space-y-8">
        @csrf

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }} class="mr-2">
                    <label for="is_admin" class="text-sm text-gray-700">Grant admin access</label>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Created By</label>
                    <select name="profile_created_by" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="self" {{ old('profile_created_by')==='self' ? 'selected' : '' }}>Self</option>
                        <option value="parent" {{ old('profile_created_by')==='parent' ? 'selected' : '' }}>Parent</option>
                        <option value="sibling" {{ old('profile_created_by')==='sibling' ? 'selected' : '' }}>Sibling</option>
                        <option value="relative" {{ old('profile_created_by')==='relative' ? 'selected' : '' }}>Relative</option>
                        <option value="friend" {{ old('profile_created_by')==='friend' ? 'selected' : '' }}>Friend</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="male" {{ old('gender')==='male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender')==='female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Name</label>
                    <input type="text" name="profile_name" value="{{ old('profile_name') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother Tongue</label>
                    <input type="text" name="mother_tongue" value="{{ old('mother_tongue') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subcaste</label>
                    <input type="text" name="subcaste" value="{{ old('subcaste') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Willing to marry from subcaste</label>
                    <select name="willing_to_marry_from_subcaste" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="yes" {{ old('willing_to_marry_from_subcaste')==='yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ old('willing_to_marry_from_subcaste')==='no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                    <select name="marital_status" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="Unmarried" {{ old('marital_status')==='Unmarried' ? 'selected' : '' }}>Unmarried</option>
                        <option value="Widower" {{ old('marital_status')==='Widower' ? 'selected' : '' }}>Widower</option>
                        <option value="Divorced" {{ old('marital_status')==='Divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="Separated" {{ old('marital_status')==='Separated' ? 'selected' : '' }}>Separated</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country living in</label>
                    <input type="text" name="country_living_in" value="{{ old('country_living_in') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Residing State</label>
                    <input type="text" name="residing_state" value="{{ old('residing_state') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Residing City</label>
                    <input type="text" name="residing_city" value="{{ old('residing_city') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Citizenship</label>
                    <input type="text" name="citizenship" value="{{ old('citizenship') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Height</label>
                    <input type="text" name="height" value="{{ old('height') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Education</label>
                    <input type="text" name="education" value="{{ old('education') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employed In</label>
                    <input type="text" name="employed_in" value="{{ old('employed_in') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Annual Income</label>
                    <input type="text" name="annual_income" value="{{ old('annual_income') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Physical Status</label>
                    <select name="physical_status" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="normal" {{ old('physical_status')==='normal' ? 'selected' : '' }}>Normal</option>
                        <option value="physically_challenged" {{ old('physical_status')==='physically_challenged' ? 'selected' : '' }}>Physically Challenged</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Family Status</label>
                    <select name="family_status" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="poor" {{ old('family_status')==='poor' ? 'selected' : '' }}>Poor</option>
                        <option value="lower_middle" {{ old('family_status')==='lower_middle' ? 'selected' : '' }}>Lower Middle Class</option>
                        <option value="middle_class" {{ old('family_status')==='middle_class' ? 'selected' : '' }}>Middle Class</option>
                        <option value="upper_middle class" {{ old('family_status')==='upper_middle class' ? 'selected' : '' }}>Upper Middle Class</option>
                        <option value="rich_affluent" {{ old('family_status')==='rich_affluent' ? 'selected' : '' }}>Rich/Affluent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Family Type</label>
                    <select name="family_type" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="joint_family" {{ old('family_type')==='joint_family' ? 'selected' : '' }}>Joint Family</option>
                        <option value="nuclear_family" {{ old('family_type')==='nuclear_family' ? 'selected' : '' }}>Nuclear Family</option>
                        <option value="small_family" {{ old('family_type')==='small_family' ? 'selected' : '' }}>Small Family</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">About Me</label>
                    <textarea name="about_me" class="w-full px-4 py-2 border rounded-md" rows="3">{{ old('about_me') }}</textarea>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">About My Family</label>
                    <textarea name="about_my_family" class="w-full px-4 py-2 border rounded-md" rows="3">{{ old('about_my_family') }}</textarea>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Few Lines About My Partner</label>
                    <textarea name="fewlines_about_my_partner" class="w-full px-4 py-2 border rounded-md" rows="3">{{ old('fewlines_about_my_partner') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosham</label>
                    <input type="text" name="dosham" value="{{ old('dosham') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Star/Nakshatram</label>
                    <select name="star_nakshatram" class="w-full px-4 py-2 border rounded-md">
                        <option value="">Select Star</option>
                        @if(isset($stars))
                            @foreach($stars as $star)
                                <option value="{{ $star }}" {{ old('star_nakshatram')===$star ? 'selected' : '' }}>{{ $star }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rasi</label>
                    <select name="rasi" class="w-full px-4 py-2 border rounded-md">
                        <option value="">Select Rasi</option>
                        @if(isset($rasis))
                            @foreach($rasis as $rasi)
                                <option value="{{ $rasi }}" {{ old('rasi')===$rasi ? 'selected' : '' }}>{{ $rasi }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gothram</label>
                    <input type="text" name="gothram" value="{{ old('gothram') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time of Birth</label>
                    <input type="time" name="time_of_birth" value="{{ old('time_of_birth') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country of Birth</label>
                    <input type="text" name="country_of_birth" value="{{ old('country_of_birth') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">State of Birth</label>
                    <input type="text" name="state_of_birth" value="{{ old('state_of_birth') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City of Birth</label>
                    <input type="text" name="city_of_birth" value="{{ old('city_of_birth') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Horoscope Chart Style</label>
                    <input type="text" name="horoscope_chart_style" value="{{ old('horoscope_chart_style') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
            </div>
        </div>

        <!-- Hobbies & Interests Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hobbies & Interests</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hobbies & Interests</label>
                    <textarea name="hobbies_and_interests" class="w-full px-4 py-2 border rounded-md" rows="3" placeholder="e.g., Music, Sports, Food, Reading, Travel">{{ old('hobbies_and_interests') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Music</label>
                    <textarea name="music" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Classical, Rock, Pop">{{ old('music') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sports</label>
                    <textarea name="sports" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Cricket, Football, Badminton">{{ old('sports') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Food</label>
                    <textarea name="food" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Indian, Chinese, Italian">{{ old('food') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Family Details Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Family Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Father's Occupation</label>
                    <input type="text" name="father_occupation" value="{{ old('father_occupation') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Occupation</label>
                    <input type="text" name="mother_occupation" value="{{ old('mother_occupation') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. of Brothers</label>
                    <input type="number" name="no_of_brothers" value="{{ old('no_of_brothers') }}" class="w-full px-4 py-2 border rounded-md" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. of Sisters</label>
                    <input type="number" name="no_of_sisters" value="{{ old('no_of_sisters') }}" class="w-full px-4 py-2 border rounded-md" min="0">
                </div>
            </div>
        </div>

        <!-- Partner Preference Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Partner Preference</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Age Min</label>
                    <input type="text" name="preferred_age_min" value="{{ old('preferred_age_min') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Age Max</label>
                    <input type="text" name="preferred_age_max" value="{{ old('preferred_age_max') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Height Min</label>
                    <input type="text" name="preferred_height_min" value="{{ old('preferred_height_min') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Height Max</label>
                    <input type="text" name="preferred_height_max" value="{{ old('preferred_height_max') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Marital Status</label>
                    <input type="text" name="preferred_marital_status" value="{{ old('preferred_marital_status') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Physical Status</label>
                    <input type="text" name="preferred_physical_status" value="{{ old('preferred_physical_status') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Mother Tongue</label>
                    <input type="text" name="preferred_mother_tongue" value="{{ old('preferred_mother_tongue') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Subcaste</label>
                    <input type="text" name="preferred_subcaste" value="{{ old('preferred_subcaste') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Chevvai Dosham</label>
                    <input type="text" name="preferred_chevvai_dosham" value="{{ old('preferred_chevvai_dosham') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Education</label>
                    <input type="text" name="preferred_education" value="{{ old('preferred_education') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Employed In</label>
                    <input type="text" name="preferred_employed_in" value="{{ old('preferred_employed_in') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Occupation</label>
                    <input type="text" name="preferred_occupation" value="{{ old('preferred_occupation') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Annual Income Min</label>
                    <input type="text" name="preferred_annual_income_min" value="{{ old('preferred_annual_income_min') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Annual Income Max</label>
                    <input type="text" name="preferred_annual_income_max" value="{{ old('preferred_annual_income_max') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Country</label>
                    <input type="text" name="preferred_country" value="{{ old('preferred_country') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Citizenship</label>
                    <input type="text" name="preferred_citizenship" value="{{ old('preferred_citizenship') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
            </div>
        </div>

        <!-- New Preference Fields Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Preferences</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Eating Habit</label>
                    <input type="text" name="preferred_eating_habit" value="{{ old('preferred_eating_habit') }}" class="w-full px-4 py-2 border rounded-md" placeholder="e.g., Vegetarian, Non-vegetarian">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Drinking Habit</label>
                    <input type="text" name="preferred_drinking_habit" value="{{ old('preferred_drinking_habit') }}" class="w-full px-4 py-2 border rounded-md" placeholder="e.g., Non-drinker, Occasional">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Smoking Habit</label>
                    <input type="text" name="preferred_smoking_habit" value="{{ old('preferred_smoking_habit') }}" class="w-full px-4 py-2 border rounded-md" placeholder="e.g., Non-smoker, Occasional">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Hobbies & Interests</label>
                    <textarea name="preferred_hobbies_and_interests" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Music, Sports, Reading, Travel">{{ old('preferred_hobbies_and_interests') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Music</label>
                    <textarea name="preferred_music" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Classical, Rock, Pop">{{ old('preferred_music') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Sports</label>
                    <textarea name="preferred_sports" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Cricket, Football, Badminton">{{ old('preferred_sports') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Food</label>
                    <textarea name="preferred_food" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Indian, Chinese, Italian">{{ old('preferred_food') }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Images</h3>
            <input type="file" name="images[]" accept="image/*" multiple class="block w-full text-sm text-gray-700">
            <p class="text-xs text-gray-500 mt-2">You can upload multiple images. Max 2MB each.</p>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.users') }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create User</button>
        </div>
    </form>
</div>
@endsection