<?php

namespace MODX\Revolution\Definition;

use JsonException;
use Throwable;

class DefinitionRegistryCli
{
    private const COMMANDS = ['compile', 'explain', 'hash', 'list', 'validate', 'warm'];

    private DefinitionRegistryDeployment $deployment;

    public function __construct(DefinitionRegistryDeployment $deployment)
    {
        $this->deployment = $deployment;
    }

    public function run(array $arguments, $stdout = null, $stderr = null): int
    {
        $stdout = $stdout ?? STDOUT;
        $stderr = $stderr ?? STDERR;
        $command = isset($arguments[0]) && is_string($arguments[0]) ? $arguments[0] : '';
        try {
            if (!in_array($command, self::COMMANDS, true)) {
                throw new DefinitionRegistryToolException(
                    'Command must be one of: ' . implode(', ', self::COMMANDS),
                    2,
                    'invalid-command'
                );
            }
            $options = $this->parseOptions(array_slice($arguments, 1));
            $result = $this->execute($command, $options);
            $written = $this->writeJson($stdout, [
                'command' => $command,
                'ok' => true,
                'result' => $result,
            ]);

            if ($written) {
                return 0;
            }
            $this->writeJson($stderr, [
                'command' => $command,
                'error' => [
                    'code' => 'stdout-write-failed',
                    'message' => 'Could not write command output to stdout.',
                ],
                'ok' => false,
            ]);

            return 5;
        } catch (DefinitionRegistryToolException $exception) {
            $this->writeJson($stderr, [
                'command' => $command === '' ? null : $command,
                'error' => [
                    'code' => $exception->getErrorCode(),
                    'message' => $exception->getMessage(),
                ],
                'ok' => false,
            ]);

            return $exception->getExitStatus();
        } catch (Throwable $exception) {
            $this->writeJson($stderr, [
                'command' => $command === '' ? null : $command,
                'error' => [
                    'code' => 'unexpected-runtime-failure',
                    'message' => $exception->getMessage(),
                ],
                'ok' => false,
            ]);

            return 5;
        }
    }

    private function execute(string $command, array $options): array
    {
        $allowedOptions = [
            'compile' => [],
            'explain' => ['key', 'name', 'type'],
            'hash' => [],
            'list' => ['package', 'type'],
            'validate' => [],
            'warm' => [],
        ];
        $unknownOptions = array_diff(array_keys($options), $allowedOptions[$command]);
        if ($unknownOptions) {
            throw new DefinitionRegistryToolException(
                "Unsupported option for {$command}: --" . reset($unknownOptions),
                2,
                'unsupported-option'
            );
        }

        switch ($command) {
            case 'compile':
                return $this->deployment->compile();
            case 'explain':
                $hasKey = isset($options['key']);
                $hasPublicIdentity = isset($options['type']) || isset($options['name']);
                if ($hasKey === $hasPublicIdentity) {
                    throw new DefinitionRegistryToolException(
                        'explain requires either --key or both --type and --name.',
                        2,
                        'invalid-selector'
                    );
                }

                return $this->deployment->explain(
                    $options['key'] ?? null,
                    $options['type'] ?? null,
                    $options['name'] ?? null
                );
            case 'hash':
                return $this->deployment->hash();
            case 'list':
                return $this->deployment->list($options['package'] ?? null, $options['type'] ?? null);
            case 'validate':
                return $this->deployment->validate();
            case 'warm':
                return $this->deployment->warm();
        }

        throw new DefinitionRegistryToolException('Unsupported command.', 2, 'invalid-command');
    }

    private function parseOptions(array $arguments): array
    {
        $options = [];
        while ($arguments) {
            $option = array_shift($arguments);
            if (!is_string($option) || strncmp($option, '--', 2) !== 0 || strlen($option) === 2) {
                throw new DefinitionRegistryToolException('Options must use --name value.', 2, 'malformed-option');
            }
            $name = substr($option, 2);
            if (isset($options[$name])) {
                throw new DefinitionRegistryToolException("Duplicate option: --{$name}", 2, 'duplicate-option');
            }
            $value = array_shift($arguments);
            if (!is_string($value) || $value === '' || strncmp($value, '--', 2) === 0) {
                throw new DefinitionRegistryToolException(
                    "Option --{$name} requires a value.",
                    2,
                    'missing-option-value'
                );
            }
            $options[$name] = $value;
        }

        ksort($options, SORT_STRING);

        return $options;
    }

    private function writeJson($stream, array $document): bool
    {
        try {
            $json = json_encode(
                $this->canonicalize($document),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
            ) . "\n";

            return fwrite($stream, $json) === strlen($json);
        } catch (JsonException) {
            $fallback = '{"error":{"code":"json-encoding-failed",'
                . '"message":"Could not encode command output."},"ok":false}' . "\n";
            fwrite($stream, $fallback);

            return false;
        }
    }

    private function canonicalize($value)
    {
        if (!is_array($value)) {
            return $value;
        }
        if (array_is_list($value)) {
            return array_map([$this, 'canonicalize'], $value);
        }
        ksort($value, SORT_STRING);
        foreach ($value as &$item) {
            $item = $this->canonicalize($item);
        }
        unset($item);

        return $value;
    }
}
