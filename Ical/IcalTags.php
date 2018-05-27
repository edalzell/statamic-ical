<?php

namespace Statamic\Addons\Ical;

use Statamic\API\URL;
use Statamic\Extend\Tags;

class IcalTags extends Tags
{
    public function download()
    {
        return URL::makeAbsolute(
            $this->actionUrl('download') . '?' . http_build_query($this->parameters)
        );
    }
}
