<?php

namespace MODX\Revolution\Definition;

interface DatabasePresenceInvalidatorInterface
{
    public function invalidateDatabasePresence(string $class): void;
}
