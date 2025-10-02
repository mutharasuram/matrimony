@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-description', 'Update user account and profile')

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

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6 space-y-8">
        @csrf
        @method('PUT')

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                    <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} class="mr-2">
                    <label for="is_admin" class="text-sm text-gray-700">Admin access</label>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile</h3>
            @php($p = $user->profile)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Created By</label>
                    <select name="profile_created_by" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['self','parent','sibling','relative','friend'] as $opt)
                            <option value="{{ $opt }}" {{ old('profile_created_by', $p->profile_created_by ?? '')===$opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['male','female'] as $g)
                            <option value="{{ $g }}" {{ old('gender', $p->gender ?? '')===$g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Name</label>
                    <input type="text" name="profile_name" value="{{ old('profile_name', $p->name ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', optional($p)->dob) }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother Tongue</label>
                    <input type="text" name="mother_tongue" value="{{ old('mother_tongue', $p->mother_tongue ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subcaste</label>
                    <input type="text" name="subcaste" value="{{ old('subcaste', $p->subcaste ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Willing to marry from subcaste</label>
                    <select name="willing_to_marry_from_subcaste" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['yes','no'] as $opt)
                            <option value="{{ $opt }}" {{ old('willing_to_marry_from_subcaste', $p->willing_to_marry_from_subcaste ?? '')===$opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                    <select name="marital_status" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['Unmarried','Widower','Divorced','Separated'] as $opt)
                            <option value="{{ $opt }}" {{ old('marital_status', $p->marital_status ?? '')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country living in</label>
                    <input type="text" name="country_living_in" value="{{ old('country_living_in', $p->country_living_in ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Residing State</label>
                    <input type="text" name="residing_state" value="{{ old('residing_state', $p->residing_state ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Residing City</label>
                    <input type="text" name="residing_city" value="{{ old('residing_city', $p->residing_city ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Citizenship</label>
                    <input type="text" name="citizenship" value="{{ old('citizenship', $p->citizenship ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Height</label>
                    <input type="text" name="height" value="{{ old('height', $p->height ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Education</label>
                    <input type="text" name="education" value="{{ old('education', $p->education ?? '') }}" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employed In</label>
                    <input type="text" name="employed_in" value="{{ old('employed_in', $p->employed_in ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $p->occupation ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Annual Income</label>
                    <input type="text" name="annual_income" value="{{ old('annual_income', $p->annual_income ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Physical Status</label>
                    <select name="physical_status" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['normal','physically_challenged'] as $opt)
                            <option value="{{ $opt }}" {{ old('physical_status', $p->physical_status ?? '')===$opt ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ', $opt)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Family Status</label>
                    <select name="family_status" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['middle_class','upper_middle class','rich_affluent'] as $opt)
                            <option value="{{ $opt }}" {{ old('family_status', $p->family_status ?? '')===$opt ? 'selected' : '' }}>{{ ucwords($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Family Type</label>
                    <select name="family_type" class="w-full px-4 py-2 border rounded-md" required>
                        @foreach(['joint_family','nuclear_family'] as $opt)
                            <option value="{{ $opt }}" {{ old('family_type', $p->family_type ?? '')===$opt ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ', $opt)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">About Me</label>
                    <textarea name="about_me" class="w-full px-4 py-2 border rounded-md" rows="3">{{ old('about_me', $p->about_me ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosham</label>
                    <input type="text" name="dosham" value="{{ old('dosham', $p->dosham ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Star/Nakshatram</label>
                    <select name="star_nakshatram" class="w-full px-4 py-2 border rounded-md">
                        <option value="">Select Star</option>
                        @foreach($stars as $star)
                            <option value="{{ $star }}" {{ old('star_nakshatram', $p->star_nakshatram ?? '')===$star ? 'selected' : '' }}>{{ $star }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rasi</label>
                    <select name="rasi" class="w-full px-4 py-2 border rounded-md">
                        <option value="">Select Rasi</option>
                        @foreach($rasis as $rasi)
                            <option value="{{ $rasi }}" {{ old('rasi', $p->rasi ?? '')===$rasi ? 'selected' : '' }}>{{ $rasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gothram</label>
                    <input type="text" name="gothram" value="{{ old('gothram', $p->gothram ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time of Birth</label>
                    <input type="time" name="time_of_birth" value="{{ old('time_of_birth', $p->time_of_birth ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country of Birth</label>
                    <input type="text" name="country_of_birth" value="{{ old('country_of_birth', $p->country_of_birth ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">State of Birth</label>
                    <input type="text" name="state_of_birth" value="{{ old('state_of_birth', $p->state_of_birth ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City of Birth</label>
                    <input type="text" name="city_of_birth" value="{{ old('city_of_birth', $p->city_of_birth ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Horoscope Chart Style</label>
                    <input type="text" name="horoscope_chart_style" value="{{ old('horoscope_chart_style', $p->horoscope_chart_style ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
            </div>
        </div>

        <!-- Hobbies & Interests Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hobbies & Interests</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hobbies & Interests</label>
                    <textarea name="hobbies_and_interests" class="w-full px-4 py-2 border rounded-md" rows="3" placeholder="e.g., Music, Sports, Food, Reading, Travel">{{ old('hobbies_and_interests', $p->hobbies_and_interests ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Music</label>
                    <textarea name="music" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Classical, Rock, Pop">{{ old('music', $p->music ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sports</label>
                    <textarea name="sports" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Cricket, Football, Badminton">{{ old('sports', $p->sports ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Food</label>
                    <textarea name="food" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="e.g., Indian, Chinese, Italian">{{ old('food', $p->food ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Family Details Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Family Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Father's Occupation</label>
                    <input type="text" name="father_occupation" value="{{ old('father_occupation', $p->father_occupation ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Occupation</label>
                    <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $p->mother_occupation ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. of Brothers</label>
                    <input type="number" name="no_of_brothers" value="{{ old('no_of_brothers', $p->no_of_brothers ?? '') }}" class="w-full px-4 py-2 border rounded-md" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. of Sisters</label>
                    <input type="number" name="no_of_sisters" value="{{ old('no_of_sisters', $p->no_of_sisters ?? '') }}" class="w-full px-4 py-2 border rounded-md" min="0">
                </div>
            </div>
        </div>

        <!-- Partner Preference Section -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Partner Preference</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Age Min</label>
                    <input type="text" name="preferred_age_min" value="{{ old('preferred_age_min', $p->preferred_age_min ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Age Max</label>
                    <input type="text" name="preferred_age_max" value="{{ old('preferred_age_max', $p->preferred_age_max ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Height Min</label>
                    <input type="text" name="preferred_height_min" value="{{ old('preferred_height_min', $p->preferred_height_min ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Height Max</label>
                    <input type="text" name="preferred_height_max" value="{{ old('preferred_height_max', $p->preferred_height_max ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Marital Status</label>
                    <input type="text" name="preferred_marital_status" value="{{ old('preferred_marital_status', $p->preferred_marital_status ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Physical Status</label>
                    <input type="text" name="preferred_physical_status" value="{{ old('preferred_physical_status', $p->preferred_physical_status ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Mother Tongue</label>
                    <input type="text" name="preferred_mother_tongue" value="{{ old('preferred_mother_tongue', $p->preferred_mother_tongue ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Subcaste</label>
                    <input type="text" name="preferred_subcaste" value="{{ old('preferred_subcaste', $p->preferred_subcaste ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Chevvai Dosham</label>
                    <input type="text" name="preferred_chevvai_dosham" value="{{ old('preferred_chevvai_dosham', $p->preferred_chevvai_dosham ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Education</label>
                    <input type="text" name="preferred_education" value="{{ old('preferred_education', $p->preferred_education ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Employed In</label>
                    <input type="text" name="preferred_employed_in" value="{{ old('preferred_employed_in', $p->preferred_employed_in ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Occupation</label>
                    <input type="text" name="preferred_occupation" value="{{ old('preferred_occupation', $p->preferred_occupation ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Annual Income Min</label>
                    <input type="text" name="preferred_annual_income_min" value="{{ old('preferred_annual_income_min', $p->preferred_annual_income_min ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Annual Income Max</label>
                    <input type="text" name="preferred_annual_income_max" value="{{ old('preferred_annual_income_max', $p->preferred_annual_income_max ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Country</label>
                    <input type="text" name="preferred_country" value="{{ old('preferred_country', $p->preferred_country ?? '') }}" class="w-full px-4 py-2 border rounded-md">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Images</h3>
            <input type="file" name="images[]" accept="image/*" multiple class="block w-full text-sm text-gray-700">
            <p class="text-xs text-gray-500 mt-2">You can upload multiple images. Max 2MB each.</p>

            @if($user->profile && $user->profile->images && $user->profile->images->count())
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    @foreach($user->profile->images as $img)
                        <div class="border rounded p-2 flex flex-col items-center">
                            <img src="{{ asset('storage/'.$img->img_path) }}" alt="Profile Image" class="w-full h-32 object-cover rounded">
                            <form method="POST" action="{{ route('admin.users.images.delete', $img->id) }}" onsubmit="return confirm('Delete this image?');" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.users') }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update User</button>
        </div>
    </form>
</div>
@endsection