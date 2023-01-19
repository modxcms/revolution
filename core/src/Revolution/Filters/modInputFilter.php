<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Filters;

use MODX\Revolution\modElement;
use MODX\Revolution\modTag;
use MODX\Revolution\modX;

/**
 * Base input filter implementation for modElement processing, based on phX.
 *
 * @package MODX\Revolution\Filters
 */
class modInputFilter
{
    /** @var modX A reference to the modX instance. */
    public $modx = null;
    /** @var array An array of filter commands. */
    private $_commands = null;
    /** @var array An array of filter modifiers. */
    private $_modifiers = null;

    /**
     * Constructor for modInputFilter
     *
     * @param modX $modx A reference to the modX instance.
     */
    function __construct(modX &$modx)
    {
        $this->modx = &$modx;
    }

    /**
     * Filters a modElement before it is processed.
     *
     * @param modElement|modTag &$element The element to apply filtering to.
     */
    public function filter(&$element)
    {
        /* split commands and modifiers and store them as properties for the output filtering */
        $output = $element->get('name');
        $name = $output;
        $splitPos = strpos($output, ':');
        if ($splitPos !== false && $splitPos > 0) {
            $name = trim(substr($output, 0, $splitPos));
            $modifiers = substr($output, $splitPos);


            $chars = mb_str_split($modifiers);
            $depth = 0;
            $command = '';
            $commandModifiers = '';
            $inModifier = false;
            $skipNext = false;
            foreach ($chars as $i => $char) {
                if ($skipNext) {
                    $skipNext = false;
                    continue;
                }

                switch ($char) {
                    // `[[` indicates the start of a nested tag, which increases the depth.
                    // The character is added to either the command or the commandModifiers
                    case '[':
                        if ($chars[$i + 1] === '[') {
                            $depth++;
                            $inModifier ? $commandModifiers .= $char.$char : $command .= $char.$char;
                            $skipNext = true;
                        }
                        else {
                            $inModifier ? $commandModifiers .= $char : $command .= $char;
                        }
                        break;

                    // `]]` indicates the end of a nested tag, which decreases the depth.
                    // The character is added to either the command or the commandModifiers
                    case ']':
                        if ($chars[$i + 1] === ']') {
                            $inModifier ? $commandModifiers .= $char.$char : $command .= $char.$char;
                            $depth--;
                            $skipNext = true;
                        }
                        else {
                            $inModifier ? $commandModifiers .= $char : $command .= $char;
                        }

                        break;
                    // The `=` sign (equals) is the separator between the command and its modifiers
                    // The character is only added to the modifiers when we're inside a nested tag;
                    // otherwise the command name would include the `=` sign when opening the modifiers
                    case '=':
                        if ($inModifier) {
                            $commandModifiers .= $char;
                        }
                        break;
                    // The ` sign is either the start or end of a modifier.
                    // However, we may encounter a ` inside a NESTED tag, which we need to leave alone.
                    // That's why only when we're at the ROOT of the tag we toggle the inModifier flag.
                    // The character is also added to the modifiers to preserve nested tags.
                    case '`':
                        if ($depth === 0) {
                            $inModifier = !$inModifier;
                        }
                        else {
                            $inModifier ? $commandModifiers .= $char : $command .= $char;
                        }
                        break;
                    // The `:` sign (colon) is a separator between multiple commands in a string.
                    // We may also encounter it inside a NESTED tag, in which case need to preserve it.
                    // At the root level, this saves the command and its modifiers and resets it
                    // for the next pass to process further.
                    case ':':
                        if (!$inModifier) {
                            if (!empty($command)) {
                                $this->_commands[] = $command;
                                $this->_modifiers[] = $commandModifiers;
                            }
                            $command = $commandModifiers = '';
                        }
                        else {
                            $commandModifiers .= $char;
                        }
                        break;

                    // Any other characters are plain strings and thus need to be added to either
                    // the modifier or root command we're currently processing
                    default:
                        $inModifier ? $commandModifiers .= $char : $command .= $char;
                        break;
                }
            }

            // After a pass over the entire tag, make sure to save the last command and its modifiers if set.
            if (!empty($command)) {
                $this->_commands[] = $command;
                $this->_modifiers[] = $commandModifiers;
            }
        }

        $element->set('name', $name);
    }

    /**
     * Indicates if the element has any input filter commands.
     *
     * @return boolean True if the input filter has commands to execute.
     */
    public function hasCommands()
    {
        return !empty($this->_commands);
    }

    /**
     * Returns a list of filter input commands to be applied through output filtering.
     *
     * @return array|null An array of filter commands or null if no commands exist.
     */
    public function & getCommands()
    {
        return $this->_commands;
    }

    /**
     * Returns a list of filter input modifiers corresponding to the input commands.
     *
     * @return array|null An array of filter modifiers for corresponding commands.
     */
    public function & getModifiers()
    {
        return $this->_modifiers;
    }
}
