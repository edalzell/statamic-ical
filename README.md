iCal
=================

A Statamic V2 add-on that creates an iCal file that can be easily downloaded. Use if you have events and want people to be to add to their calender.

## Installing
1. Copy the folder contents to your Statamic `site\addons` directory
2. Update your addons, i.e. `php please addons:refresh`
2. There is no step 3

## Usage
```
<a href="{{ ical:download 
    id="{ a_unique_id }"
    start_date="{ start }" 
    end_date="{ end }" 
    summary="foo" 
    description="bar" 
    url="myevents.com" }}">Add to your calendar</a>
```
You need to pass it a unique id so that the details can be stored and retrieved. If you're on some normal content, the `id` of the page/entry would be fine.

`summary`, `description` and `url` are all optional.

## LICENSE

[MIT License](http://emd.mit-license.org)
