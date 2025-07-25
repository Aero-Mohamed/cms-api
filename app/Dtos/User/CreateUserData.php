<?php

namespace App\Dtos\User;

use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
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
}
