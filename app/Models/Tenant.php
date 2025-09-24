<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
        ];
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
}