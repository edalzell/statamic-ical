<?php

namespace Statamic\Addons\Ical;

use Carbon\Carbon;
use Statamic\API\Config;
use Statamic\Extend\Controller;
use Eluceo\iCal\Component\Alarm;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Component\Calendar;

class IcalController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('index');
    }

    public function getDownload()
    {
        // get the event data
        $data = $this->storage->getYAML(request('id'));

        /** @var \Eluceo\iCal\Component\Calendar $vCalendar */
        $vCalendar = new Calendar(Config::getSiteUrl());

        // convert date to GMT as that's what iCal wants
        /** @var \Carbon\Carbon $start_date */
        $start_date = new Carbon($data['start_date']);
        $start_date->setTimezone('UTC');
        //$start_date = Carbon::createFromTimestamp($data['start_date'])->setTimezone('UTC');

        /** @var \Carbon\Carbon $end_date */
        $end_date = new Carbon($data['end_date']);
        $end_date->setTimezone('UTC');
        //$end_date = Carbon::createFromTimestamp($data['end_date'])->setTimezone('UTC');

        /** @var \Eluceo\iCal\Component\Event $vEvent */
        $vEvent = new Event();

        $vEvent->setDtStart($start_date)->setDtEnd($end_date);

        if (isset($data['summary']))
        {
            $vEvent->setSummary($data['summary']);
        }

        if (isset($data['description']))
        {
            $vEvent->setDescription($data['description']);
        }

        if (isset($data['url']))
        {
            $vEvent->setUrl($data['url']);
        }

        // @todo this doesn't work
        /** @var \Eluceo\iCal\Component\Alarm $vAlarm */
        $vAlarm = new Alarm();
        $vAlarm
            ->setAction(Alarm::ACTION_DISPLAY)
            ->setTrigger('-P1H') // one hour before
            ->setDescription($data['summary']);

        $vEvent->addComponent($vAlarm);

        $vCalendar->addComponent($vEvent);

        response($vCalendar->render())
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="cal.ics"')
            ->send();
    }
}
