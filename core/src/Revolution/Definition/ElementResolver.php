<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modElement;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modScript;
use MODX\Revolution\modX;

class ElementResolver implements ElementResolverInterface, DatabasePresenceInvalidatorInterface
{
    private modX $modx;
    private DefinitionRegistry $registry;
    private DefinitionDatabaseFacts $databaseFacts;
    private array $lastDecision = [];
    private array $databasePresence = [];

    public function __construct(modX $modx, DefinitionRegistry $registry)
    {
        $this->modx = $modx;
        $this->registry = $registry;
        $this->databaseFacts = new DefinitionDatabaseFacts($modx);
    }

    public function getElement(string $class, string $name): ?modElement
    {
        $requestedClass = $class;
        $class = $this->modx->loadClass($class);
        if (!is_string($class) || !is_a($class, modElement::class, true)) {
            $this->lastDecision = [
                'winner' => null,
                'reason' => 'unsupported-element-class',
                'class' => $requestedClass,
                'name' => $name,
            ];

            return null;
        }

        $definition = $this->registry->getDefinition($class, $name);
        if (!$definition) {
            $element = $this->loadDatabaseElement($class, $name);
            $this->lastDecision = [
                'winner' => $element ? 'database' : null,
                'reason' => $element ? 'database-only' : 'not-found',
                'class' => $class,
                'name' => $name,
            ];

            $this->attachDatabaseMetadata($element, $this->lastDecision);

            return $element;
        }

        $presenceKey = $class . ':' . DefinitionRegistry::normalizeName($name);
        if (!array_key_exists($presenceKey, $this->databasePresence)) {
            $databaseExists = $this->databaseFacts->elementExists($class, $name);
            if ($databaseExists === null) {
                throw new \RuntimeException('Could not determine database element precedence.');
            }
            $this->databasePresence[$presenceKey] = $databaseExists;
        }
        $databaseExists = $this->databasePresence[$presenceKey];

        if ($databaseExists) {
            $element = $this->loadDatabaseElement($class, $name);
            $this->lastDecision = [
                'winner' => $element ? 'database' : null,
                'reason' => $element ? 'database-default' : 'database-load-denied',
                'class' => $class,
                'name' => $name,
                'collision' => true,
                'definition' => $definition['key'],
            ];

            $this->attachDatabaseMetadata($element, $this->lastDecision);

            return $element;
        }

        $element = $this->hydrateDiskElement($definition);
        $this->lastDecision = [
            'winner' => 'disk',
            'reason' => 'disk-only',
            'class' => $class,
            'name' => $name,
            'collision' => false,
            'definition' => $definition['key'],
        ];
        $element->setDefinitionMetadata([
            'source' => 'disk',
            'package' => $definition['package'],
            'manifest' => $definition['manifest'],
            'source_file' => $definition['file'],
            'normalized_key' => $definition['normalized_name'],
            'definition_key' => $definition['key'],
            'collision' => false,
            'decision' => $this->lastDecision['reason'],
            'property_sets' => $definition['property_sets'],
            'media_source' => $definition['media_source'],
        ]);

        return $element;
    }

    public function getLastDecision(): array
    {
        return $this->lastDecision;
    }

    /**
     * A database write can make a cached absence stale within the same request.
     */
    public function invalidateDatabasePresence(string $class): void
    {
        $class = $this->modx->loadClass($class);
        if (!is_string($class) || !is_a($class, modElement::class, true)) {
            return;
        }

        $classes = [$class];
        foreach (class_parents($class) as $parent) {
            if (is_a($parent, modElement::class, true)) {
                $classes[] = $parent;
            }
        }
        foreach (array_keys($this->databasePresence) as $key) {
            foreach ($classes as $candidate) {
                if (str_starts_with($key, $candidate . ':')) {
                    unset($this->databasePresence[$key]);
                    break;
                }
            }
        }
    }

    private function loadDatabaseElement(string $class, string $name): ?modElement
    {
        if (isset($this->modx->sourceCache[$class][$name])) {
            $snapshot = $this->modx->sourceCache[$class][$name];
            $element = $this->modx->newObject($class);
            $element->fromArray($snapshot['fields'], '', true, true);
            $element->setPolicies($snapshot['policies']);

            if (!empty($snapshot['source']['class_key'])) {
                $source = $this->modx->newObject($snapshot['source']['class_key']);
                $source->fromArray($snapshot['source'], '', true, true);
                $element->addOne($source, 'Source');
            }

            return $element;
        }

        $element = $this->modx->getObjectGraph($class, ['Source' => []], ['name' => $name], true);
        if ($element && array_key_exists($class, $this->modx->sourceCache)) {
            $this->modx->sourceCache[$class][$name] = [
                'fields' => $element->toArray(),
                'policies' => $element->getPolicies(),
                'source' => $element->Source ? $element->Source->toArray() : [],
            ];
        }

        return $element instanceof modElement ? $element : null;
    }

    private function hydrateDiskElement(array $definition): modElement
    {
        $element = $this->modx->newObject($definition['class']);
        $element->set('name', $definition['name']);
        $element->setContent($definition['content']);
        $element->setProperties($definition['properties']);
        $element->set('static', false);

        if ($element instanceof modScript) {
            $element->_scriptName = DefinitionRegistry::scriptName($definition['key'], $definition['content_hash']);
            $element->_scriptCacheKey = DefinitionRegistry::scriptCacheKey(
                $definition['key'],
                $definition['content_hash']
            );
        }
        if ($element instanceof modPlugin) {
            $element->set('disabled', false);
        }

        return $element;
    }

    private function attachDatabaseMetadata(?modElement $element, array $decision): void
    {
        if (!$element) {
            return;
        }
        $element->setDefinitionMetadata([
            'source' => 'database',
            'package' => null,
            'manifest' => null,
            'source_file' => $element->get('static_file') ?: null,
            'normalized_key' => DefinitionRegistry::normalizeName((string) $element->get('name')),
            'definition_key' => 'database:' . $element->_class . ':' . $element->get('id'),
            'collision' => (bool) ($decision['collision'] ?? false),
            'decision' => $decision['reason'],
        ]);
    }
}
