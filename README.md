/php_code_samples
│
├── /app
│   ├── /Constants                                  # constants
│   │   └── Rule.php
│   ├── /Http
│   │   │    ├──/Controllers                           # Controller files
│   │   │    │   /api
│   │   │    │   │   /v1
│   │   │    │   │   ├── AttendanceController.php
│   │   │    │   │   ├── BillsController.php
│   │   │    │   │   ├── EmployeesController.php
│   │   │    │   │   └── ProofOfWork.php                                 
│   │   │    └── Controller.php                            
│   │   │
│   │   ├── /Middleware                             # Middleware files
│   │   │   ├── Authenticate.php
│   │   │   └── TrimStrings.php                                 
│   │   │
│   │   ├── /Helpers                                # helper files
│   │   │   ├── JwtHelper.php
│   │   │   ├── ResponseHelper.php
│   │   │   └── ValidtorHelper.php                                 
│   │   │
│   │   ├── /Requests                               # Requests/validation files
│   │   │   ├── BillDeleteRequest.php
│   │   │   ├── BillStoreRequest.php
│   │   │   ├── EmployeesRequest.php
│   │   │   ├── MarkAttendanceRequest.php
│   │   │   ├── ProofOfWorkStoreRequest.php
│   │   │   ├── RecordAttendanceRequest.php
│   │   │   └── UpdateAttendanceRequest.php                                 
│   │   │
│   │   ├── /Responses                              # Responses helper files
│   │   │   ├── Message.php
│   │   │   └── ResponseCode.php                                 
│   │   │
│   │   ├── /models                           # Model files
│   │   │   ├── Attendance.php                      
│   │   │   ├── Bills.php
│   │   │   ├── Employee.php
│   │   │   ├── ProofOfWork.php                        
│   │   │   └── User.php                       
│
│
├── /resourcs                                
│   │   ├── /js                                      
│   │   │   ├── /components                          # JS compnents files
│   │   │   │   ├── CourseCard.jsx
│   │   │   │   ├── Example.jss                      
│   │   │   │   ├── excelExport.jss                                     
│   │   │   │   └── textField.jss             
│   │   │   ├── /ReactJS                          # JS compnents files
│   │   │   │   ├── auth.js
│   │   │   │   └── CoursePage.jss
│   │   │   ├── bootstrap.js
│   │   │   └── app.js
│   │   ├── /views                                     
│   │   │   ├── welcome.blade.php
│   │   │   └── student.blade.php
│
├── /database
│   │   /factories                             # Factories files
│   │   └── UserFactory.php
|   │    /migrations                           # Migration files
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2014_10_12_100000_create_password_reset_tokens_table.php
│   │   ├── 2019_08_19_000000_create_failed_jobs_table.php
│   │   ├── 2019_12_14_000001_create_personal_access_tokens_table.php
│   │   ├── 2024_09_06_131512_create_attendances_table.php
│   │   ├── 2024_09_06_141901_create_employees_table.php
│   │   ├── 2024_09_06_142237_create_proof_of_works_table.php
│   │   └── 2024_09_06_153825_create_bills_table.php
│   │   /seeders                               # Seeders files
│   │   └── DatabaseSeeder.php
│                                      
├── /routes                                    # Routes files
│   └── api.php
│
├── .env
├── composer.lock
├── composer.json
├── package-lock.json
├── package.json
└── README.md                                  # Project documentation



## Attendance Controller

The `AttendanceController` manages employee attendance within a course-based system. It includes the following functions:

### Methods

- **`index()`**: 
  Retrieves and filters attendance data based on course IDs, status, and employee number. Returns paginated results to efficiently handle large datasets.

- **`record(Request $request)`**: 
  Records attendance for an employee. Validates employee existence, role, activation status, and course registration before creating the attendance record.

- **`update(Request $request, $id)`**: 
  Updates the status of a specific attendance record. Ensures the record exists before applying any changes to avoid errors.

- **`mark(Request $request)`**: 
  Marks attendance by creating a new record based on the provided employee and course IDs. 

### Features
- **Validation**: Ensures all required data and conditions are met before performing operations.
- **Exception Handling**: Incorporates robust error handling to maintain system stability and provide clear feedback.
- **Pagination**: Uses pagination in `index()` to manage large volumes of data effectively.
- **Consistent Response Formatting**: Utilizes the `makeResponse` helper to standardize API responses, ensuring clarity and consistency.


## Bills Controller

This controller manages bills, providing actions for listing, viewing, creating, updating, and deleting bills. It enforces role-based access, allowing only admins to perform specific actions, while regular users can manage their own bills.

### Methods

- **`index()`**: 
  Retrieves all bills for admins, while non-admin users only see their own bills. Handles cases where no bills are found by returning a proper message.

- **`show($id)`**: 
  Displays details of a specific bill, ensuring the current user has access to view it. Admins can view any bill, while regular users are restricted to their own bills.

- **`store(Request $request)`**: 
  Adds or updates a bill. It validates the bill data, ensuring that an invoice attachment is included when required. Handles file uploads securely and uses database transactions to roll back in case of any errors.

- **`destroy($id)`**: 
  Deletes a bill after verifying its existence and that the user has permission to delete it. It also deletes the associated invoice file from storage if it exists.

### Features
- **Role-based Access**: Admins have full access, while regular users are restricted to their own bills.
- **File Handling**: Securely manages invoice file uploads, and ensures proper cleanup when a bill is deleted.
- **Transaction Management**: Uses database transactions to ensure data integrity, rolling back changes if any part of the process fails.
- **Consistent Response Formatting**: Utilizes the `makeResponse` helper to standardize API responses for success, failure, or error conditions.
- **Exception Handling**: Catches and handles exceptions effectively, ensuring the system remains secure and stable.


## Employees Controller

This controller manages employee data, providing features for listing, viewing, adding, updating, deleting, searching, and retrieving employee names. Role-based access ensures only authorized users can interact with specific data.

### Methods

- **`index()`**: 
  Retrieves employees based on the current user's role. Admins can access all employees, while users can only access their own records. Handles cases with no employees by returning an appropriate message.

- **`show($id)`**: 
  Displays details of a specific employee. Ensures that the employee belongs to the current user or the admin has access. Returns the employee's information or an error message if unauthorized.

- **`store(EmployeesStoreRequest $request)`**: 
  Adds or updates an employee's data. If no employee ID is provided, a new employee is created. Otherwise, it updates the existing employee's details. It handles both operations based on the employee's status and the provided data.

- **`destroy($id)`**: 
  Deletes an employee record after verifying that the employee exists and belongs to the current user. Admins can delete any employee, while regular users can only delete their own records.

- **`employeeNames()`**: 
  Retrieves a list of employee names and IDs based on the user's role. Admins can see all employees, while users can only view their own.

- **`search($search)`**: 
  Searches for employees by name or employee ID. Allows both users and admins to filter through employee records based on their role, returning relevant matches.

### Features
- **Role-based Access**: Ensures users can only interact with employee data they are authorized to access.
- **Secure Data Handling**: Safeguards employee information, ensuring updates and deletions are performed with proper authorization.
- **Efficient Response Management**: Uses `makeResponse` to format API responses consistently for success, failure, or error conditions.


## Proof of Work Controller

This controller manages **Proof of Work** records for authenticated users, including file uploads, retrieval, updates, and deletions. 

### Methods

- **`index()`**: Retrieves all proof-of-work records for the authenticated user. Returns success if records exist or a message if none are found.

- **`show($id)`**: Displays a specific proof-of-work record. Ensures the record belongs to the authenticated user. Returns success or failure.

- **`store(ProofOfWorkStoreRequest $request)`**:
  - **Create**: Validates and uploads a document. Creates a new proof-of-work record with the document path, proof ID, employee ID, and expiry date.
  - **Update**: If a new document is uploaded, it replaces the old one. Updates record details.
  - Returns success or failure depending on the outcome.

- **`destroy($id)`**: Deletes a specific proof-of-work record and its associated file. Returns success or failure.

### Features
- **File Handling**: Manages file uploads and deletions using Laravel's `Storage` facade.
- **Role-based Access**: Ensures users can only access their own records.
- **Error Handling**: Catches exceptions and provides clear error messages.


## Rule Class

The `Rule` class provides API-specific validation rules for various operations. It includes a static method to retrieve validation rules based on the API endpoint.

### Validation Rules

- **`GENERATE_CIPHER`**: Requires a `sampleString` parameter.
- **`ADD_CATEGORY`**: Requires `name`, `parentId`, and optionally a `category_image` file.
- **`SAVE_ERROR`**: Requires `apiName`, `body`, `header`, and `errorMessage`.

### Methods

- **`get($api)`**: Retrieves the validation rules for a specified API endpoint.

This class centralizes and organizes validation rules, ensuring consistent and maintainable code for API operations.


## `jwtToUser` Function

The `jwtToUser` function converts a JWT (JSON Web Token) into a user object.

### Parameters

- **`$token`** (string): The JWT to be converted.

### Returns

- **object**: The user object associated with the provided JWT, retrieved using the `\JWTAuth::toUser` method.

This function simplifies user retrieval from a JWT for authentication and authorization purposes.


## Helper Functions

### `invalidTokenResponse`

Returns a default response indicating an invalid token.

- **Returns:** `array`
  - `data`: Contains `code` (failure code), `message` (invalid token message), and `serverMaintenance` status.
  - `status`: `false`

### `defaultErrorResponse`

Returns a default error response for general failures.

- **Returns:** `array`
  - `data`: Contains `code` (failure code), `message` (generic error message), and `serverMaintenance` status.
  - `status`: `false`

### `makeResponse`

Generates a formatted JSON response.

- **Parameters:**
  - `$code` (integer): Response code.
  - `$message` (string): Message to include in the response.
  - `$data` (array): Additional data to include.
  - `$status` (boolean): HTTP status code (default is `500`).
  - `$errors` (string|array): Optional error details.

- **Returns:** `array`
  - `data`: Includes `code`, `message`, `result` (data), `errors`, and `serverMaintenance` status.
  - `status`: HTTP status code

These functions standardize API responses for invalid tokens, general errors, and custom responses.


## Helper Functions

### `getRule`

Fetches validation rules based on the API endpoint.

- **Parameters:**
  - `$api` (string): API endpoint for which to retrieve rules.

- **Returns:** `object` containing validation rules.

### `validateData`

Validates request data against predefined rules.

- **Parameters:**
  - `$request`: The request object containing data to validate.
  - `$apiRule` (string): API endpoint to determine validation rules.
  - `$customMessages` (array): Optional custom validation messages.
  - `$customAttribute` (array): Optional custom attribute names.

- **Returns:** `array`
  - On validation failure:
    - `status`: `true`
    - `errors`: Validation error messages.
  - On validation success:
    - `status`: `false`

- **Errors Handling:** Rolls back database transactions on exceptions and returns a standardized error response with a failure message.

These functions handle fetching validation rules and validating request data, ensuring data integrity and standardized error handling.


## Middleware: `Authenticate`

### Overview

Handles user authentication, redirecting unauthenticated users to the login page or returning a JSON response based on the request type.

### Methods

#### `redirectTo`

Determines where to redirect unauthenticated users.

- **Parameters:**
  - `$request` (Request): The HTTP request instance.

- **Returns:** `string|null`
  - If the request expects JSON, returns `null` (no redirection).
  - Otherwise, returns the route to the login page.

This middleware ensures that users are redirected appropriately based on their request type when they are not authenticated.


## Middleware: `TrimStrings`

### Overview

Automatically trims whitespace from input strings, except for specific attributes like passwords.

### Properties

#### `$except`

An array of attributes that should not be trimmed.

- **Type:** `array<int, string>`
- **Default:** 
  ```php
  protected $except = [
      'current_password',
      'password',
      'password_confirmation',
  ];


## Laravel Form Requests

### Overview

Laravel's Form Requests are used to handle validation and authorization logic for HTTP requests. They streamline validation by separating it from controllers and providing a clean and reusable way to validate incoming data.

### Request Format

For handling different types of requests, such as creating, updating, or deleting resources, we use specific Form Requests:

- **`BillDeleteRequest.php`**: Validates data for deleting a bill.
- **`BillStoreRequest.php`**: Validates data for creating or updating a bill.
- **`EmployeesRequest.php`**: Validates employee data for creation or updates.
- **`MarkAttendanceRequest.php`**: Validates data for marking attendance.
- **`ProofOfWorkStoreRequest.php`**: Validates data for creating or updating proof of work records.
- **`RecordAttendanceRequest.php`**: Validates data for recording attendance.
- **`UpdateAttendanceRequest.php`**: Validates data for updating attendance records.

### Validation Process

Each request class typically includes:

- **`authorize()`**: Determines if the user is authorized to make the request. Returns `true` by default.
- **`rules()`**: Defines the validation rules that apply to the request data.
- **`failedValidation(Validator $validator)`**: Handles validation failures by returning a formatted response with errors.


### Message Class

The `Message` class in `App\Http\Responses` centralizes all response messages used throughout the application. It provides a set of predefined messages for different scenarios, such as errors, empty results, or successful operations.

### Usage

This class is utilized to ensure consistent messaging in API responses, enhancing clarity and maintainability. By using this centralized message repository, the application can standardize the way messages are communicated across various endpoints and components.

Example usage:
```php
return makeResponse(ResponseCode::FAIL, Message::NO_PRODUCT_UPLOADED, [], ResponseCode::FAIL);
```

### ResponseCode Class

The `ResponseCode` class in `App\Http\Responses` defines standardized response codes and associated messages used across the application. It helps maintain consistency in API responses by providing a centralized set of status codes and their corresponding messages.

### Response Codes

- **200**: Success
- **400**: Fail
- **401**: Invalid Credentials
- **403**: Unauthorized
- **404**: Not Found
- **422**: Validation Error
- **500**: Unexpected Error

### Usage

The class includes a method `getMessage($code)` to retrieve the message associated with each response code. This ensures that response messages are consistent and easy to manage across the application.

Example usage:
```php
return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
```

## Attendance Model

The `Attendance` model handles employee attendance records, interacting with the `attendances` table. It links to the `User` and `Course` models via relationships, associating records with specific employees and courses. Key attributes include `employee_id`, `course_id`, `attendance_date`, and `status`. The model provides scopes for filtering records by employee, course, date, and status, enhancing the ability to query and manage attendance data effectively.


## Bills Model

The `Bills` model manages billing records in the `bills` table and supports soft deletes. Key attributes include `invoice_no`, `invoice_attach`, `user_id`, `amount`, `billing_date`, `description`, and `status`. It uses the `SoftDeletes` trait for handling soft deletion, marking records as deleted without removing them from the database. The model includes a relationship to the `User` model, associating each bill with a specific user. Attributes are cast to appropriate native types for consistency.


## Employees Model

The `Employees` model manages employee records within the `employees` table. It includes attributes such as `name`, `user_id`, `emp_id`, `level_id`, `department_id`, `dob`, `gender`, `address`, `email`, `marital_status`, `nationality`, `phone_no`, `ni_number`, and various other employment details. The model supports mass assignment for these attributes and casts certain fields to native types for consistency. It also defines a relationship to the `User` model, linking each employee to their user record based on `emp_id`.


## ProofOfWork Model

The `ProofOfWork` model represents proof of work documents associated with employees and users. It supports soft deletion and includes attributes such as `user_id`, `pow_id`, `expiry_date`, `document`, `status`, and `emp_id`. The model allows mass assignment for these attributes and casts certain fields to native types. It defines relationships to the `User` model (for organizational associations) and the `Employees` model (for employee associations), enabling comprehensive management of work-related documentation.


## User Model

The `User` model represents users in the application, extending `Authenticatable` for authentication purposes. It uses traits for API token handling, factory creation, and notifications. The model includes mass assignable attributes such as `name`, `email`, and `password`, with the password and `remember_token` attributes hidden for security. The `email_verified_at` field is cast to a `datetime`, and the `password` is cast to a hashed format, ensuring proper handling and security of user credentials.


### ReactJS

#### auth.js
This file provides a set of API functions to manage authentication and user data operations. It uses a central handleApiRequest function to standardize API calls and includes functions for logging in, verifying OTP, resending OTP, fetching user details, and updating the user profile.

#### CoursesCard.jsx
This component displays a styled course card with details such as name, progress, and activity count. It adapts to screen size and course status (locked or unlocked) using Material UI components and icons. Navigation is handled based on user interactions, and PropTypes ensure type safety.

#### CoursesPage.jsx
This component displays a list of courses fetched from an API. It features a hero banner with breadcrumbs for navigation and a grid layout to present each course using the CoursesCard component. It uses React hooks for state management and data fetching, and Redux for accessing user information.

#### excelExport.jsx
This component allows users to download data as an Excel file. It uses ExcelJS to generate a formatted Excel sheet and file-saver to trigger the download. There is a button that initiates the export process.

#### textField.jsx
This component provides a styled text input field with an optional adornment and label. It features custom styles for the input field and label, with required field indication and support for right-to-left text direction.


## UserFactory

The `UserFactory` class in `Database\Factories` is used for generating fake user data for testing and seeding purposes. It provides a default set of attributes for the `User` model, including `name`, `email`, `password`, and `remember_token`. The factory also includes a method `unverified()` to simulate users with unverified email addresses. This setup helps streamline the creation of test data with realistic and consistent values.


## Migrations

The migration files in this project define the schema changes for various database tables. Key migrations include:

- **2014_10_12_000000_create_users_table.php**: Creates the `users` table with essential user fields.
- **2014_10_12_100000_create_password_reset_tokens_table.php**: Sets up the `password_reset_tokens` table for handling password resets.
- **2019_08_19_000000_create_failed_jobs_table.php**: Creates the `failed_jobs` table to log job failures.
- **2019_12_14_000001_create_personal_access_tokens_table.php**: Defines the `personal_access_tokens` table for API token management.
- **2024_09_06_131512_create_attendances_table.php**: Establishes the `attendances` table for tracking employee attendance.
- **2024_09_06_141901_create_employees_table.php**: Sets up the `employees` table to store employee records.
- **2024_09_06_142237_create_proof_of_works_table.php**: Creates the `proof_of_works` table for managing proof of work documents.
- **2024_09_06_153825_create_bills_table.php**: Defines the `bills` table for handling billing information.

Each migration file includes `up` and `down` methods to apply and revert schema changes, respectively, ensuring a structured and manageable database schema evolution.


## DatabaseSeeder

The `DatabaseSeeder` class in `Database\Seeders` is responsible for seeding the application's database with initial data. It uses model factories to create 10 random `User` records and an additional `User` with predefined attributes (`name` and `email`). This seeder helps set up a baseline dataset for development and testing purposes.


## API Best Practices

This project follows best practices for API development, ensuring a well-structured and maintainable API. The API routes are defined in `routes/api.php`, organized with clear prefixes and middleware to enforce security and access control. Routes are grouped by resource type, such as `employees`, `tasks`, and `proof-of-work`, to provide a coherent and intuitive API structure. Authentication is managed using Laravel Sanctum, with routes protected to ensure that only authorized users can access or modify resources. The API also includes utility routes for checking database connectivity and handling user login and registration. This organization promotes consistency, security, and ease of use in the API.


## .env Configuration

The `.env` file is used to configure environment-specific settings for the Laravel application. It includes essential configuration values such as the application name (`APP_NAME`), environment (`APP_ENV`), and debug mode (`APP_DEBUG`). Database settings, such as connection details (`DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, etc.), are specified to connect the application to the database. Logging and caching configurations are managed through `LOG_CHANNEL`, `CACHE_DRIVER`, and other related settings. The `.env` file also defines mail server settings (`MAIL_MAILER`, `MAIL_HOST`, etc.), AWS credentials, and Pusher configuration for real-time notifications. This file ensures that environment-specific settings can be easily managed and adapted without modifying the application code.


## composer.json

The `composer.json` file defines the PHP dependencies and configuration for the Laravel application. It specifies the project details such as name, description, and license, and outlines the required packages, including Laravel framework components, Guzzle HTTP client, and Sanctum for API authentication. The `require-dev` section lists development dependencies for tasks like testing, linting, and code generation. Autoloading is configured to map namespaces to directories for both application and test code. Key scripts automate common tasks, such as generating application keys and publishing Laravel assets. The configuration ensures optimized autoloading, preferred package installation, and stable dependencies, providing a solid foundation for managing the application's PHP ecosystem.


## composer.lock

The `composer.lock` file is automatically generated by Composer to lock the exact versions of dependencies installed for a PHP project. Unlike `composer.json`, which defines the required packages and their version constraints, `composer.lock` records the specific versions of packages and their dependencies that were resolved and installed at the time of running `composer install`. This ensures that all environments, such as development, staging, and production, use the same versions of dependencies, providing consistency and preventing issues related to version discrepancies. By committing `composer.lock` to version control, you help ensure that every team member and deployment environment has an identical set of packages, which improves reproducibility and stability of the application.


## package-lock.json

The `package-lock.json` file is generated by npm (Node Package Manager) to provide a detailed snapshot of the project's dependency tree. It records the exact versions of all dependencies and their nested dependencies, ensuring that the same versions are installed across different environments. Unlike `package.json`, which specifies the dependency versions and ranges, `package-lock.json` locks the versions to those that were resolved at the time of installation. This file is crucial for maintaining consistency in the development process, as it ensures that every environment—whether development, staging, or production—uses the precise package versions intended by the project. By including `package-lock.json` in version control, you prevent discrepancies that could arise from different versions of dependencies, thereby reducing the likelihood of bugs and compatibility issues.


## package.json

The `package.json` file is a crucial component of Node.js projects, serving as the manifest for the project. It contains metadata about the project, including its name, version, description, and the entry point of the application. This file also specifies the project's dependencies, both runtime and development, by defining their version ranges. In addition to listing dependencies, `package.json` includes scripts for common tasks such as testing, building, and starting the application, which can be executed via npm commands. It is essential for managing the project's dependencies and configuration, and it ensures that anyone who clones the repository can easily install the required packages and run the project. By defining project-specific configurations and scripts, `package.json` helps streamline development workflows and maintain consistency across different environments.
