<?php

namespace Statamic\Addons\Ical;

use Carbon\Carbon;
use Eluceo\iCal\Component\Alarm;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Statamic\API\Config;
use Statamic\Extend\Controller;

class IcalController extends Controller
{
    public function getDownload()
    {
        // @todo this doesn't work
        $vAlarm = new Alarm();
        $vAlarm
            ->setAction(Alarm::ACTION_DISPLAY)
            ->setTrigger('-P1H') // one hour before
            ->setDescription(request('summary'));

        $vEvent = new Event();
        $vEvent
            ->setDtStart($this->getCarbon(request('start_date')))
            ->setDtEnd($this->getCarbon(request('end_date')))
            ->setSummary(request('summary'))
            ->setLocation(request('location'))
            ->setDescription(request('description'))
            ->setUrl(request('url'))
            ->addComponent($vAlarm);

        $vCalendar = new Calendar(Config::getSiteUrl());
        $vCalendar->addComponent($vEvent);

        response($vCalendar->render())
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="cal.ics"')
            ->send();
    }

    /**
     * Get the Carbon version of the datetime
     *
     * @param string|int $datetime foo
     *
     * @return Carbon\Carbon
     */
    private function getCarbon($datetime)
    {
        if (is_numeric($datetime)) {
            return Carbon::createFromTimestamp($datetime)->setTimezone('UTC');
        } else {
            return Carbon::parse($datetime)->setTimezone('UTC')->setTimezone('UTC');
        }
    }
}
