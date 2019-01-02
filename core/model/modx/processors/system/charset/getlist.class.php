<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Gets a list of charsets
 *
 * @package modx
 * @subpackage processors.system.charset
 */
class modCharsetGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('charsets');
    }
    public function process() {
        $charsets = array(
            array(
                'value' => 'ASMO-708',
                'text' => 'Arabic (ASMO 708) - ASMO-708',
            ),
            array(
                'value' => 'DOS-720',
                'text' => 'Arabic (DOS) - DOS-720',
            ),
            array(
                'value' => 'iso-8859-6',
                'text' => 'Arabic (ISO) - iso-8859-6',
            ),
            array(
                'value' => 'x-mac-arabic',
                'text' => 'Arabic (Mac) - x-mac-arabic',
            ),
            array(
                'value' => 'windows-1256',
                'text' => 'Arabic (Windows) - windows-1256',
            ),
            array(
                'value' => 'ibm775',
                'text' => 'Baltic (DOS) - ibm775',
            ),
            array(
                'value' => 'iso-8859-4',
                'text' => 'Baltic (ISO) - iso-8859-4',
            ),
            array(
                'value' => 'windows-1257',
                'text' => 'Baltic (Windows) - windows-1257',
            ),
            array(
                'value' => 'ibm852',
                'text' => 'Central European (DOS) - ibm852',
            ),
            array(
                'value' => 'iso-8859-2',
                'text' => 'Central European (ISO) - iso-8859-2',
            ),
            array(
                'value' => 'x-mac-ce',
                'text' => 'Central European (Mac) - x-mac-ce',
            ),
            array(
                'value' => 'windows-1250',
                'text' => 'Central European (Windows) - windows-1250',
            ),
            array(
                'value' => 'EUC-CN',
                'text' => 'Chinese Simplified (EUC) - EUC-CN',
            ),
            array(
                'value' => 'gb2312',
                'text' => 'Chinese Simplified (GB2312) - gb2312',
            ),
            array(
                'value' => 'hz-gb-2312',
                'text' => 'Chinese Simplified (HZ) - hz-gb-2312',
            ),
            array(
                'value' => 'x-mac-chinesesimp',
                'text' => 'Chinese Simplified (Mac) - x-mac-chinesesimp',
            ),
            array(
                'value' => 'big5',
                'text' => 'Chinese Traditional (Big5) - big5',
            ),
            array(
                'value' => 'x-Chinese-CNS',
                'text' => 'Chinese Traditional (CNS) - x-Chinese-CNS',
            ),
            array(
                'value' => 'x-Chinese-Eten',
                'text' => 'Chinese Traditional (Eten) - x-Chinese-Eten',
            ),
            array(
                'value' => 'x-mac-chinesetrad',
                'text' => 'Chinese Traditional (Mac) - x-mac-chinesetrad',
            ),
            array(
                'value' => 'cp866',
                'text' => 'Cyrillic (DOS) - cp866',
            ),
            array(
                'value' => 'iso-8859-5',
                'text' => 'Cyrillic (ISO) - iso-8859-5',
            ),
            array(
                'value' => 'koi8-r',
                'text' => 'Cyrillic (KOI8-R) - koi8-r',
            ),
            array(
                'value' => 'koi8-u',
                'text' => 'Cyrillic (KOI8-U) - koi8-u',
            ),
            array(
                'value' => 'x-mac-cyrillic',
                'text' => 'Cyrillic (Mac) - x-mac-cyrillic',
            ),
            array(
                'value' => 'windows-1251',
                'text' => 'Cyrillic (Windows) - windows-1251',
            ),
            array(
                'value' => 'x-Europa',
                'text' => 'Europa - x-Europa',
            ),
            array(
                'value' => 'x-IA5-German',
                'text' => 'German (IA5) - x-IA5-German',
            ),
            array(
                'value' => 'ibm737',
                'text' => 'Greek (DOS) - ibm737',
            ),
            array(
                'value' => 'iso-8859-7',
                'text' => 'Greek (ISO) - iso-8859-7',
            ),
            array(
                'value' => 'x-mac-greek',
                'text' => 'Greek (Mac) - x-mac-greek',
            ),
            array(
                'value' => 'windows-1253',
                'text' => 'Greek (Windows) - windows-1253',
            ),
            array(
                'value' => 'ibm869',
                'text' => 'Greek, Modern (DOS) - ibm869',
            ),
            array(
                'value' => 'DOS-862',
                'text' => 'Hebrew (DOS) - DOS-862',
            ),
            array(
                'value' => 'iso-8859-8-i',
                'text' => 'Hebrew (ISO-Logical) - iso-8859-8-i',
            ),
            array(
                'value' => 'iso-8859-8',
                'text' => 'Hebrew (ISO-Visual) - iso-8859-8',
            ),
            array(
                'value' => 'x-mac-hebrew',
                'text' => 'Hebrew (Mac) - x-mac-hebrew',
            ),
            array(
                'value' => 'windows-1255',
                'text' => 'Hebrew (Windows) - windows-1255',
            ),
            array(
                'value' => 'ibm861',
                'text' => 'Icelandic (DOS) - ibm861',
            ),
            array(
                'value' => 'x-mac-icelandic',
                'text' => 'Icelandic (Mac) - x-mac-icelandic',
            ),
            array(
                'value' => 'x-iscii-as',
                'text' => 'ISCII Assamese - x-iscii-as',
            ),
            array(
                'value' => 'x-iscii-be',
                'text' => 'ISCII Bengali - x-iscii-be',
            ),
            array(
                'value' => 'x-iscii-de',
                'text' => 'ISCII Devanagari - x-iscii-de',
            ),
            array(
                'value' => 'x-iscii-gu',
                'text' => 'ISCII Gujarathi - x-iscii-gu',
            ),
            array(
                'value' => 'x-iscii-ka',
                'text' => 'ISCII Kannada - x-iscii-ka',
            ),
            array(
                'value' => 'x-iscii-ma',
                'text' => 'ISCII Malayalam - x-iscii-ma',
            ),
            array(
                'value' => 'x-iscii-or',
                'text' => 'ISCII Oriya - x-iscii-or',
            ),
            array(
                'value' => 'x-iscii-pa',
                'text' => 'ISCII Panjabi - x-iscii-pa',
            ),
            array(
                'value' => 'x-iscii-ta',
                'text' => 'ISCII Tamil - x-iscii-ta',
            ),
            array(
                'value' => 'x-iscii-te',
                'text' => 'ISCII Telugu - x-iscii-te',
            ),
            array(
                'value' => 'euc-jp',
                'text' => 'Japanese (EUC) - euc-jp',
            ),
            array(
                'value' => 'iso-2022-jp',
                'text' => 'Japanese (JIS) - iso-2022-jp',
            ),
            array(
                'value' => 'iso-2022-jp',
                'text' => 'Japanese (JIS-Allow 1 byte Kana - SO/SI) - iso-2022-jp',
            ),
            array(
                'value' => 'csISO2022JP',
                'text' => 'Japanese (JIS-Allow 1 byte Kana) - csISO2022JP',
            ),
            array(
                'value' => 'x-mac-japanese',
                'text' => 'Japanese (Mac) - x-mac-japanese',
            ),
            array(
                'value' => 'shift_jis',
                'text' => 'Japanese (Shift-JIS) - shift_jis',
            ),
            array(
                'value' => 'ks_c_5601-1987',
                'text' => 'Korean - ks_c_5601-1987',
            ),
            array(
                'value' => 'euc-kr',
                'text' => 'Korean (EUC) - euc-kr',
            ),
            array(
                'value' => 'iso-2022-kr',
                'text' => 'Korean (ISO) - iso-2022-kr',
            ),
            array(
                'value' => 'Johab',
                'text' => 'Korean (Johab) - Johab',
            ),
            array(
                'value' => 'x-mac-korean',
                'text' => 'Korean (Mac) - x-mac-korean',
            ),
            array(
                'value' => 'iso-8859-3',
                'text' => 'Latin 3 (ISO) - iso-8859-3',
            ),
            array(
                'value' => 'iso-8859-15',
                'text' => 'Latin 9 (ISO) - iso-8859-15',
            ),
            array(
                'value' => 'x-IA5-Norwegian',
                'text' => 'Norwegian (IA5) - x-IA5-Norwegian',
            ),
            array(
                'value' => 'IBM437',
                'text' => 'OEM United States - IBM437',
            ),
            array(
                'value' => 'x-IA5-Swedish',
                'text' => 'Swedish (IA5) - x-IA5-Swedish',
            ),
            array(
                'value' => 'windows-874',
                'text' => 'Thai (Windows) - windows-874',
            ),
            array(
                'value' => 'ibm857',
                'text' => 'Turkish (DOS) - ibm857',
            ),
            array(
                'value' => 'iso-8859-9',
                'text' => 'Turkish (ISO) - iso-8859-9',
            ),
            array(
                'value' => 'x-mac-turkish',
                'text' => 'Turkish (Mac) - x-mac-turkish',
            ),
            array(
                'value' => 'windows-1254',
                'text' => 'Turkish (Windows) - windows-1254',
            ),
            array(
                'value' => 'unicode',
                'text' => 'Unicode - unicode',
            ),
            array(
                'value' => 'unicodeFFFE',
                'text' => 'Unicode (Big-Endian) - unicodeFFFE',
            ),
            array(
                'value' => 'UTF-7',
                'text' => 'Unicode (UTF-7) - utf-7',
            ),
            array(
                'value' => 'UTF-8',
                'text' => 'Unicode (UTF-8) - utf-8',
            ),
            array(
                'value' => 'us-ascii',
                'text' => 'US-ASCII - us-ascii',
            ),
            array(
                'value' => 'windows-1258',
                'text' => 'Vietnamese (Windows) - windows-1258',
            ),
            array(
                'value' => 'ibm850',
                'text' => 'Western European (DOS) - ibm850',
            ),
            array(
                'value' => 'x-IA5',
                'text' => 'Western European (IA5) - x-IA5',
            ),
            array(
                'value' => 'iso-8859-1',
                'text' => 'Western European (ISO) - iso-8859-1',
            ),
            array(
                'value' => 'macintosh',
                'text' => 'Western European (Mac) - macintosh',
            ),
            array(
                'value' => 'Windows-1252',
                'text' => 'Western European (Windows) - Windows-1252',
            ),
        );

        return $this->outputArray($charsets);
    }
}
return 'modCharsetGetListProcessor';

