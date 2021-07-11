<?php

namespace Flat3\Lodata\Tests\Unit\Parser;

use Flat3\Lodata\Controller\Transaction;
use Flat3\Lodata\Expression\Evaluate;
use Flat3\Lodata\Expression\Parser\Filter;
use Flat3\Lodata\Tests\TestCase;

class EvaluateTest extends TestCase
{
    public function test_0()
    {
        $this->assertSameExpression(1, '1');
    }

    public function test_1()
    {
        $this->assertSameExpression(1.1, '1.1');
    }

    public function test_2()
    {
        $this->assertSameExpression('a', "'a'");
    }

    public function test_3()
    {
        $this->assertSameExpression(null, 'null');
    }

    public function test_4()
    {
        $this->assertNan($this->evaluate('NaN'));
    }

    public function test_5()
    {
        $this->assertInfinite($this->evaluate('INF'));
    }

    public function test_6()
    {
        $this->assertInfinite(-$this->evaluate('-INF'));
    }

    public function test_7()
    {
        $this->assertSameExpression('2001-01-01', '2001-01-01');
    }

    public function test_8()
    {
        $this->assertSameExpression('14:14:14.000000', '14:14:14');
    }

    public function test_9()
    {
        $this->assertSameExpression('14:14:14.000001', '14:14:14.000001');
    }

    public function test_10()
    {
        $this->assertSameExpression('2020-01-01T23:23:23+00:00', '2020-01-01T23:23:23+00:00');
    }

    public function test_11()
    {
        $this->assertSameExpression(true, 'true');
    }

    public function test_12()
    {
        $this->assertSameExpression(false, 'false');
    }

    public function test_13()
    {
        $this->assertSameExpression(367485.122, 'P4DT6H4M45.121999999974S');
    }

    public function test_14()
    {
        $this->assertGuid(
            '2D1B80E8-0DAD-4EE7-AB6F-AE9FEC896290',
            $this->evaluate('2D1B80E8-0DAD-4EE7-AB6F-AE9FEC896290')
        );
    }

    public function test_20()
    {
        $this->assertTrueExpression('1 eq 1');
    }

    public function test_21()
    {
        $this->assertTrueExpression('1 eq 1.0');
    }

    public function test_22()
    {
        $this->assertTrueExpression('1 eq 1.0');
    }

    public function test_23()
    {
        $this->assertFalseExpression('1 eq 1.2');
    }

    public function test_24()
    {
        $this->assertTrueExpression('null eq null');
    }

    public function test_25()
    {
        $this->assertFalseExpression('null eq 4');
    }

    public function test_26()
    {
        $this->assertTrueExpression('-INF eq -INF');
    }

    public function test_27()
    {
        $this->assertTrueExpression('INF eq INF');
    }

    public function test_28()
    {
        $this->assertFalseExpression('NaN eq NaN');
    }

    public function test_29()
    {
        $this->assertTrueExpression('4 gt 3');
    }

    public function test_30()
    {
        $this->assertTrueExpression('true gt false');
    }

    public function test_31()
    {
        $this->assertTrueExpression('INF gt 28347395734');
    }

    public function test_32()
    {
        $this->assertTrueExpression('-12387238 gt -INF');
    }

    public function test_33()
    {
        $this->assertFalseExpression('4 gt null');
    }

    public function test_34()
    {
        $this->assertFalseExpression('null gt 4');
    }

    public function test_35()
    {
        $this->assertTrueExpression('4 ge 3');
    }

    public function test_36()
    {
        $this->assertTrueExpression('true ge false');
    }

    public function test_37()
    {
        $this->assertTrueExpression('INF ge 28347395734');
    }

    public function test_38()
    {
        $this->assertTrueExpression('-12387238 ge -INF');
    }

    public function test_39()
    {
        $this->assertFalseExpression('4 ge null');
    }

    public function test_40()
    {
        $this->assertFalseExpression('null ge 4');
    }

    public function test_41()
    {
        $this->assertTrueExpression('3 lt 4');
    }

    public function test_42()
    {
        $this->assertTrueExpression('false lt true');
    }

    public function test_43()
    {
        $this->assertTrueExpression('28347395734 lt INF');
    }

    public function test_44()
    {
        $this->assertTrueExpression('-INF lt -12387238');
    }

    public function test_45()
    {
        $this->assertFalseExpression('null lt 4');
    }

    public function test_46()
    {
        $this->assertFalseExpression('4 lt null');
    }

    public function test_47()
    {
        $this->assertTrueExpression('3 le 4');
    }

    public function test_48()
    {
        $this->assertTrueExpression('false le true');
    }

    public function test_49()
    {
        $this->assertTrueExpression('28347395734 le INF');
    }

    public function test_50()
    {
        $this->assertTrueExpression('-INF le -12387238');
    }

    public function test_51()
    {
        $this->assertFalseExpression('null le 4');
    }

    public function test_52()
    {
        $this->assertFalseExpression('4 le null');
    }

    public function test_53()
    {
        $this->assertTrueExpression('true and true');
    }

    public function test_54()
    {
        $this->assertFalseExpression('true and false');
    }

    public function test_55()
    {
        $this->assertFalseExpression('false and null');
    }

    public function test_56()
    {
        $this->assertNullExpression('true and null');
    }

    public function test_57()
    {
        $this->assertTrueExpression('true or true');
    }

    public function test_58()
    {
        $this->assertTrueExpression('true or false');
    }

    public function test_59()
    {
        $this->assertTrueExpression('true or null');
    }

    public function test_60()
    {
        $this->assertNullExpression('false or null');
    }

    public function test_61()
    {
        $this->assertTrueExpression('not false');
    }

    public function test_62()
    {
        $this->assertFalseExpression('not true');
    }

    public function test_63()
    {
        $this->assertNullExpression('not null');
    }

    public function test_64()
    {
        $this->assertTrueExpression('1 in (1,2)');
    }

    public function test_65()
    {
        $this->assertFalseExpression('1 in (3,2)');
    }

    public function test_66()
    {
        $this->assertTrueExpression("1 in ('1',2)");
    }

    public function assertTrueExpression($expression): void
    {
        $this->assertTrue($this->evaluate($expression));
    }

    public function assertFalseExpression($expression): void
    {
        $this->assertFalse($this->evaluate($expression));
    }

    public function assertNullExpression($expression): void
    {
        $this->assertNull($this->evaluate($expression));
    }

    public function assertSameExpression($expected, $expression): void
    {
        $this->assertSame($expected, $this->evaluate($expression));
    }

    public function evaluate(string $expression, ?array $item = null)
    {
        $transaction = new Transaction();
        $parser = new Filter($transaction);
        $tree = $parser->generateTree($expression);

        return Evaluate::eval($tree, $item);
    }
}