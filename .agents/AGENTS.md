# Laravel Clean Architecture Guidelines (Action Pattern)

This project uses the **Action Pattern** to maintain clean, readable, and highly maintainable code. The goal is to keep controllers, models, and other Laravel components "thin" by delegating business logic to single-responsibility Action classes.

---

## 1. Golden Rules of File Readability

- **Max File Length**: No single file should exceed **150 lines of code** (excluding boilerplate comments where necessary, but code itself should be kept compact).
- **Max Method Length**: Methods must not exceed **25 lines**. If a method is longer, refactor by extracting blocks into private helper methods or new classes.
- **Low Cognitive Complexity**: Avoid nested loops, deeply nested `if-else` blocks, and long inline database queries.

---

## 2. Action Pattern

All business logic must be isolated in single-responsibility classes under `app/Actions/` grouped into subfolders by feature (e.g., `app/Actions/Auth/`, `app/Actions/Fasting/`, `app/Actions/Fgb/`, `app/Actions/Safety/`).

### Action Class Structure
- Actions should be named as verbs describing the task (e.g., `RegisterUserAction`, `StoreFastingLogAction`).
- Each Action must have only **one public method**, typically `execute()` or `handle()`.
- Use dependency injection in the constructor for any services or repositories required.

#### Example Action Class:
```php
<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\MobileProfile;
use App\Models\UserAuthProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'email' => $data['email'],
                'type' => $data['type'],
            ]);

            MobileProfile::create(array_merge($data['profile'], ['user_id' => $user->id]));

            UserAuthProvider::create([
                'user_id' => $user->id,
                'provider' => $data['provider'],
                'provider_id' => $data['email'],
                'password_hash' => Hash::make($data['password']),
            ]);

            return $user;
        });
    }
}
```

---

## 3. Thin Controllers

Controllers should act only as traffic controllers. They must NOT contain business logic, manual database queries, or inline validation logic.

### Controller Guidelines:
- **No Inline Validation**: Always use **Form Requests** (`app/Http/Requests`) for validating input.
- **Delegate Business Logic**: Call Action classes to perform actions.
- **No Direct DB Queries**: Controllers should not run raw queries, complex Eloquent builders, or transactions. Let the Action handle those.
- **API Resources**: Use **API Resources** (`app/Http/Resources`) to format JSON responses instead of returning raw arrays or model objects.

#### Example Controller:
```php
<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Actions\RegisterUserAction;
use App\Utilities\JwtUtility;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $registerAction)
    {
        $user = $registerAction->execute($request->validated());
        
        $token = JwtUtility::generateAccessToken($user);
        $refreshToken = JwtUtility::generateRefreshToken($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user->load('mobileProfile')),
                'token' => $token,
                'refresh_token' => $refreshToken['token'],
            ],
            'message' => 'Registration successful'
        ]);
    }
}
```

---

## 4. Thin Models

Models should represent the database structure and relationships, not business processes.

### Keep inside Models:
- Relationships (e.g., `belongsTo`, `hasMany`).
- Attribute casting (`protected $casts`).
- Query scopes (e.g., `scopeActive`).
- Accessors and mutators.

### Keep OUT of Models:
- Complex query logic that crosses multiple boundaries.
- Logic modifying other tables/models.
- Logic sending notifications, creating external tokens, or writing to log files.

---

## 5. Form Requests

Use Form Requests to cleanly separate validation logic from controller handlers.

#### Example Form Request (`app/Http/Requests/RegisterRequest.php`):
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer'],
            'gender' => ['required', 'string'],
            // etc...
        ];
    }
}
```

---

## 6. Clean Architecture (5-Layer Pattern)

For operations involving complex parameters, data querying, and formatting, follow the 5-layer clean architecture system:

1.  **Form Request**: Validate request inputs (`app/Http/Requests`).
2.  **DTO (Data Transfer Object)**: Group validated parameters into a strongly-typed data class (`app/DTO`).
3.  **Action Class**: Execute the business process by coordinating data scopes and retrieval (`app/Actions`).
4.  **Query Scopes**: Define DB query filters inside Model local scopes (`app/Models`) to hide Eloquent query builder complexities.
5.  **API Resource / Presenter**: Transform the Eloquent results into formatted, presentable JSON responses (`app/Http/Resources`).

### Example DTO:
```php
<?php

namespace App\DTO;

class PatientFilterData
{
    public function __construct(
        public ?string $search,
        public ?string $risk,
        public ?string $protocol,
        public ?string $date,
        public int $page
    ) {}

    public static function fromRequest(array $validated, int $page = 1): self
    {
        return new self(
            search: $validated['search'] ?? null,
            risk: $validated['risk'] ?? null,
            protocol: $validated['protocol'] ?? null,
            date: $validated['date'] ?? null,
            page: $page
        );
    }
}
```

---

## 7. Views Architecture & Partials Pattern

To prevent views (Blade files) from becoming bloated (max 150 lines rule applies to Blade files as well where practical), structure your views folder by features and extract layout sections into partials:

### Folder Directory Structure:
```
resources/views/
├── [feature]/
│   ├── index.blade.php             # Main page template
│   └── partials/                   # Sub-sections (Only if page is complex)
│       ├── _stats.blade.php
│       ├── _filters.blade.php
│       └── _modal-add.blade.php
```

### View Guidelines:
- **Use Partials (`@include`)**: For any complex section of a page (e.g. search forms, grids, modal dialogues), extract it into a separate file under `[feature]/partials/` prefixed with an underscore (e.g., `_filters.blade.php`).
- **Use Blade Components (`<x-...>`)**: For reusable UI elements (badges, buttons, generic modal skeletons, alert boxes) shared across different features, create them in `resources/views/components/`.
- **Keep Scripts Clean**: Avoid writing long inline scripts. Bind complex JavaScript properties/functions to external or cleanly declared Alpine.js handlers.


