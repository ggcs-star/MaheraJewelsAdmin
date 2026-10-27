<?php

namespace App\Services;

use App\Models\Organization;

class OrganizationService
{
    public function getActiveOrganization(): ?Organization
    {
        return Organization::query()
            ->where('is_active', true)
            ->first();
    }
}
