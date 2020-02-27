iCal
=================

A Statamic V2 add-on that creates an iCal file that can be easily downloaded. Use if you have events and want people to be to add to their calender.

## Installing
1. Copy the folder contents to your Statamic `site\addons` directory
2. Update your addons, i.e. `php please update:addons`
3. There is no step 3

## Usage
```
<a href="{{ ical:download 
    start_date="{ start }" 
    end_date="{ end }" 
    summary="foo" 
    description="bar" 
    location="baz" 
    url="myevents.com" }}">Add to your calendar</a>
```
`start_date` & `end_date` could be a php date/time or a unix timestamp.

`summary`, `description`, `location` and `url` are all optional.

## LICENSE

[MIT License](http://emd.mit-license.org)