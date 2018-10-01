<?php

class ReplaceBracesTest extends \PHPUnit\Framework\TestCase
{

    public function __construct()
    {
        parent::__construct();

        include_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/core/model/modx/modx.class.php';
    }

    public function testEmptyString()
    {
        $this->assertEquals(
            '',
            modX::replaceBraces('')
        );

        $this->assertEquals(
            '',
            modX::replaceBraces(new stdClass)
        );

        $this->assertEquals(
            '',
            modX::replaceBraces(null)
        );
    }

    public function testDefaultProperty()
    {
        $this->assertEquals(
            'clear string',
            modX::replaceBraces('clear string')
        );

        $source = '[[tag? &param=`value`]]';
        $replacing = '&#91;&#91;tag? &param=&#96;value&#96;&#93;&#93;';

        $this->assertEquals(
            $replacing,
            modX::replaceBraces($source)
        );

        $this->assertEquals(
            array($replacing => $replacing),
            modX::replaceBraces(array(
                $source => $source
            ))
        );

        $this->assertEquals(
            array(
                $replacing => array(
                    $replacing => $replacing
                )
            ),
            modX::replaceBraces(array(
                $source => array(
                    $source => $source
                )
            ))
        );
    }

    public function testChangingProperty()
    {
        $property = array('[' => '', ']' => '&#93;');

        $this->assertEquals(
            'clear string',
            modX::replaceBraces('clear string', $property)
        );

        $source = '[[tag? &param=`value`]]';
        $replacing = 'tag? &param=`value`&#93;&#93;';

        $this->assertEquals(
            $replacing,
            modX::replaceBraces($source, $property)
        );

        $this->assertEquals(
            array($replacing => $replacing),
            modX::replaceBraces(
                array(
                    $source => $source
                ),
                $property
            )
        );

        $this->assertEquals(
            array(
                $replacing => array(
                    $replacing => $replacing
                )
            ),
            modX::replaceBraces(
                array(
                    $source => array(
                        $source => $source
                    )
                ),
                $property
            )
        );
    }
}
