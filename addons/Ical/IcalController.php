<?php

namespace Statamic\Addons\Ical;

use Carbon\Carbon;
use Statamic\API\Config;
use Statamic\Extend\Controller;
use Sabre\VObject\Component\VAlarm;
use Sabre\VObject\Component\VEvent;
use Sabre\VObject\Component\VCalendar;

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

        /** @var \Sabre\VObject\Component\VCalendar $vCalendar */
        $vCalendar = new VCalendar(["URL" => Config::getSiteUrl()]);

        // convert date to GMT as that's what iCal wants
        /** @var \Carbon\Carbon $start_date */
        $start_date = new Carbon($data['start_date']);
        $start_date->setTimezone('UTC');
        //$start_date = Carbon::createFromTimestamp($data['start_date'])->setTimezone('UTC');

        /** @var \Carbon\Carbon $end_date */
        $end_date = new Carbon($data['end_date']);
        $end_date->setTimezone('UTC');
        //$end_date = Carbon::createFromTimestamp($data['end_date'])->setTimezone('UTC');

        /** @var \Sabre\VObject\Component\VEvent $vEvent */
        $vEvent = $vCalendar->add('VEVENT', [
            'DTSTART' => $start_date,
            'DTEND' => $end_date,
        ]);

        if (isset($data['summary']))
        {
            $vEvent->add('SUMMARY', $data['summary']);
        }

        if (isset($data['description']))
        {
            $vEvent->add('DESCRIPTION', $data['description']);
        }

        if (isset($data['url']))
        {
            $vEvent->add('URL', $data['url']);
        }

        $vEvent->add('VALARM', [
            'ACTION' => 'DISPLAY',
            'TRIGGER' => '-PT1H',
            'DESCRIPTION' => $data['summary'],
        ]);

        response($vCalendar->serialize())
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="cal.ics"')
            ->send();
    }
}
