<?php
namespace MODX\Revolution\Tests\Cases\Modx;

use MODX\Revolution\modX;
use PHPUnit\Framework\TestCase;
use stdClass;

class ReplaceReservedTest extends TestCase
{
    public function testEmptyString()
    {
        $this->assertEquals(
            '',
            modX::replaceReserved('')
        );

        $this->assertEquals(
            '',
            modX::replaceReserved(new stdClass)
        );

        $this->assertEquals(
            '',
            modX::replaceReserved(null)
        );
    }

    public function testDefaultProperty()
    {
        $this->assertEquals(
            'clear string',
            modX::replaceReserved('clear string')
        );

        $source = '[[tag? &param=`value`]]';
        $replacing = '&#91;&#91;tag? &param=&#96;value&#96;&#93;&#93;';

        $this->assertEquals(
            $replacing,
            modX::replaceReserved($source)
        );

        $this->assertEquals(
            [$replacing => $replacing],
            modX::replaceReserved([
                $source => $source
            ])
        );

        $this->assertEquals(
            [
                $replacing => [
                    $replacing => $replacing
                ]
            ],
            modX::replaceReserved([
                $source => [
                    $source => $source
                ]
            ])
        );
    }

    public function testChangingProperty()
    {
        $property = ['[' => '', ']' => '&#93;'];

        $this->assertEquals(
            'clear string',
            modX::replaceReserved('clear string', $property)
        );

        $source = '[[tag? &param=`value`]]';
        $replacing = 'tag? &param=`value`&#93;&#93;';

        $this->assertEquals(
            $replacing,
            modX::replaceReserved($source, $property)
        );

        $this->assertEquals(
            [$replacing => $replacing],
            modX::replaceReserved(
                [
                    $source => $source
                ],
                $property
            )
        );

        $this->assertEquals(
            [
                $replacing => [
                    $replacing => $replacing
                ]
            ],
            modX::replaceReserved(
                [
                    $source => [
                        $source => $source
                    ]
                ],
                $property
            )
        );
    }
}
