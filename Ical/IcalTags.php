<?php

namespace Statamic\Addons\Ical;

use Statamic\API\URL;
use Statamic\Extend\Tags;

class IcalTags extends Tags
{
    public function download()
    {
        $id = $this->getParam('id');

        // if this event is NOT stored
        if (!$this->storage->getYAML($id))
        {
            //store the event data
            $this->storage->putYAML($id, $this->parameters);
        }

        return URL::makeAbsolute($this->actionUrl('download') . '?id=' . $id);
    }
}
