<?php

namespace App\Queue;

use App\Service\ResourceService;

class CreateResourceJob extends AbstractJob
{

    public function __construct(private ResourceService $resourceService)
    {
    }

    public function execute()
    {
        $this->resourceService->create($this->arguments['resourceId']);
    }
}