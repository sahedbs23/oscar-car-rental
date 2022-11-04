<?php

namespace Validation\Rules;

use App\Validation\Rules\Rules;
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
    public ?Rules $rules;

    public function setUp() :void
    {
        $this->rules = new Rules();
    }

    public function tearDown():void
    {
        $this->rules = null;
    }

    public function ruleDataProvider()
    {
        return [
            ['location','Calahonda',false], // Calahonda is a string
            ['num_of_doors', 123,true], // 123 is not a string
            ['num_of_seats','1&&1',false], // '1&&1' is a string.
        ];
    }


    /**
     * @dataProvider ruleDataProvider
     * @return void
     */
    public function testString($key, $value, $expected)
    {
        [$failed] = $this->rules->string($key, $value);
        $this->assertEquals($expected, $failed );
    }


    /**
     * @dataProvider ruleDataProvider
     * @return void
     */
    public function testNumeric()
    {
        [$failed] = $this->rules->numeric('num_of_doors', '123');
        $this->assertFalse( $failed );


        [$failed] = $this->rules->numeric('num_of_doors', 'Oscar Car');
        $this->assertTrue( $failed );
    }


    /**
     * @dataProvider ruleDataProvider
     * @return void
     */
    public function testRequired()
    {
        [$failed] = $this->rules->required('num_of_doors', null);
        $this->assertTrue( $failed );

        [$failed] = $this->rules->required('num_of_doors', '');
        $this->assertTrue( $failed );

        [$failed] = $this->rules->required('num_of_doors', []);
        $this->assertTrue( $failed );

        [$failed] = $this->rules->required('num_of_doors', [1, 2]);
        $this->assertFalse( $failed );

        [$failed] = $this->rules->required('num_of_doors', 'a');
        $this->assertFalse( $failed );

    }
}
