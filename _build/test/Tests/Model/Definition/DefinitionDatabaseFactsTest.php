<?php

namespace MODX\Revolution\Tests\Model\Definition;

use ArrayObject;
use MODX\Revolution\Definition\DefinitionDatabaseFacts;
use MODX\Revolution\modX;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

class DefinitionDatabaseFactsTest extends XTestCase
{
    public function testBulkSnapshotsNormalizeDeduplicateAndBatchQueries(): void
    {
        $batches = new ArrayObject();
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTableName', 'prepare'])
            ->getMock();
        $modx->method('getTableName')->willReturn('modx_event');
        $modx->method('prepare')->willReturnCallback(function () use ($batches) {
            return new class ($batches) {
                private ArrayObject $batches;

                public function __construct(ArrayObject $batches)
                {
                    $this->batches = $batches;
                }

                public function execute(array $params): bool
                {
                    $this->batches->append($params);

                    return true;
                }

                public function fetch(): false
                {
                    return false;
                }
            };
        });
        $names = [];
        for ($index = 0; $index < 501; $index++) {
            $names[] = sprintf('Event%03d', $index);
        }
        $names[] = 'EVENT000';

        $snapshots = (new DefinitionDatabaseFacts($modx))->eventSnapshots($names);

        $this->assertCount(501, $snapshots);
        $this->assertCount(2, $batches);
        $this->assertCount(DefinitionDatabaseFacts::BULK_QUERY_CHUNK_SIZE, $batches[0]);
        $this->assertCount(1, $batches[1]);
        $this->assertArrayHasKey('event000', $snapshots);
        $this->assertFalse($snapshots['event000']);
    }
}
