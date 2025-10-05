# Matrimony Application API Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
Currently, the API endpoints are not protected by authentication middleware. All endpoints are publicly accessible.

## Response Format
All API responses follow a consistent format:

### Success Response
```json
{
    "success": true,
    "data": {},
    "message": "Success message"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "data": {}
}
```

---

## 1. Authentication & Registration

### 1.1 User Registration
**Endpoint:** `POST /register`

**Description:** Register a new user account with complete profile information

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "mobile": "9876543210",
    "profile_created_by": "self",
    "gender": "male",
    "dob": "1990-01-01",
    "mother_tongue": "English",
    "subcaste": "Brahmin",
    "sub_caste_details": "Iyer",
    "willing_to_marry_from_subcaste": "yes",
    "marital_status": "Unmarried",
    "country_living_in": "India",
    "residing_state": "Tamil Nadu",
    "residing_city": "Chennai",
    "citizenship": "Indian",
    "height": "5'8\"",
    "education": "Bachelor's Degree",
    "employed_in": "Private",
    "occupation": "Software Engineer",
    "annual_income": "500000",
    "physical_status": "normal",
    "family_status": "middle_class",
    "family_type": "nuclear_family",
    "about_me": "I am a software engineer looking for a life partner",
    "dosham": "no",
    "star_nakshatram": "Rohini",
    "rasi": "Taurus",
    "gothram": "Bharadwaja",
    "time_of_birth": "10:30",
    "country_of_birth": "India",
    "state_of_birth": "Tamil Nadu",
    "city_of_birth": "Chennai",
    "horoscope_chart_style": "North Indian",
    "education_category": "Engineering",
    "habit": "Non-smoker",
    "isEligible": true,
    "income": "500000",
    "father_occupation": "Engineer",
    "mother_occupation": "Teacher",
    "no_of_brothers": 1,
    "no_of_sisters": 0,
    "preferred_age_min": "25",
    "preferred_age_max": "35",
    "preferred_height_min": "5'4\"",
    "preferred_height_max": "5'8\"",
    "preferred_marital_status": "Unmarried",
    "preferred_physical_status": "normal",
    "preferred_mother_tongue": "English",
    "preferred_subcaste": "Brahmin",
    "preferred_chevvai_dosham": "no",
    "preferred_education": "Bachelor's Degree",
    "preferred_employed_in": "Private",
    "preferred_occupation": "Software Engineer",
    "preferred_annual_income_min": "300000",
    "preferred_annual_income_max": "800000",
    "preferred_country": "India",
    "preferred_citizenship": "Indian",
    "eating_habit": "Vegetarian",
    "drinking_habit": "Non-drinker",
    "smoking_habit": "Non-smoker",
    "hobbies_and_interests": "Music, Sports, Food, Reading, Travel",
    "music": "Classical, Rock, Pop",
    "sports": "Cricket, Football, Badminton",
    "food": "Indian, Chinese, Italian",
    "preferred_eating_habit": "Vegetarian",
    "preferred_drinking_habit": "Non-drinker",
    "preferred_smoking_habit": "Non-smoker",
    "preferred_hobbies_and_interests": "Music, Sports, Reading, Travel",
    "preferred_music": "Classical, Rock, Pop",
    "preferred_sports": "Cricket, Football, Badminton",
    "preferred_food": "Indian, Chinese, Italian"
}
```

**Validation Rules:**
- `name`: required
- `email`: required|email|unique:users,email
- `password`: required
- `mobile`: required|string|max:15|unique:users,mobile
- `profile_created_by`: required|in:self,parent,sibling,relative,friend
- `gender`: required|in:male,female
- `dob`: required|date
- `mother_tongue`: required|string|max:255
- `subcaste`: nullable|string|max:255
- `sub_caste_details`: nullable|string|max:255
- `willing_to_marry_from_subcaste`: required|in:yes,no
- `marital_status`: required|in:Unmarried,Widower,Divorced,Separated
- `country_living_in`: required|string|max:255
- `residing_state`: required|string|max:255
- `residing_city`: required|string|max:255
- `citizenship`: required|string|max:255
- `height`: required|string|max:255
- `education`: required|string|max:255
- `employed_in`: nullable|string|max:255
- `occupation`: nullable|string|max:255
- `annual_income`: nullable|string|max:255
- `physical_status`: required|in:normal,physically_challenged
- `family_status`: required|in:middle_class,upper_middle_class,rich_affluent
- `family_type`: required|in:joint_family,nuclear_family
- `about_me`: nullable|string
- `dosham`: required|in:yes,no,donot_know
- `star_nakshatram`: nullable|string|max:255
- `rasi`: nullable|string|max:255
- `gothram`: nullable|string|max:255
- `time_of_birth`: nullable|date_format:H:i
- `country_of_birth`: nullable|string|max:255
- `state_of_birth`: nullable|string|max:255
- `city_of_birth`: nullable|string|max:255
- `horoscope_chart_style`: nullable|string|max:255
- `education_category`: nullable|string|max:255
- `habit`: nullable|string|max:255
- `isEligible`: nullable|boolean
- `income`: nullable|string|max:255
- `father_occupation`: nullable|string|max:255
- `mother_occupation`: nullable|string|max:255
- `no_of_brothers`: nullable|integer|min:0
- `no_of_sisters`: nullable|integer|min:0
- `preferred_age_min`: nullable|string|max:255
- `preferred_age_max`: nullable|string|max:255
- `preferred_height_min`: nullable|string|max:255
- `preferred_height_max`: nullable|string|max:255
- `preferred_marital_status`: nullable|string|max:255
- `preferred_physical_status`: nullable|string|max:255
- `preferred_mother_tongue`: nullable|string|max:255
- `preferred_subcaste`: nullable|string|max:255
- `preferred_chevvai_dosham`: nullable|string|max:255
- `preferred_education`: nullable|string|max:255
- `preferred_employed_in`: nullable|string|max:255
- `preferred_occupation`: nullable|string|max:255
- `preferred_annual_income_min`: nullable|string|max:255
- `preferred_annual_income_max`: nullable|string|max:255
- `preferred_country`: nullable|string|max:255
- `preferred_citizenship`: nullable|string|max:255
- `eating_habit`: nullable|string|max:255
- `drinking_habit`: nullable|string|max:255
- `smoking_habit`: nullable|string|max:255
- `hobbies_and_interests`: nullable|string
- `music`: nullable|string
- `sports`: nullable|string
- `food`: nullable|string
- `preferred_eating_habit`: nullable|string|max:255
- `preferred_drinking_habit`: nullable|string|max:255
- `preferred_smoking_habit`: nullable|string|max:255
- `preferred_hobbies_and_interests`: nullable|string
- `preferred_music`: nullable|string
- `preferred_sports`: nullable|string
- `preferred_food`: nullable|string

**Response:**
```json
{
    "success": true,
    "data": {
        "token": "1|abc123def456...",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "mobile": "9876543210",
            "m_id": "M123456",
            "profile": {
                "id": 1,
                "user_id": 1,
                "name": "John Doe",
                "gender": "male",
                "dob": "1990-01-01",
                "height": "5'8\"",
                "education": "Bachelor's Degree",
                "occupation": "Software Engineer"
            },
            "images": []
        },
        "list": []
    },
    "message": "User register successfully"
}
```

### 1.2 User Login
**Endpoint:** `POST /login`

**Description:** Authenticate user using email, mobile, or m_id

**Request Body:**
```json
{
    "value": "john@example.com",
    "password": "password123"
}
```

**Note:** The `value` field can be:
- Email address
- Mobile number
- M_ID (matrimony ID)

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "token": "1|abc123def456...",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "mobile": "9876543210",
            "m_id": "M123456",
            "profile": {
                "id": 1,
                "name": "John Doe",
                "gender": "male",
                "dob": "1990-01-01",
                "height": "5'8\"",
                "education": "Bachelor's Degree",
                "occupation": "Software Engineer"
            },
            "images": []
        },
        "list": []
    },
    "message": "User login successfully"
}
```

**Response (Unauthorized - 401):**
```json
{
    "success": false,
    "message": "Unauthorised",
    "data": {
        "error": "Unauthorised"
    }
}
```

### 1.3 Send SMS OTP
**Endpoint:** `POST /sendSms`

**Description:** Send OTP to user's mobile number

**Request Body:**
```json
{
    "mobile": "9876543210"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "OTP sent successfully",
    "data": {
        "return": true,
        "request_id": "abc123",
        "message": "SMS sent successfully"
    }
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Failed to send OTP",
    "data": {
        "error": "Invalid API Response",
        "full_response": {
            "return": false,
            "message": "Invalid mobile number"
        }
    }
}
```

### 1.4 Verify OTP
**Endpoint:** `POST /verifyOtp`

**Description:** Verify OTP sent to mobile number (OTP expires in 5 minutes)

**Request Body:**
```json
{
    "mobile": "9876543210",
    "otp": "123456"
}
```

**Validation Rules:**
- `mobile`: required|string
- `otp`: required|digits:6

**Response (Success):**
```json
{
    "success": true,
    "data": true,
    "message": "OTP verified successfully"
}
```

**Response (OTP Expired - 400):**
```json
{
    "success": false,
    "message": "OTP expired",
    "data": {
        "error": "The OTP has expired."
    }
}
```

**Response (Invalid OTP - 400):**
```json
{
    "success": false,
    "message": "Invalid OTP or mobile number",
    "data": {
        "error": "The OTP provided is incorrect."
    }
}
```

### 1.5 Check User Existence
**Endpoint:** `POST /checkIsExist`

**Description:** Check if user exists by email or mobile

**Request Body:**
```json
{
    "type": "email",
    "value": "john@example.com"
}
```

**Type Values:**
- `email`: Check by email address
- `mobile`: Check by mobile number

**Response:**
```json
{
    "success": true,
    "data": true,
    "message": "Email exists"
}
```

### 1.6 Update Password
**Endpoint:** `POST /updatePassword`

**Description:** Update user password by mobile number

**Request Body:**
```json
{
    "mobile": "9876543210",
    "password": "newpassword123"
}
```

**Response:**
```json
{
    "success": true,
    "data": true,
    "message": "Password updated successfully"
}
```

### 1.7 Update Profile
**Endpoint:** `POST /updateProfile`

**Description:** Update user profile with new family details and partner preference fields

**Request Body:**
```json
{
    "user_id": 1,
    "name": "John Smith",
    "height": "5'9\"",
    "about_me": "Updated about me section",
    "father_occupation": "Engineer",
    "mother_occupation": "Teacher",
    "no_of_brothers": 1,
    "no_of_sisters": 0,
    "preferred_age_min": "25",
    "preferred_age_max": "35",
    "preferred_height_min": "5'4\"",
    "preferred_height_max": "5'8\"",
    "preferred_marital_status": "Unmarried",
    "preferred_physical_status": "normal",
    "preferred_mother_tongue": "English",
    "preferred_subcaste": "Brahmin",
    "preferred_chevvai_dosham": "no",
    "preferred_education": "Bachelor's Degree",
    "preferred_employed_in": "Private",
    "preferred_occupation": "Software Engineer",
    "preferred_annual_income_min": "300000",
    "preferred_annual_income_max": "800000",
    "preferred_country": "India",
    "preferred_citizenship": "Indian",
    "eating_habit": "Vegetarian",
    "drinking_habit": "Non-drinker",
    "smoking_habit": "Non-smoker",
    "hobbies_and_interests": "Music, Sports, Food, Reading, Travel",
    "music": "Classical, Rock, Pop",
    "sports": "Cricket, Football, Badminton",
    "food": "Indian, Chinese, Italian",
    "preferred_eating_habit": "Vegetarian",
    "preferred_drinking_habit": "Non-drinker",
    "preferred_smoking_habit": "Non-smoker",
    "preferred_hobbies_and_interests": "Music, Sports, Reading, Travel",
    "preferred_music": "Classical, Rock, Pop",
    "preferred_sports": "Cricket, Football, Badminton",
    "preferred_food": "Indian, Chinese, Italian"
}
```

**Validation Rules:**
- `user_id`: required|exists:users,id
- All other fields are optional (nullable)
- Same validation rules as registration for each field

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Smith",
            "email": "john@example.com",
            "mobile": "9876543210",
            "m_id": "M123456",
            "profile": {
                "id": 1,
                "name": "John Smith",
                "height": "5'9\"",
                "about_me": "Updated about me section",
                "father_occupation": "Engineer",
                "mother_occupation": "Teacher",
                "no_of_brothers": 1,
                "no_of_sisters": 0,
                "preferred_age_min": "25",
                "preferred_age_max": "35",
                "preferred_height_min": "5'4\"",
                "preferred_height_max": "5'8\"",
                "preferred_marital_status": "Unmarried",
                "preferred_physical_status": "normal",
                "preferred_mother_tongue": "English",
                "preferred_subcaste": "Brahmin",
                "preferred_chevvai_dosham": "no",
                "preferred_education": "Bachelor's Degree",
                "preferred_employed_in": "Private",
                "preferred_occupation": "Software Engineer",
                "preferred_annual_income_min": "300000",
                "preferred_annual_income_max": "800000",
                "preferred_country": "India",
                "updated_at": "2024-01-01T12:00:00.000000Z"
            },
            "images": []
        }
    },
    "message": "Profile updated successfully"
}
```

**Response (User Not Found - 404):**
```json
{
    "success": false,
    "message": "User not found",
    "data": {
        "error": "User does not exist"
    }
}
```

**Response (Profile Not Found - 404):**
```json
{
    "success": false,
    "message": "Profile not found",
    "data": {
        "error": "Profile does not exist"
    }
}
```

---

## 2. Profile Management

### 2.1 Create Profile
**Endpoint:** `POST /profile`

**Description:** Create user profile (Note: This endpoint is not currently registered in routes)

**Request Body:**
```json
{
    "user_id": 1,
    "profile_created_by": "self",
    "gender": "male",
    "name": "John Doe",
    "dob": "1990-01-01",
    "mother_tongue": "English",
    "subcaste": "Brahmin",
    "sub_caste_details": "Iyer",
    "willing_to_marry_from_subcaste": "yes",
    "marital_status": "Unmarried",
    "country_living_in": "India",
    "residing_state": "Tamil Nadu",
    "residing_city": "Chennai",
    "citizenship": "Indian",
    "height": "5'8\"",
    "education": "Bachelor's Degree",
    "employed_in": "Private",
    "occupation": "Software Engineer",
    "annual_income": "500000",
    "physical_status": "normal",
    "family_status": "middle_class",
    "family_type": "nuclear_family",
    "about_me": "I am a software engineer looking for a life partner",
    "dosham": "no",
    "star_nakshatram": "Rohini",
    "rasi": "Taurus",
    "gothram": "Bharadwaja",
    "time_of_birth": "10:30",
    "country_of_birth": "India",
    "state_of_birth": "Tamil Nadu",
    "city_of_birth": "Chennai",
    "horoscope_chart_style": "North Indian"
}
```

**Response:**
```json
{
    "message": "Profile created successfully",
    "profile": {
        "id": 1,
        "user_id": 1,
        "name": "John Doe",
        "gender": "male",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### 2.2 Update Profile
**Endpoint:** `POST /profile/update`

**Description:** Update existing user profile (Legacy endpoint - use /updateProfile for new fields)

**Request Body:**
```json
{
    "user_id": 1,
    "name": "John Smith",
    "height": "5'9\"",
    "about_me": "Updated about me section"
}
```

**Validation Rules:**
- `user_id`: required|exists:users,id
- `profile_created_by`: sometimes|in:self,parent,sibling,relative,friend
- `gender`: sometimes|in:male,female
- `name`: sometimes|string|max:255
- `dob`: sometimes|date
- `mother_tongue`: sometimes|string|max:255
- `subcaste`: nullable|string|max:255
- `sub_caste_details`: nullable|string|max:255
- `willing_to_marry_from_subcaste`: sometimes|in:yes,no
- `marital_status`: sometimes|in:Unmarried,Widower,Divorced,Separated
- `country_living_in`: sometimes|string|max:255
- `residing_state`: sometimes|string|max:255
- `residing_city`: sometimes|string|max:255
- `citizenship`: sometimes|string|max:255
- `height`: sometimes|string|max:255
- `education`: sometimes|string|max:255
- `employed_in`: nullable|string|max:255
- `occupation`: nullable|string|max:255
- `annual_income`: nullable|string|max:255
- `physical_status`: sometimes|in:normal,physically_challenged
- `family_status`: sometimes|in:middle_class,upper_middle_class,rich_affluent
- `family_type`: sometimes|in:joint_family,nuclear_family
- `about_me`: nullable|string
- `dosham`: sometimes|in:yes,no,donot_know
- `star_nakshatram`: nullable|string|max:255
- `rasi`: nullable|string|max:255
- `gothram`: nullable|string|max:255
- `time_of_birth`: nullable|date_format:H:i
- `country_of_birth`: nullable|string|max:255
- `state_of_birth`: nullable|string|max:255
- `city_of_birth`: nullable|string|max:255
- `horoscope_chart_style`: nullable|string|max:255

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "profile": {
            "id": 1,
            "user_id": 1,
            "name": "John Smith",
            "height": "5'9\"",
            "about_me": "Updated about me section",
            "updated_at": "2024-01-01T12:00:00.000000Z"
        }
    },
    "message": "Profile updated successfully!"
}
```

**Response (User Not Found - 404):**
```json
{
    "success": false,
    "message": "User not found",
    "data": []
}
```

**Response (Profile Not Found - 404):**
```json
{
    "success": false,
    "message": "User profile not found",
    "data": []
}
```

### 2.3 Upload Profile Images
**Endpoint:** `POST /image_upload`

**Description:** Upload profile images

**Request Body:** (multipart/form-data)
```
id: 1
profile_img[]: [file1.jpg, file2.jpg, file3.jpg]
```

**Validation Rules:**
- `id`: required
- `profile_img`: required|array
- `profile_img.*`: image|mimes:jpeg,png,jpg

**Response (Success):**
```json
{
    "success": true,
    "data": [
        "http://your-domain.com/storage/app/public/profile_images/image1.jpg",
        "http://your-domain.com/storage/app/public/profile_images/image2.jpg",
        "http://your-domain.com/storage/app/public/profile_images/image3.jpg"
    ],
    "message": "Profile Images uploaded successfully!"
}
```

**Response (User Profile Not Found - 404):**
```json
{
    "success": false,
    "message": "User profile not found",
    "data": []
}
```

**Response (Invalid File Upload - 404):**
```json
{
    "success": false,
    "message": "Invalid file upload",
    "data": []
}
```

### 2.4 Delete Profile Image
**Endpoint:** `POST /image_delete`

**Description:** Delete a profile image by its ID

**Request Body:**
```json
{
    "image_id": 1
}
```

**Validation Rules:**
- `image_id`: required|integer|exists:profile_img,id

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "deleted": true,
        "image_id": 1,
        "deleted_path": "profile_images/image1.jpg"
    },
    "message": "Profile image deleted successfully!"
}
```

**Response (Image Not Found - 404):**
```json
{
    "success": false,
    "message": "Profile image not found",
    "data": []
}
```

**Response (Validation Error - 422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "data": {
        "image_id": ["The image id field is required."]
    }
}
```

### 2.5 Get User Details
**Endpoint:** `POST /user-details`

**Description:** Get complete user profile with images

**Request Body:**
```json
{
    "user_id": 1
}
```

**Validation Rules:**
- `user_id`: required|exists:users,id

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "mobile": "9876543210",
        "m_id": "M123456",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "profile": {
            "id": 1,
            "profile_created_by": "self",
            "gender": "male",
            "name": "John Doe",
            "dob": "1990-01-01",
            "mother_tongue": "English",
            "subcaste": "Brahmin",
            "sub_caste_details": "Iyer",
            "willing_to_marry_from_subcaste": "yes",
            "marital_status": "Unmarried",
            "country_living_in": "India",
            "residing_state": "Tamil Nadu",
            "residing_city": "Chennai",
            "citizenship": "Indian",
            "height": "5'8\"",
            "education": "Bachelor's Degree",
            "employed_in": "Private",
            "occupation": "Software Engineer",
            "annual_income": "500000",
            "physical_status": "normal",
            "family_status": "middle_class",
            "family_type": "nuclear_family",
            "about_me": "I am a software engineer looking for a life partner",
            "dosham": "no",
            "star_nakshatram": "Rohini",
            "rasi": "Taurus",
            "gothram": "Bharadwaja",
            "time_of_birth": "10:30",
            "country_of_birth": "India",
            "state_of_birth": "Tamil Nadu",
            "city_of_birth": "Chennai",
            "horoscope_chart_style": "North Indian",
            "father_occupation": "Engineer",
            "mother_occupation": "Teacher",
            "no_of_brothers": 1,
            "no_of_sisters": 0,
            "preferred_age_min": "25",
            "preferred_age_max": "35",
            "preferred_height_min": "5'4\"",
            "preferred_height_max": "5'8\"",
            "preferred_marital_status": "Unmarried",
            "preferred_physical_status": "normal",
            "preferred_mother_tongue": "English",
            "preferred_subcaste": "Brahmin",
            "preferred_chevvai_dosham": "no",
            "preferred_education": "Bachelor's Degree",
            "preferred_employed_in": "Private",
            "preferred_occupation": "Software Engineer",
            "preferred_annual_income_min": "300000",
            "preferred_annual_income_max": "800000",
            "preferred_country": "India",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "images": [
            {
                "id": 1,
                "img_path": "profile_images/image1.jpg",
                "full_url": "http://your-domain.com/storage/app/public/profile_images/image1.jpg",
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    },
    "message": "User details retrieved successfully!"
}
```

### 2.6 Delete Account
**Endpoint:** `POST /delete_account`

**Description:** Delete user account permanently

**Request Body:**
```json
{
    "user_id": 1
}
```

**Validation Rules:**
- `user_id`: required|integer|exists:users,id

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "deleted": true,
        "user_id": 1
    },
    "message": "User account deleted successfully!"
}
```

**Response (User Not Found - 404):**
```json
{
    "success": false,
    "message": "User not found",
    "data": []
}
```

---

## 3. Matches & Discovery

### 3.1 Get Matches
**Endpoint:** `GET /matches`

**Description:** Get different types of matches based on criteria

**Query Parameters:**
- `type` (required): Type of matches to retrieve
  - `just_joined`: Recently registered users
  - `matches`: Compatible matches
  - `nearby`: Users from nearby locations
  - `shortlisted`: Users shortlisted by current user
  - `shortlisted_by`: Users who shortlisted current user
  - `interested`: Users current user is interested in
  - `interested_by`: Users interested in current user
- `id` (required): User ID
- `per_page` (optional): Number of results per page (default: 15, max: 100)
- `page` (optional): Page number (default: 1)

**Validation Rules:**
- `type`: required|string|in:just_joined,matches,nearby,shortlisted,shortlisted_by,interested,interested_by
- `id`: required|integer|min:1
- `per_page`: optional (limited between 1-100)
- `page`: optional (minimum 1)

**Example Request:**
```
GET /matches?type=matches&id=1&per_page=10&page=1
```

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 2,
                "name": "Jane Doe",
                "age": 28,
                "height": "5'6\"",
                "education": "Master's Degree",
                "occupation": "Doctor",
                "location": "Chennai, Tamil Nadu",
                "images": [
                    "http://your-domain.com/storage/app/public/profile_images/jane1.jpg"
                ]
            }
        ],
        "total": 50,
        "per_page": 10,
        "last_page": 5
    },
    "message": "Matches retrieved successfully"
}
```

---

## 4. Interest Management

### 4.1 Send/Manage Interest
**Endpoint:** `POST /intrested`

**Description:** Send interest, accept, decline, or reply to interest

**Request Body:**
```json
{
    "sender_id": 1,
    "receiver_id": 2,
    "status": "pending",
    "message": "Hi, I'm interested in knowing you better"
}
```

**Status Values:**
- `pending`: Send new interest
- `accepted`: Accept received interest
- `declined`: Decline received interest
- `replied`: Reply to interest

**Validation Rules:**
- `sender_id`: required (must exist in users table)
- `receiver_id`: required (must exist in users table)
- `status`: required|in:pending,accepted,declined,replied
- `message`: optional|string

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "interest_id": 1,
        "sender_id": 1,
        "receiver_id": 2,
        "status": "pending",
        "message": "Hi, I'm interested in knowing you better",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "message": "Interest sent successfully"
}
```

**Response (Missing Parameters - 400):**
```json
{
    "success": false,
    "message": "Missing required parameters",
    "data": {
        "sender_id": 1,
        "receiver_id": 2,
        "status": "pending"
    }
}
```

**Response (Invalid Status - 400):**
```json
{
    "success": false,
    "message": "Invalid status value",
    "data": {
        "status": "invalid_status",
        "valid_statuses": ["pending", "accepted", "declined", "replied"]
    }
}
```

**Response (User Not Found - 404):**
```json
{
    "success": false,
    "message": "Sender not found",
    "data": {
        "sender_id": 999
    }
}
```

**Response (Self Interest - 400):**
```json
{
    "success": false,
    "message": "Cannot send interest to yourself",
    "data": []
}
```

---

## 5. Shortlist Management

### 5.1 Add/Remove from Shortlist
**Endpoint:** `POST /shortlist`

**Description:** Add or remove user from shortlist

**Request Body:**
```json
{
    "id": 1,
    "shorted_id": 2
}
```

**Validation Rules:**
- `id`: required (must exist in users table)
- `shorted_id`: required (must exist in users table)

**Response (Added):**
```json
{
    "success": true,
    "data": {
        "action": "added",
        "shortlisted": true
    },
    "message": "Profile added to shortlist successfully!"
}
```

**Response (Removed):**
```json
{
    "success": true,
    "data": {
        "action": "removed",
        "shortlisted": false
    },
    "message": "Profile removed from shortlist successfully!"
}
```

**Response (Missing Parameters - 400):**
```json
{
    "success": false,
    "message": "Missing required parameters",
    "data": {
        "id": 1,
        "shorted_id": null
    }
}
```

**Response (User Not Found - 404):**
```json
{
    "success": false,
    "message": "User not found",
    "data": {
        "id": 999
    }
}
```

**Response (Cannot Shortlist Self - 400):**
```json
{
    "success": false,
    "message": "Cannot shortlist yourself",
    "data": []
}
```

---

## 6. Location APIs

### 6.1 Get Countries
**Endpoint:** `GET /countries`

**Description:** Get list of all countries

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "country_id": 1,
            "name": "India"
        },
        {
            "country_id": 2,
            "name": "United States"
        }
    ],
    "message": "Country list retrieved successfully"
}
```

### 6.2 Get States
**Endpoint:** `GET /states/{country_id}`

**Description:** Get states by country ID

**Example:** `GET /states/1`

**Response (Success):**
```json
{
    "success": true,
    "data": [
        {
            "state_id": 1,
            "name": "Tamil Nadu"
        },
        {
            "state_id": 2,
            "name": "Kerala"
        }
    ],
    "message": "State list retrieved successfully"
}
```

**Response (No States Found):**
```json
{
    "success": false,
    "message": "No states found for the selected country",
    "data": []
}
```

### 6.3 Get Cities
**Endpoint:** `GET /cities/{state_id}`

**Description:** Get cities by state ID

**Example:** `GET /cities/1`

**Response (Success):**
```json
{
    "success": true,
    "data": [
        {
            "city_id": 1,
            "name": "Chennai"
        },
        {
            "city_id": 2,
            "name": "Coimbatore"
        }
    ],
    "message": "City list retrieved successfully"
}
```

**Response (No Cities Found):**
```json
{
    "success": false,
    "message": "No cities found for the selected state",
    "data": []
}
```

---

## 7. Occupation APIs

### 7.1 Get All Occupations
**Endpoint:** `GET /occupations`

**Description:** Get list of all occupations

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "category": "IT",
            "occupation_name": "Software Engineer"
        },
        {
            "id": 2,
            "category": "Medical",
            "occupation_name": "Doctor"
        }
    ],
    "message": "All occupations retrieved successfully"
}
```

---

## 8. Education APIs

### 8.1 Get All Education Degrees
**Endpoint:** `GET /education`

**Description:** Get list of all education degrees

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "category": "Engineering",
            "degree_name": "Bachelor of Engineering"
        },
        {
            "id": 2,
            "category": "Medical",
            "degree_name": "MBBS"
        }
    ],
    "message": "All educational qualifications retrieved successfully"
}
```

### 8.2 Get Education Categories
**Endpoint:** `GET /education/categories`

**Description:** Get list of education categories

**Response:**
```json
{
    "success": true,
    "data": [
        "Engineering",
        "Medical",
        "Arts",
        "Commerce"
    ],
    "message": "Education categories retrieved successfully"
}
```

### 8.3 Get Education by Category
**Endpoint:** `GET /education-by-category`

**Description:** Get education degrees by category

**Query Parameters:**
- `category` (required): Education category

**Example:** `GET /education-by-category?category=Engineering`

**Response (Success):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "category": "Engineering",
            "degree_name": "Bachelor of Engineering"
        },
        {
            "id": 2,
            "category": "Engineering",
            "degree_name": "Master of Engineering"
        }
    ],
    "message": "Degrees retrieved successfully"
}
```

**Response (Category Required):**
```json
{
    "success": false,
    "message": "Category is required",
    "data": []
}
```

**Response (No Degrees Found):**
```json
{
    "success": false,
    "message": "No degrees found for the given category",
    "data": []
}
```

---

## 9. Astrology APIs

### 9.1 Get Rasi and Star
**Endpoint:** `GET /rasi`

**Description:** Get list of Rasi and Star

**Response:**
```json
{
    "success": true,
    "data": {
        "rasi": [
            "Aries",
            "Taurus",
            "Gemini"
        ],
        "star": [
            "Ashwini",
            "Bharani",
            "Krittika"
        ]
    },
    "message": "Rasi & star list retrieved successfully"
}
```

### 9.2 Get Star Only
**Endpoint:** `GET /star`

**Description:** Get list of stars only

**Response (Success):**
```json
{
    "success": true,
    "data": [
        "Ashwini",
        "Bharani",
        "Krittika",
        "Rohini"
    ],
    "message": "Star list retrieved successfully"
}
```

**Response (No Stars Found):**
```json
{
    "success": false,
    "message": "No Star records found",
    "data": []
}
```

---

## Error Codes

| HTTP Status | Description |
|-------------|-------------|
| 200 | Success |
| 400 | Bad Request - Invalid parameters |
| 404 | Not Found - Resource not found |
| 422 | Validation Error - Invalid input data |
| 500 | Internal Server Error |

## Common Error Examples

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation Error",
    "data": {
        "email": ["The email field is required."],
        "password": ["The password field is required."],
        "mobile": ["The mobile field must be a string."],
        "gender": ["The selected gender is invalid."]
    }
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "User not found",
    "data": []
}
```

### Bad Request Error (400)
```json
{
    "success": false,
    "message": "Missing required parameters",
    "data": {
        "sender_id": 1,
        "receiver_id": null,
        "status": "pending"
    }
}
```

### Unauthorized Error (401)
```json
{
    "success": false,
    "message": "Unauthorised",
    "data": {
        "error": "Unauthorised"
    }
}
```

### Internal Server Error (500)
```json
{
    "success": false,
    "message": "Error processing request",
    "data": {
        "error": "Database connection failed"
    }
}
```

### File Upload Error (404)
```json
{
    "success": false,
    "message": "Invalid file upload",
    "data": []
}
```

### OTP Related Errors (400)
```json
{
    "success": false,
    "message": "OTP expired",
    "data": {
        "error": "The OTP has expired."
    }
}
```

### Business Logic Errors (400)
```json
{
    "success": false,
    "message": "Cannot send interest to yourself",
    "data": []
}
```

---

## Notes

1. **File Upload**: Profile image uploads support JPEG, PNG, and JPG formats with multipart/form-data
2. **Pagination**: Match endpoints support pagination with configurable page size (1-100 per page)
3. **Data Validation**: All endpoints include comprehensive input validation with Laravel validation rules
4. **Error Handling**: Consistent error response format across all endpoints with proper HTTP status codes
5. **Flexible Input**: Most endpoints accept both JSON and form-data formats
6. **Registration**: The register endpoint creates both user and profile in a single request with database transaction
7. **Login**: Supports login with email, mobile, or m_id (matrimony ID)
8. **OTP**: OTP expires in 5 minutes and is validated with 6-digit format
9. **Authentication**: Currently no authentication middleware is applied (all endpoints are public)
10. **Database Transactions**: Critical operations like registration use database transactions for data integrity
11. **Image Storage**: Profile images are stored in `storage/app/public/profile_images/` directory
12. **SMS Integration**: Uses Fast2SMS API for OTP delivery
13. **Soft Deletes**: User accounts support soft deletion
14. **Interest Management**: Supports multiple interest statuses (pending, accepted, declined, replied)
15. **Shortlist Management**: Toggle functionality to add/remove users from shortlist
16. **Family Details**: New fields for father's occupation, mother's occupation, number of brothers and sisters
17. **Partner Preferences**: Comprehensive preference fields for age, height, marital status, physical status, mother tongue, subcaste, education, employment, occupation, income range, and country
18. **Profile Updates**: Two update endpoints available - legacy `/profile/update` and new `/updateProfile` with all fields

## Testing the API

You can test the API using tools like:
- Postman
- Insomnia
- curl commands
- Any HTTP client

### Example curl commands:

```bash
# Register user with complete profile
curl -X POST http://your-domain.com/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name":"John Doe",
    "email":"john@example.com",
    "password":"password123",
    "mobile":"9876543210",
    "profile_created_by":"self",
    "gender":"male",
    "dob":"1990-01-01",
    "mother_tongue":"English",
    "willing_to_marry_from_subcaste":"yes",
    "marital_status":"Unmarried",
    "country_living_in":"India",
    "residing_state":"Tamil Nadu",
    "residing_city":"Chennai",
    "citizenship":"Indian",
    "height":"5'\''8\"",
    "education":"Bachelor'\''s Degree",
    "physical_status":"normal",
    "family_status":"middle_class",
    "family_type":"nuclear_family",
    "dosham":"no"
  }'

# Login user
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"value":"john@example.com","password":"password123"}'

# Get matches
curl -X GET "http://your-domain.com/api/matches?type=matches&id=1&per_page=10&page=1"

# Upload profile image
curl -X POST http://your-domain.com/api/image_upload \
  -F "id=1" \
  -F "profile_img[]=@/path/to/image1.jpg" \
  -F "profile_img[]=@/path/to/image2.jpg"

# Delete profile image
curl -X POST http://your-domain.com/api/image_delete \
  -H "Content-Type: application/json" \
  -d '{"image_id": 1}'

# Update profile (legacy endpoint)
curl -X POST http://your-domain.com/api/profile/update \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"name":"John Smith","height":"5'\''9\""}'

# Update profile with new fields
curl -X POST http://your-domain.com/api/updateProfile \
  -H "Content-Type: application/json" \
  -d '{
    "user_id":1,
    "name":"John Smith",
    "height":"5'\''9\"",
    "father_occupation":"Engineer",
    "mother_occupation":"Teacher",
    "no_of_brothers":1,
    "no_of_sisters":0,
    "preferred_age_min":"25",
    "preferred_age_max":"35",
    "preferred_height_min":"5'\''4\"",
    "preferred_height_max":"5'\''8\"",
    "preferred_marital_status":"Unmarried",
    "preferred_physical_status":"normal",
    "preferred_mother_tongue":"English",
    "preferred_subcaste":"Brahmin",
    "preferred_chevvai_dosham":"no",
    "preferred_education":"Bachelor'\''s Degree",
    "preferred_employed_in":"Private",
    "preferred_occupation":"Software Engineer",
    "preferred_annual_income_min":"300000",
    "preferred_annual_income_max":"800000",
    "preferred_country":"India",
    "preferred_citizenship":"Indian",
    "eating_habit":"Vegetarian",
    "drinking_habit":"Non-drinker",
    "smoking_habit":"Non-smoker",
    "hobbies_and_interests":"Music, Sports, Food, Reading, Travel",
    "music":"Classical, Rock, Pop",
    "sports":"Cricket, Football, Badminton",
    "food":"Indian, Chinese, Italian"
  }'
```
