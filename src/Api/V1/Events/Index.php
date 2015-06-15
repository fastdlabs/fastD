<?php

namespace Api\V1\Events;

use Kernel\Extensions\RestEventExtension;

class Index extends RestEventExtension
{
    public function indexAction()
    {
        return $this->responseJson(['name' => 'jan']);
    }
}