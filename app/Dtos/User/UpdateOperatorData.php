<?php

namespace App\Dtos\User;

use App\Enums\SystemRoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class UpdateOperatorData extends Data
{
    /**
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     */
    public function __construct(
        #[Sometimes, Max(255)]
        public ?string $name = null,

        #[Sometimes, Email, Max(255)]
        public ?string $email = null,

        #[Sometimes, Min(8), Max(255), Confirmed]
        public ?string $password = null,
    ) {
    }

    /**
     * @return bool
     */
    public static function authorize(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole(SystemRoleEnum::ADMIN->value);
    }
}
