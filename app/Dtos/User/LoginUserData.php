<?php

namespace App\Dtos\User;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LoginUserData extends Data
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        #[Required, Email, Max(255)]
        public string $email,
        #[Required, Max(255)]
        public string $password,
    ) {
    }
}
