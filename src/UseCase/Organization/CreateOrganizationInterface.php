<?php

declare(strict_types=1);

namespace App\UseCase\Organization;

use App\Dto\Request\Organization\CreateOrganizationRequest;
use App\Dto\Response\Organization\CreateOrganizationResponse;
use Exception;

interface CreateOrganizationInterface
{
    /**
     * @throws Exception
     */
    public function execute(CreateOrganizationRequest $createOrganizationRequest): CreateOrganizationResponse;
}
