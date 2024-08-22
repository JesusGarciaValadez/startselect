<?php

namespace Tests\Unit\app\ValueObjects;

use App\Enums\Currency;
use App\ValueObjects\MoneyValueObject;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MoneyValueObjectTest extends TestCase
{
    #[Test]
    public function it_is_created(): void
    {
        $amount = 100;
        $currency = 'usd';

        $money = new MoneyValueObject($amount, $currency);
        $this->assertEquals(100, $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency());
    }

    #[Test]
    public function it_has_an_invalid_currency_code(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $amount = 100;
        $currency = 'INVALID';

        new MoneyValueObject($amount, $currency);
    }

    #[Test]
    public function it_adds_more_money(): void
    {
        $money1 = new MoneyValueObject(amount: 50, currency: 'USD');
        $money2 = new MoneyValueObject(amount: 20, currency: 'USD');

        $result = $money1->add($money2->getAmount());

        $this->assertEquals(70, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function it_subtracts_money(): void
    {
        $money1 = new MoneyValueObject(amount: 50, currency: 'USD');
        $money2 = new MoneyValueObject(amount: 20, currency: 'USD');

        $result = $money1->subtract($money2->getAmount());

        $this->assertEquals(30, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function it_multiplies_money(): void
    {
        $money = new MoneyValueObject(amount: 50, currency: 'USD');
        $result = $money->multiply(2);

        $this->assertEquals(100, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function it_divides_in_parts(): void
    {
        $money = new MoneyValueObject(amount: 50, currency: 'USD');
        $result = $money->divide(2);

        $this->assertEquals(25, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function it_applies_a_discount(): void
    {
        $money = new MoneyValueObject(amount: 100, currency: 'USD');
        $result = $money->applyDiscount(10); // 10% discount

        $this->assertEquals(90, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function test_currency_conversion(): void
    {
        $euros = new MoneyValueObject(amount: 100, currency: 'EUR');
        $result = $euros->convertTo('CHF');

        $this->assertEquals(195.58, $result->getAmount());
        $this->assertEquals('CHF', $result->getCurrency());
    }

    #[Test]
    public function test_invalid_currency_conversion(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $euros = new MoneyValueObject(amount: 100, currency: 'EUR');
        $euros->convertTo('USD');
    }

    #[Test]
    public function it_tests_money_comparisons(): void
    {
        $money1 = new MoneyValueObject(amount: 50, currency: 'USD');
        $money2 = new MoneyValueObject(amount: 20, currency: 'USD');
        $money3 = new MoneyValueObject(amount: 80, currency: 'USD');

        $min = MoneyValueObject::min([$money1, $money2, $money3]);
        $max = MoneyValueObject::max([$money1, $money2, $money3]);
        $total = MoneyValueObject::total([$money1, $money2, $money3]);
        $average = MoneyValueObject::average([$money1, $money2, $money3]);

        $this->assertEquals(20, $min->getAmount());
        $this->assertEquals(80, $max->getAmount());
        $this->assertEquals(150, $total->getAmount());
        $this->assertEquals(50, $average->getAmount());
    }

    #[Test]
    public function it_tests_money_zero_amount(): void
    {
        $money = new MoneyValueObject(amount: 0, currency: 'USD');
        $this->assertEquals(0, $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency());
    }

    #[Test]
    public function it_tests_money_negative_amount(): void
    {
        $money = new MoneyValueObject(amount: -50, currency: 'USD');
        $this->assertEquals(-50, $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency());
    }

    #[Test]
    public function it_tests_money_large_amount(): void
    {
        $money = new MoneyValueObject(amount: 9999999999.99, currency: 'USD');
        $result = $money->multiply(2);

        $this->assertEquals(19999999999.98, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function it_test_money_floating_point_precision(): void
    {
        $money = new MoneyValueObject(amount: 0.1, currency: 'USD');
        $result = $money->multiply(3);

        // Due to floating-point arithmetic, assert with a small tolerance
        $this->assertEquals(0.3, $result->getAmount(), '', 0.00001);
    }

    #[Test]
    public function it_test_invalid_currency_code(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $money = new MoneyValueObject(amount: 100, currency: 'INVALID');
    }

    #[Test]
    public function it_tests_empty_amount(): void
    {
        $this->expectException(\TypeError::class); // or use custom validation exceptions

        // Assuming your Operation class requires both amount and currency
        new MoneyValueObject(amount: null, currency: 'USD');
    }

    #[Test]
    public function it_tests_empty_currency(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $money = new MoneyValueObject(amount: 100, currency: '');
    }

    #[Test]
    public function it_tests_missing_amount(): void
    {
        $this->expectException(\TypeError::class);

        new MoneyValueObject(currency: 'USD');
    }

    #[Test]
    public function it_tests_money_non_numeric_amount(): void
    {
        $this->expectException(\TypeError::class);

        new MoneyValueObject(amount: 'EUR', currency: 'USD');
    }

    #[Test]
    public function it_tests_missing_currency(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new MoneyValueObject(amount: 100);
    }

    #[Test]
    public function it_tests_chained_operations(): void
    {
        $money1 = new MoneyValueObject(amount: 100, currency: 'USD');
        $money2 = new MoneyValueObject(amount: 50, currency: 'USD');

        $result = $money1->add($money2->getAmount())->subtract((new MoneyValueObject(amount: 25, currency: 'USD'))
            ->getAmount());

        $this->assertEquals(125, $result->getAmount());
    }

    #[Test]
    public function it_tests_apply_full_discount(): void
    {
        $money = new MoneyValueObject(amount: 100, currency: 'USD');
        $result = $money->applyDiscount(100);

        $this->assertEquals(0, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    #[Test]
    public function it_gets_an_array_from_moneyValueObject(): void
    {
        $money = new MoneyValueObject(amount: 0, currency: 'USD');

        $this->assertEquals(['amount' => 0, 'currency' => 'USD'], $money->toArray());
    }

    #[Test]
    public function it_gets_the_currency_id(): void
    {
        $money = new MoneyValueObject(amount: 0, currency: 'USD');

        $this->assertEquals(Currency::key('USD'), $money->getCurrencyId());
    }
}
