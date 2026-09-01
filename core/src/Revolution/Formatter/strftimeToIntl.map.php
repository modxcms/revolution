<?php

return [
    '%a' => 'D',
    '%A' => 'EEEE',
    '%d' => 'dd',
    '%e' => 'd',
    '%j' => 'DDD',
    '%u' => 'e', // general match, 1-7 (Mon-Sun) => 1-7 (Sun-Sat)
    '%w' => 'e', // general match, 0-6 (Sun-Sat) => 1-7 (Sun-Sat)
    '%U' => 'ww', // general match, see strftime
    '%V' => 'ww', // close match, except some leap years
    '%W' => 'ww', // general match, see strftime
    '%b' => 'MMM',
    '%h' => 'MMM',
    '%B' => 'MMMM',
    '%m' => 'MM',
    '%C' => '**', // 2-digit century, no Intl equivalent (would have to be calculated from y)
    '%g' => 'YY',
    '%G' => 'Y',
    '%y' => 'yy',
    '%Y' => 'y',
    '%H' => 'HH',
    '%k' => 'H',
    '%I' => 'hh',
    '%l' => 'h',
    '%M' => 'mm',
    '%p' => 'a',
    '%P' => 'a', // 'b' is specified in ICU, but doesn't seem to work, using 'a'
    '%S' => 'ss',
    '%z' => 'Z',
    '%Z' => 'z',
    '%s' => '**', // no Intl equivalent to display UNIX timestamp
    // compound formats
    '%r' => 'hh:mm:ss a',
    '%R' => 'HH:mm',
    '%T' => 'HH:mm:ss',
    '%X' => ['date' => \IntlDateFormatter::NONE, 'time' => \IntlDateFormatter::LONG],
    '%c' => ['date' => \IntlDateFormatter::MEDIUM, 'time' => \IntlDateFormatter::LONG],
    '%D' => 'MM/dd/yy',
    '%F' => 'y-MM-dd',
    '%x' => ['date' => \IntlDateFormatter::SHORT, 'time' => \IntlDateFormatter::NONE],
    // characters
    '%n' => '*n', // newline, \n only works within double quoted string
    '%t' => '*t', // tab, \t only works within double quoted string
    '%%' => "'%'"
];
