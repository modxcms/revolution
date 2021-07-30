<?php

namespace MODX\Revolution;

use xPDO\xPDO;

/**
 * Class modDeprecatedMethod
 *
 * @property string $class_name
 * @property string $method_name
 * @property string $message
 *
 * @package MODX\Revolution
 */
class modDeprecatedMethod extends \xPDO\Om\xPDOSimpleObject
{
    private $callers = [];

    public function addCaller(string $class, string $function, string $file = null, int $line = null)
    {
        $def = "{$class}::{$function}::{$file}::{$line}";

        // Check if we have it in memory already
        if (array_key_exists($def, $this->callers)) {
            $caller = $this->callers[$def];
            $caller->set('call_count', $caller->get('call_count') + 1);
            return;
        }

        $callerDef = !empty($class) ? "{$class}::{$function}" : $function;

        // If the method is new, we can't yet have a callers instance yet, so create it
        // and keep in memory + addMany so it is saved when the method is saved
        if ($this->isNew()) {
            $this->callers[$def] = $this->createNewCaller($callerDef, $file, $line);
            $this->addMany($this->callers[$def], 'Callers');
            return;
        }

        // Check for existing caller instances
        $caller = $this->xpdo->getObject(modDeprecatedCall::class, [
            'method' => $this->get('id'),
            'caller' => $callerDef,
            'caller_file' => $file,
            'caller_line' => $line,
        ]);
        if ($caller) {
            $caller->set('call_count', $caller->get('call_count') + 1);
            $this->callers[$def] = $caller;
            // this makes sure the object is saved on success
            $this->addMany($this->callers[$def], 'Callers');
            return;
        }

        // Create a new caller object
        $this->callers[$def] = $this->createNewCaller($callerDef, $file, $line);
        $this->addMany($this->callers[$def], 'Callers');
    }

    private function createNewCaller(string $callerDef, string $file, int $line): modDeprecatedCall
    {
        /** @var modDeprecatedCall $caller */
        $caller = $this->xpdo->newObject(modDeprecatedCall::class);
        $caller->fromArray([
            'call_count' => 1,
            'caller' => $callerDef,
            'caller_file' => $file,
            'caller_line' => $line,
        ]);

        return $caller;
    }
}
