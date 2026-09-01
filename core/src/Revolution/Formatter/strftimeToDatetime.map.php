<?php

return [
    '%a' => 'D',
    '%A' => 'l',
    '%d' => 'd',
    '%e' => 'j',
    '%j' => 'z', // 001 to 366 => 0 to 365
    '%u' => 'N',
    '%w' => 'w',
    '%U' => 'W', // general match, see strftime
    '%V' => 'W', // general match, see strftime
    '%W' => 'W',
    '%b' => 'M',
    '%h' => 'M', // general match, %h is localized version of %b
    '%B' => 'F',
    '%m' => 'm',
    '%C' => '**', // 2-digit century, no datetime equivalent
    '%g' => 'y', // general match, see strftime
    '%G' => 'Y', // general match, see strftime
    '%y' => 'y',
    '%Y' => 'Y',
    '%H' => 'H',
    '%k' => 'G',
    '%I' => 'h',
    '%l' => 'g',
    '%M' => 'i',
    '%p' => 'A',
    '%P' => 'a',
    '%S' => 's',
    '%z' => 'Z',
    '%Z' => 'T',
    '%s' => 'U',
    // compound formats
    '%r' => 'h:i:s A',
    '%R' => 'H:i',
    '%T' => 'H:i:s',
    '%X' => 'h:i:s', // locale unsupported in datetime, see strftime
    '%c' => 'c', // locale unsupported in datetime, see strftime
    '%D' => 'm/d/y',
    '%F' => 'Y-m-d',
    '%x' => 'm/d/y', // locale unsupported in datetime, see strftime
    // characters
    '%n' => '*n', // newline, \n only works within double quoted string
    '%t' => '*t', // tab, \t only works within double quoted string
    '%%' => '%'
];
