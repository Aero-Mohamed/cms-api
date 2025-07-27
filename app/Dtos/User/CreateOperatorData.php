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
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateOperatorData extends Data
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct(
        #[Required, Max(255)]
        public string $name,
        #[Required, Email, Max(255), Unique('users', 'email')]
        public string $email,
        #[Required, Min(8), Max(255), Confirmed]
        public string $password,
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
