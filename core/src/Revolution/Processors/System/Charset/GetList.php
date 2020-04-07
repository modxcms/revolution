<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Charset;

use MODX\Revolution\Processors\Processor;

/**
 * Gets a list of charsets
 * @package MODX\Revolution\Processors\System\Charset
 */
class GetList extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('charsets');
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $charsets = [
            [
                'value' => 'ASMO-708',
                'text' => 'Arabic (ASMO 708) - ASMO-708',
            ],
            [
                'value' => 'DOS-720',
                'text' => 'Arabic (DOS) - DOS-720',
            ],
            [
                'value' => 'iso-8859-6',
                'text' => 'Arabic (ISO) - iso-8859-6',
            ],
            [
                'value' => 'x-mac-arabic',
                'text' => 'Arabic (Mac) - x-mac-arabic',
            ],
            [
                'value' => 'windows-1256',
                'text' => 'Arabic (Windows) - windows-1256',
            ],
            [
                'value' => 'ibm775',
                'text' => 'Baltic (DOS) - ibm775',
            ],
            [
                'value' => 'iso-8859-4',
                'text' => 'Baltic (ISO) - iso-8859-4',
            ],
            [
                'value' => 'windows-1257',
                'text' => 'Baltic (Windows) - windows-1257',
            ],
            [
                'value' => 'ibm852',
                'text' => 'Central European (DOS) - ibm852',
            ],
            [
                'value' => 'iso-8859-2',
                'text' => 'Central European (ISO) - iso-8859-2',
            ],
            [
                'value' => 'x-mac-ce',
                'text' => 'Central European (Mac) - x-mac-ce',
            ],
            [
                'value' => 'windows-1250',
                'text' => 'Central European (Windows) - windows-1250',
            ],
            [
                'value' => 'EUC-CN',
                'text' => 'Chinese Simplified (EUC) - EUC-CN',
            ],
            [
                'value' => 'gb2312',
                'text' => 'Chinese Simplified (GB2312) - gb2312',
            ],
            [
                'value' => 'hz-gb-2312',
                'text' => 'Chinese Simplified (HZ) - hz-gb-2312',
            ],
            [
                'value' => 'x-mac-chinesesimp',
                'text' => 'Chinese Simplified (Mac) - x-mac-chinesesimp',
            ],
            [
                'value' => 'big5',
                'text' => 'Chinese Traditional (Big5) - big5',
            ],
            [
                'value' => 'x-Chinese-CNS',
                'text' => 'Chinese Traditional (CNS) - x-Chinese-CNS',
            ],
            [
                'value' => 'x-Chinese-Eten',
                'text' => 'Chinese Traditional (Eten) - x-Chinese-Eten',
            ],
            [
                'value' => 'x-mac-chinesetrad',
                'text' => 'Chinese Traditional (Mac) - x-mac-chinesetrad',
            ],
            [
                'value' => 'cp866',
                'text' => 'Cyrillic (DOS) - cp866',
            ],
            [
                'value' => 'iso-8859-5',
                'text' => 'Cyrillic (ISO) - iso-8859-5',
            ],
            [
                'value' => 'koi8-r',
                'text' => 'Cyrillic (KOI8-R) - koi8-r',
            ],
            [
                'value' => 'koi8-u',
                'text' => 'Cyrillic (KOI8-U) - koi8-u',
            ],
            [
                'value' => 'x-mac-cyrillic',
                'text' => 'Cyrillic (Mac) - x-mac-cyrillic',
            ],
            [
                'value' => 'windows-1251',
                'text' => 'Cyrillic (Windows) - windows-1251',
            ],
            [
                'value' => 'x-Europa',
                'text' => 'Europa - x-Europa',
            ],
            [
                'value' => 'x-IA5-German',
                'text' => 'German (IA5) - x-IA5-German',
            ],
            [
                'value' => 'ibm737',
                'text' => 'Greek (DOS) - ibm737',
            ],
            [
                'value' => 'iso-8859-7',
                'text' => 'Greek (ISO) - iso-8859-7',
            ],
            [
                'value' => 'x-mac-greek',
                'text' => 'Greek (Mac) - x-mac-greek',
            ],
            [
                'value' => 'windows-1253',
                'text' => 'Greek (Windows) - windows-1253',
            ],
            [
                'value' => 'ibm869',
                'text' => 'Greek, Modern (DOS) - ibm869',
            ],
            [
                'value' => 'DOS-862',
                'text' => 'Hebrew (DOS) - DOS-862',
            ],
            [
                'value' => 'iso-8859-8-i',
                'text' => 'Hebrew (ISO-Logical) - iso-8859-8-i',
            ],
            [
                'value' => 'iso-8859-8',
                'text' => 'Hebrew (ISO-Visual) - iso-8859-8',
            ],
            [
                'value' => 'x-mac-hebrew',
                'text' => 'Hebrew (Mac) - x-mac-hebrew',
            ],
            [
                'value' => 'windows-1255',
                'text' => 'Hebrew (Windows) - windows-1255',
            ],
            [
                'value' => 'ibm861',
                'text' => 'Icelandic (DOS) - ibm861',
            ],
            [
                'value' => 'x-mac-icelandic',
                'text' => 'Icelandic (Mac) - x-mac-icelandic',
            ],
            [
                'value' => 'x-iscii-as',
                'text' => 'ISCII Assamese - x-iscii-as',
            ],
            [
                'value' => 'x-iscii-be',
                'text' => 'ISCII Bengali - x-iscii-be',
            ],
            [
                'value' => 'x-iscii-de',
                'text' => 'ISCII Devanagari - x-iscii-de',
            ],
            [
                'value' => 'x-iscii-gu',
                'text' => 'ISCII Gujarathi - x-iscii-gu',
            ],
            [
                'value' => 'x-iscii-ka',
                'text' => 'ISCII Kannada - x-iscii-ka',
            ],
            [
                'value' => 'x-iscii-ma',
                'text' => 'ISCII Malayalam - x-iscii-ma',
            ],
            [
                'value' => 'x-iscii-or',
                'text' => 'ISCII Oriya - x-iscii-or',
            ],
            [
                'value' => 'x-iscii-pa',
                'text' => 'ISCII Panjabi - x-iscii-pa',
            ],
            [
                'value' => 'x-iscii-ta',
                'text' => 'ISCII Tamil - x-iscii-ta',
            ],
            [
                'value' => 'x-iscii-te',
                'text' => 'ISCII Telugu - x-iscii-te',
            ],
            [
                'value' => 'euc-jp',
                'text' => 'Japanese (EUC) - euc-jp',
            ],
            [
                'value' => 'iso-2022-jp',
                'text' => 'Japanese (JIS) - iso-2022-jp',
            ],
            [
                'value' => 'iso-2022-jp',
                'text' => 'Japanese (JIS-Allow 1 byte Kana - SO/SI) - iso-2022-jp',
            ],
            [
                'value' => 'csISO2022JP',
                'text' => 'Japanese (JIS-Allow 1 byte Kana) - csISO2022JP',
            ],
            [
                'value' => 'x-mac-japanese',
                'text' => 'Japanese (Mac) - x-mac-japanese',
            ],
            [
                'value' => 'shift_jis',
                'text' => 'Japanese (Shift-JIS) - shift_jis',
            ],
            [
                'value' => 'ks_c_5601-1987',
                'text' => 'Korean - ks_c_5601-1987',
            ],
            [
                'value' => 'euc-kr',
                'text' => 'Korean (EUC) - euc-kr',
            ],
            [
                'value' => 'iso-2022-kr',
                'text' => 'Korean (ISO) - iso-2022-kr',
            ],
            [
                'value' => 'Johab',
                'text' => 'Korean (Johab) - Johab',
            ],
            [
                'value' => 'x-mac-korean',
                'text' => 'Korean (Mac) - x-mac-korean',
            ],
            [
                'value' => 'iso-8859-3',
                'text' => 'Latin 3 (ISO) - iso-8859-3',
            ],
            [
                'value' => 'iso-8859-15',
                'text' => 'Latin 9 (ISO) - iso-8859-15',
            ],
            [
                'value' => 'x-IA5-Norwegian',
                'text' => 'Norwegian (IA5) - x-IA5-Norwegian',
            ],
            [
                'value' => 'IBM437',
                'text' => 'OEM United States - IBM437',
            ],
            [
                'value' => 'x-IA5-Swedish',
                'text' => 'Swedish (IA5) - x-IA5-Swedish',
            ],
            [
                'value' => 'windows-874',
                'text' => 'Thai (Windows) - windows-874',
            ],
            [
                'value' => 'ibm857',
                'text' => 'Turkish (DOS) - ibm857',
            ],
            [
                'value' => 'iso-8859-9',
                'text' => 'Turkish (ISO) - iso-8859-9',
            ],
            [
                'value' => 'x-mac-turkish',
                'text' => 'Turkish (Mac) - x-mac-turkish',
            ],
            [
                'value' => 'windows-1254',
                'text' => 'Turkish (Windows) - windows-1254',
            ],
            [
                'value' => 'unicode',
                'text' => 'Unicode - unicode',
            ],
            [
                'value' => 'unicodeFFFE',
                'text' => 'Unicode (Big-Endian) - unicodeFFFE',
            ],
            [
                'value' => 'UTF-7',
                'text' => 'Unicode (UTF-7) - utf-7',
            ],
            [
                'value' => 'UTF-8',
                'text' => 'Unicode (UTF-8) - utf-8',
            ],
            [
                'value' => 'us-ascii',
                'text' => 'US-ASCII - us-ascii',
            ],
            [
                'value' => 'windows-1258',
                'text' => 'Vietnamese (Windows) - windows-1258',
            ],
            [
                'value' => 'ibm850',
                'text' => 'Western European (DOS) - ibm850',
            ],
            [
                'value' => 'x-IA5',
                'text' => 'Western European (IA5) - x-IA5',
            ],
            [
                'value' => 'iso-8859-1',
                'text' => 'Western European (ISO) - iso-8859-1',
            ],
            [
                'value' => 'macintosh',
                'text' => 'Western European (Mac) - macintosh',
            ],
            [
                'value' => 'Windows-1252',
                'text' => 'Western European (Windows) - Windows-1252',
            ],
        ];

        return $this->outputArray($charsets);
    }
}
