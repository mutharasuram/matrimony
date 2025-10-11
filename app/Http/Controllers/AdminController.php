<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Profile;
use App\Models\ProfileImg;
use App\Models\Product;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is admin
            if (!$user->is_admin) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin privileges.',
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {

        // Get statistics for dashboard
        $totalUsers = User::count();
        $totalProfiles = Profile::count();
        $totalProducts = Product::count();
        $recentUsers = User::latest()->take(5)->get();
        $recentProfiles = Profile::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalProfiles', 'totalProducts', 'recentUsers', 'recentProfiles'));
    }

    public function users(Request $request)
    {
        // Account status: active (default), inactive (soft deleted), all (withTrashed)
        $account = $request->get('account');

        $baseQuery = User::query();
        if ($account === 'inactive') {
            $baseQuery = $baseQuery->onlyTrashed();
        } elseif ($account === 'all') {
            $baseQuery = $baseQuery->withTrashed();
        }

        $query = $baseQuery->with('profile');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('m_id', 'like', "%{$search}%");
            });
        }
        
        // Sort functionality (whitelisted columns)
        $allowedSortColumns = ['name', 'email', 'created_at', 'mobile', 'm_id'];
        $sortBy = $request->get('sort_by', 'created_at');
        if (! in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'created_at';
        }
        $sortOrder = $request->get('sort_order', 'desc');
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        $users = $query->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        [$stars, $rasis] = $this->getAstroOptions();
        return view('admin.users-create', compact('stars', 'rasis'));
    }

    public function storeUser(Request $request)
    {
        [$stars, $rasis] = $this->getAstroOptions();

        $validated = $request->validate([
            // user fields
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('users', 'mobile')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
            
            // profile fields
            'profile_created_by' => ['required', Rule::in(['self', 'parent', 'sibling', 'relative', 'friend'])],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'profile_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'mother_tongue' => ['required', 'string', 'max:255'],
            'subcaste' => ['nullable', 'string', 'max:255'],
            'willing_to_marry_from_subcaste' => ['required', Rule::in(['yes', 'no'])],
            'marital_status' => ['required', Rule::in(['Unmarried', 'Widower', 'Divorced', 'Separated'])],
            'country_living_in' => ['required', 'string', 'max:255'],
            'residing_state' => ['required', 'string', 'max:255'],
            'residing_city' => ['required', 'string', 'max:255'],
            'citizenship' => ['required', 'string', 'max:255'],
            'height' => ['required', 'string', 'max:50'],
            'education' => ['required', 'string', 'max:255'],
            'employed_in' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'annual_income' => ['nullable', 'string', 'max:255'],
            'physical_status' => ['required', Rule::in(['normal', 'physically_challenged'])],
            'family_status' => ['required', Rule::in(['poor', 'lower_middle', 'middle_class', 'upper_middle class', 'rich_affluent'])],
            'family_type' => ['required', Rule::in(['joint_family', 'nuclear_family', 'small_family'])],
            'about_me' => ['nullable', 'string'],
            'dosham' => ['nullable', 'string', 'max:50'],
            'star_nakshatram' => ['nullable', Rule::in($stars)],
            'rasi' => ['nullable', Rule::in($rasis)],
            'gothram' => ['nullable', 'string', 'max:255'],
            // accept either HH:MM or HH:MM:SS and normalize later
            'time_of_birth' => ['nullable', 'string'],
            'country_of_birth' => ['nullable', 'string', 'max:255'],
            'state_of_birth' => ['nullable', 'string', 'max:255'],
            'city_of_birth' => ['nullable', 'string', 'max:255'],
            'horoscope_chart_style' => ['nullable', 'string', 'max:255'],
            // Family Details validation rules
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'no_of_brothers' => ['nullable', 'integer', 'min:0'],
            'no_of_sisters' => ['nullable', 'integer', 'min:0'],
            // Partner Preference validation rules
            'preferred_age_min' => ['nullable', 'string', 'max:255'],
            'preferred_age_max' => ['nullable', 'string', 'max:255'],
            'preferred_height_min' => ['nullable', 'string', 'max:255'],
            'preferred_height_max' => ['nullable', 'string', 'max:255'],
            'preferred_marital_status' => ['nullable', 'string', 'max:255'],
            'preferred_physical_status' => ['nullable', 'string', 'max:255'],
            'preferred_mother_tongue' => ['nullable', 'string', 'max:255'],
            'preferred_subcaste' => ['nullable', 'string', 'max:255'],
            'preferred_chevvai_dosham' => ['nullable', 'string', 'max:255'],
            'preferred_education' => ['nullable', 'string', 'max:255'],
            'preferred_employed_in' => ['nullable', 'string', 'max:255'],
            'preferred_occupation' => ['nullable', 'string', 'max:255'],
            'preferred_annual_income_min' => ['nullable', 'string', 'max:255'],
            'preferred_annual_income_max' => ['nullable', 'string', 'max:255'],
            'preferred_country' => ['nullable', 'string', 'max:255'],
            'preferred_citizenship' => ['nullable', 'string', 'max:255'],
            'eating_habit' => ['nullable', 'string', 'max:255'],
            'drinking_habit' => ['nullable', 'string', 'max:255'],
            'smoking_habit' => ['nullable', 'string', 'max:255'],
            'hobbies_and_interests' => ['nullable', 'string'],
            'music' => ['nullable', 'string'],
            'sports' => ['nullable', 'string'],
            'food' => ['nullable', 'string'],
            // New preference fields
            'preferred_eating_habit' => ['nullable', 'string', 'max:255'],
            'preferred_drinking_habit' => ['nullable', 'string', 'max:255'],
            'preferred_smoking_habit' => ['nullable', 'string', 'max:255'],
            'preferred_hobbies_and_interests' => ['nullable', 'string'],
            'preferred_music' => ['nullable', 'string'],
            'preferred_sports' => ['nullable', 'string'],
            'preferred_food' => ['nullable', 'string'],
            // New fields from migration
            'about_my_family' => ['nullable', 'string', 'max:255'],
            'fewlines_about_my_partner' => ['nullable', 'string', 'max:255'],

            // images
            'images.*' => ['nullable', 'image', 'max:2048'],
        ]);

        // Normalize time_of_birth to HH:MM if present
        $timeOfBirth = $request->input('time_of_birth');
        if (! empty($timeOfBirth)) {
            $parsed = \DateTime::createFromFormat('H:i', $timeOfBirth) ?: \DateTime::createFromFormat('H:i:s', $timeOfBirth);
            if (! $parsed) {
                return back()->withErrors(['time_of_birth' => 'The time of birth must be in format HH:MM.'])->withInput();
            }
            $timeOfBirth = $parsed->format('H:i');
        } else {
            $timeOfBirth = null;
        }

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->mobile = $validated['mobile'];
        $user->m_id = User::generateUniqueMId();
        $user->password = Hash::make($validated['password']);
        $user->is_admin = (bool)($validated['is_admin'] ?? false);
        $user->save();

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->profile_created_by = $validated['profile_created_by'];
        $profile->gender = $validated['gender'];
        $profile->name = $validated['profile_name'];
        $profile->dob = $validated['dob'];
        $profile->mother_tongue = $validated['mother_tongue'];
        $profile->subcaste = $validated['subcaste'] ?? null;
        $profile->willing_to_marry_from_subcaste = $validated['willing_to_marry_from_subcaste'];
        $profile->marital_status = $validated['marital_status'];
        $profile->country_living_in = $validated['country_living_in'];
        $profile->residing_state = $validated['residing_state'];
        $profile->residing_city = $validated['residing_city'];
        $profile->citizenship = $validated['citizenship'];
        $profile->height = $validated['height'];
        $profile->education = $validated['education'];
        $profile->employed_in = $validated['employed_in'] ?? null;
        $profile->occupation = $validated['occupation'] ?? null;
        $profile->annual_income = $validated['annual_income'] ?? null;
        $profile->physical_status = $validated['physical_status'];
        $profile->family_status = $validated['family_status'];
        $profile->family_type = $validated['family_type'];
        $profile->about_me = $validated['about_me'] ?? null;
        $profile->dosham = $validated['dosham'] ?? null;
        $profile->star_nakshatram = $validated['star_nakshatram'] ?? null;
        $profile->rasi = $validated['rasi'] ?? null;
        $profile->gothram = $validated['gothram'] ?? null;
        $profile->time_of_birth = $timeOfBirth;
        $profile->country_of_birth = $validated['country_of_birth'] ?? null;
        $profile->state_of_birth = $validated['state_of_birth'] ?? null;
        $profile->city_of_birth = $validated['city_of_birth'] ?? null;
        $profile->horoscope_chart_style = $validated['horoscope_chart_style'] ?? null;
        // Family Details fields
        $profile->father_occupation = $validated['father_occupation'] ?? null;
        $profile->mother_occupation = $validated['mother_occupation'] ?? null;
        $profile->no_of_brothers = $validated['no_of_brothers'] ?? null;
        $profile->no_of_sisters = $validated['no_of_sisters'] ?? null;
        // Partner Preference fields
        $profile->preferred_age_min = $validated['preferred_age_min'] ?? null;
        $profile->preferred_age_max = $validated['preferred_age_max'] ?? null;
        $profile->preferred_height_min = $validated['preferred_height_min'] ?? null;
        $profile->preferred_height_max = $validated['preferred_height_max'] ?? null;
        $profile->preferred_marital_status = $validated['preferred_marital_status'] ?? null;
        $profile->preferred_physical_status = $validated['preferred_physical_status'] ?? null;
        $profile->preferred_mother_tongue = $validated['preferred_mother_tongue'] ?? null;
        $profile->preferred_subcaste = $validated['preferred_subcaste'] ?? null;
        $profile->preferred_chevvai_dosham = $validated['preferred_chevvai_dosham'] ?? null;
        $profile->preferred_education = $validated['preferred_education'] ?? null;
        $profile->preferred_employed_in = $validated['preferred_employed_in'] ?? null;
        $profile->preferred_occupation = $validated['preferred_occupation'] ?? null;
        $profile->preferred_annual_income_min = $validated['preferred_annual_income_min'] ?? null;
        $profile->preferred_annual_income_max = $validated['preferred_annual_income_max'] ?? null;
        $profile->preferred_country = $validated['preferred_country'] ?? null;
        $profile->preferred_citizenship = $validated['preferred_citizenship'] ?? null;
        $profile->eating_habit = $validated['eating_habit'] ?? null;
        $profile->drinking_habit = $validated['drinking_habit'] ?? null;
        $profile->smoking_habit = $validated['smoking_habit'] ?? null;
        $profile->hobbies_and_interests = $validated['hobbies_and_interests'] ?? null;
        $profile->music = $validated['music'] ?? null;
        $profile->sports = $validated['sports'] ?? null;
        $profile->food = $validated['food'] ?? null;
        // New preference fields
        $profile->preferred_eating_habit = $validated['preferred_eating_habit'] ?? null;
        $profile->preferred_drinking_habit = $validated['preferred_drinking_habit'] ?? null;
        $profile->preferred_smoking_habit = $validated['preferred_smoking_habit'] ?? null;
        $profile->preferred_hobbies_and_interests = $validated['preferred_hobbies_and_interests'] ?? null;
        $profile->preferred_music = $validated['preferred_music'] ?? null;
        $profile->preferred_sports = $validated['preferred_sports'] ?? null;
        $profile->preferred_food = $validated['preferred_food'] ?? null;
        // New fields from migration
        $profile->about_my_family = $validated['about_my_family'] ?? null;
        $profile->fewlines_about_my_partner = $validated['fewlines_about_my_partner'] ?? null;
        $profile->save();

        // Handle images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('profile_images', 'public');
                ProfileImg::create([
                    'profile_id' => $profile->id,
                    'img_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(int $id)
    {
        [$stars, $rasis] = $this->getAstroOptions();
        $user = User::with(['profile', 'profile.images'])->withTrashed()->findOrFail($id);
        return view('admin.users-edit', compact('user', 'stars', 'rasis'));
    }

    public function updateUser(Request $request, int $id)
    {
        [$stars, $rasis] = $this->getAstroOptions();
        $user = User::with('profile')->withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('users', 'mobile')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],

            'profile_created_by' => ['required', Rule::in(['self', 'parent', 'sibling', 'relative', 'friend'])],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'profile_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'mother_tongue' => ['required', 'string', 'max:255'],
            'subcaste' => ['nullable', 'string', 'max:255'],
            'willing_to_marry_from_subcaste' => ['required', Rule::in(['yes', 'no'])],
            'marital_status' => ['required', Rule::in(['Unmarried', 'Widower', 'Divorced', 'Separated'])],
            'country_living_in' => ['required', 'string', 'max:255'],
            'residing_state' => ['required', 'string', 'max:255'],
            'residing_city' => ['required', 'string', 'max:255'],
            'citizenship' => ['required', 'string', 'max:255'],
            'height' => ['required', 'string', 'max:50'],
            'education' => ['required', 'string', 'max:255'],
            'employed_in' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'annual_income' => ['nullable', 'string', 'max:255'],
            'physical_status' => ['required', Rule::in(['normal', 'physically_challenged'])],
            'family_status' => ['required', Rule::in(['poor', 'lower_middle', 'middle_class', 'upper_middle class', 'rich_affluent'])],
            'family_type' => ['required', Rule::in(['joint_family', 'nuclear_family', 'small_family'])],
            'about_me' => ['nullable', 'string'],
            'dosham' => ['nullable', 'string', 'max:50'],
            'star_nakshatram' => ['nullable', Rule::in($stars)],
            'rasi' => ['nullable', Rule::in($rasis)],
            'gothram' => ['nullable', 'string', 'max:255'],
            // accept either HH:MM or HH:MM:SS and normalize later
            'time_of_birth' => ['nullable', 'string'],
            'country_of_birth' => ['nullable', 'string', 'max:255'],
            'state_of_birth' => ['nullable', 'string', 'max:255'],
            'city_of_birth' => ['nullable', 'string', 'max:255'],
            'horoscope_chart_style' => ['nullable', 'string', 'max:255'],
            // Family Details validation rules
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'no_of_brothers' => ['nullable', 'integer', 'min:0'],
            'no_of_sisters' => ['nullable', 'integer', 'min:0'],
            // Partner Preference validation rules
            'preferred_age_min' => ['nullable', 'string', 'max:255'],
            'preferred_age_max' => ['nullable', 'string', 'max:255'],
            'preferred_height_min' => ['nullable', 'string', 'max:255'],
            'preferred_height_max' => ['nullable', 'string', 'max:255'],
            'preferred_marital_status' => ['nullable', 'string', 'max:255'],
            'preferred_physical_status' => ['nullable', 'string', 'max:255'],
            'preferred_mother_tongue' => ['nullable', 'string', 'max:255'],
            'preferred_subcaste' => ['nullable', 'string', 'max:255'],
            'preferred_chevvai_dosham' => ['nullable', 'string', 'max:255'],
            'preferred_education' => ['nullable', 'string', 'max:255'],
            'preferred_employed_in' => ['nullable', 'string', 'max:255'],
            'preferred_occupation' => ['nullable', 'string', 'max:255'],
            'preferred_annual_income_min' => ['nullable', 'string', 'max:255'],
            'preferred_annual_income_max' => ['nullable', 'string', 'max:255'],
            'preferred_country' => ['nullable', 'string', 'max:255'],
            'preferred_citizenship' => ['nullable', 'string', 'max:255'],
            'eating_habit' => ['nullable', 'string', 'max:255'],
            'drinking_habit' => ['nullable', 'string', 'max:255'],
            'smoking_habit' => ['nullable', 'string', 'max:255'],
            'hobbies_and_interests' => ['nullable', 'string'],
            'music' => ['nullable', 'string'],
            'sports' => ['nullable', 'string'],
            'food' => ['nullable', 'string'],
            // New preference fields
            'preferred_eating_habit' => ['nullable', 'string', 'max:255'],
            'preferred_drinking_habit' => ['nullable', 'string', 'max:255'],
            'preferred_smoking_habit' => ['nullable', 'string', 'max:255'],
            'preferred_hobbies_and_interests' => ['nullable', 'string'],
            'preferred_music' => ['nullable', 'string'],
            'preferred_sports' => ['nullable', 'string'],
            'preferred_food' => ['nullable', 'string'],
            // New fields from migration
            'about_my_family' => ['nullable', 'string', 'max:255'],
            'fewlines_about_my_partner' => ['nullable', 'string', 'max:255'],

            // images
            'images.*' => ['nullable', 'image', 'max:2048'],
        ]);

        // Normalize time_of_birth to HH:MM if present
        $timeOfBirth = $request->input('time_of_birth');
        if (! empty($timeOfBirth)) {
            $parsed = \DateTime::createFromFormat('H:i', $timeOfBirth) ?: \DateTime::createFromFormat('H:i:s', $timeOfBirth);
            if (! $parsed) {
                return back()->withErrors(['time_of_birth' => 'The time of birth must be in format HH:MM.'])->withInput();
            }
            $timeOfBirth = $parsed->format('H:i');
        } else {
            $timeOfBirth = null;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->mobile = $validated['mobile'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->is_admin = (bool)($validated['is_admin'] ?? false);
        $user->save();

        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $profile->profile_created_by = $validated['profile_created_by'];
        $profile->gender = $validated['gender'];
        $profile->name = $validated['profile_name'];
        $profile->dob = $validated['dob'];
        $profile->mother_tongue = $validated['mother_tongue'];
        $profile->subcaste = $validated['subcaste'] ?? null;
        $profile->willing_to_marry_from_subcaste = $validated['willing_to_marry_from_subcaste'];
        $profile->marital_status = $validated['marital_status'];
        $profile->country_living_in = $validated['country_living_in'];
        $profile->residing_state = $validated['residing_state'];
        $profile->residing_city = $validated['residing_city'];
        $profile->citizenship = $validated['citizenship'];
        $profile->height = $validated['height'];
        $profile->education = $validated['education'];
        $profile->employed_in = $validated['employed_in'] ?? null;
        $profile->occupation = $validated['occupation'] ?? null;
        $profile->annual_income = $validated['annual_income'] ?? null;
        $profile->physical_status = $validated['physical_status'];
        $profile->family_status = $validated['family_status'];
        $profile->family_type = $validated['family_type'];
        $profile->about_me = $validated['about_me'] ?? null;
        $profile->dosham = $validated['dosham'] ?? null;
        $profile->star_nakshatram = $validated['star_nakshatram'] ?? null;
        $profile->rasi = $validated['rasi'] ?? null;
        $profile->gothram = $validated['gothram'] ?? null;
        $profile->time_of_birth = $timeOfBirth;
        $profile->country_of_birth = $validated['country_of_birth'] ?? null;
        $profile->state_of_birth = $validated['state_of_birth'] ?? null;
        $profile->city_of_birth = $validated['city_of_birth'] ?? null;
        $profile->horoscope_chart_style = $validated['horoscope_chart_style'] ?? null;
        // Family Details fields
        $profile->father_occupation = $validated['father_occupation'] ?? null;
        $profile->mother_occupation = $validated['mother_occupation'] ?? null;
        $profile->no_of_brothers = $validated['no_of_brothers'] ?? null;
        $profile->no_of_sisters = $validated['no_of_sisters'] ?? null;
        // Partner Preference fields
        $profile->preferred_age_min = $validated['preferred_age_min'] ?? null;
        $profile->preferred_age_max = $validated['preferred_age_max'] ?? null;
        $profile->preferred_height_min = $validated['preferred_height_min'] ?? null;
        $profile->preferred_height_max = $validated['preferred_height_max'] ?? null;
        $profile->preferred_marital_status = $validated['preferred_marital_status'] ?? null;
        $profile->preferred_physical_status = $validated['preferred_physical_status'] ?? null;
        $profile->preferred_mother_tongue = $validated['preferred_mother_tongue'] ?? null;
        $profile->preferred_subcaste = $validated['preferred_subcaste'] ?? null;
        $profile->preferred_chevvai_dosham = $validated['preferred_chevvai_dosham'] ?? null;
        $profile->preferred_education = $validated['preferred_education'] ?? null;
        $profile->preferred_employed_in = $validated['preferred_employed_in'] ?? null;
        $profile->preferred_occupation = $validated['preferred_occupation'] ?? null;
        $profile->preferred_annual_income_min = $validated['preferred_annual_income_min'] ?? null;
        $profile->preferred_annual_income_max = $validated['preferred_annual_income_max'] ?? null;
        $profile->preferred_country = $validated['preferred_country'] ?? null;
        $profile->preferred_citizenship = $validated['preferred_citizenship'] ?? null;
        $profile->eating_habit = $validated['eating_habit'] ?? null;
        $profile->drinking_habit = $validated['drinking_habit'] ?? null;
        $profile->smoking_habit = $validated['smoking_habit'] ?? null;
        $profile->hobbies_and_interests = $validated['hobbies_and_interests'] ?? null;
        $profile->music = $validated['music'] ?? null;
        $profile->sports = $validated['sports'] ?? null;
        $profile->food = $validated['food'] ?? null;
        // New preference fields
        $profile->preferred_eating_habit = $validated['preferred_eating_habit'] ?? null;
        $profile->preferred_drinking_habit = $validated['preferred_drinking_habit'] ?? null;
        $profile->preferred_smoking_habit = $validated['preferred_smoking_habit'] ?? null;
        $profile->preferred_hobbies_and_interests = $validated['preferred_hobbies_and_interests'] ?? null;
        $profile->preferred_music = $validated['preferred_music'] ?? null;
        $profile->preferred_sports = $validated['preferred_sports'] ?? null;
        $profile->preferred_food = $validated['preferred_food'] ?? null;
        // New fields from migration
        $profile->about_my_family = $validated['about_my_family'] ?? null;
        $profile->fewlines_about_my_partner = $validated['fewlines_about_my_partner'] ?? null;
        $profile->user()->associate($user);
        $profile->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('profile_images', 'public');
                ProfileImg::create([
                    'profile_id' => $profile->id,
                    'img_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.users.edit', $user->id)->with('success', 'User updated successfully.');
    }

    public function deleteUserImage(int $imageId)
    {
        $image = ProfileImg::findOrFail($imageId);
        // Remove file from storage if exists
        if ($image->img_path && Storage::disk('public')->exists($image->img_path)) {
            Storage::disk('public')->delete($image->img_path);
        }
        $image->delete();

        return back()->with('success', 'Image removed successfully.');
    }

    public function deactivateUser(Request $request, int $id)
    {
        $authUser = $request->user();
        if ($authUser && (int)$authUser->id === (int)$id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user = User::find($id);
        if (! $user) {
            return back()->with('error', 'User not found or already deactivated.');
        }

        $user->delete();

        return back()->with('success', 'User has been deactivated.');
    }

    public function activateUser(Request $request, int $id)
    {
        $user = User::withTrashed()->find($id);
        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if (! $user->trashed()) {
            return back()->with('success', 'User is already active.');
        }

        $user->restore();

        return back()->with('success', 'User has been reactivated.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    protected function getAstroOptions(): array
    {
        $stars = [
            'Ashwini','Bharani','Krittika','Rohini','Mrigashira','Ardra','Punarvasu','Pushya','Ashlesha','Magha','Purva Phalguni','Uttara Phalguni','Hasta','Chitra','Swati','Vishakha','Anuradha','Jyeshta','Mula','Purva Ashadha','Uttara Ashadha','Shravana','Dhanishta','Shatabhisha','Purva Bhadrapada','Uttara Bhadrapada','Revati'
        ];
        $rasis = [
            'Mesha (Aries)','Vrishabha (Taurus)','Mithuna (Gemini)','Karka (Cancer)','Simha (Leo)','Kanya (Virgo)','Tula (Libra)','Vrishchika (Scorpio)','Dhanu (Sagittarius)','Makara (Capricorn)','Kumbha (Aquarius)','Meena (Pisces)'
        ];
        return [$stars, $rasis];
    }
} 