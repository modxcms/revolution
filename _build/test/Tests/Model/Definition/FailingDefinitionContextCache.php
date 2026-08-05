<?php

namespace MODX\Revolution\Tests\Model\Definition;

use xPDO\Cache\xPDOFileCache;

class FailingDefinitionContextCache extends xPDOFileCache
{
    public function set($key, $var, $expire = 0, $options = [])
    {
        return false;
    }
}
