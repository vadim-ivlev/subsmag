<?php

namespace Rg\ApiBundle\Service;

use Rg\ApiBundle\Cart\CartException;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CartItemValidator
{
    const MONTHFIRSTHALF = 2048;
    const MONTHSECONDHALF = 32;
    const FIRSTHALF = 4032;
    const SECONDHALF = 63;
    const FULLYEAR = 4095;

    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param \stdClass $product
     * @param Tariff $tariff
     * @throws CartException
     */
    public function validateProduct(\stdClass $product, Tariff $tariff)
    {
        /*
        $product->first_month,
        $product->duration,
        $product->year,
        $product->tariff,
        $product->quantity
        */
        if ($tariff->getTimeunit()->getYear() != $product->year)
            throw new CartException('Год позиции должен соответствовать тарифу.');

        if (!$this->validateQty($product->quantity))
            throw new CartException('Количество должно быть целым положительным ненулевым числом.');

        try {
            $this->validateFirstMonthAndDuration($tariff, $product);
        } catch (CartException $e) {
            throw new CartException($e->getMessage(), ', :' . join(', ', (array) $product));
        }
    }

    public function validateId(int $id)
    {
        $options = [ 'options' => [ 'min_range' => 1 ], ];

        return filter_var($id, FILTER_VALIDATE_INT, $options);
    }

    private function validateFirstMonthAndDuration(Tariff $tariff, \stdClass $product)
    {
        $first_month = $product->first_month;
        $duration = $product->duration;

        $options = [
            'options' => [
                'min_range' => 1,
                'max_range' => 12,
            ],
        ];

        if (!filter_var($first_month, FILTER_VALIDATE_INT, $options))
            throw new CartException('Первый месяц должен быть в диапазоне 1-12');

        $bitmask = $tariff->getTimeunit()->getBitmask();
        switch ($bitmask) {
            case self::FULLYEAR:
                if ( !($first_month == 1 and $duration == 12) )
                    throw new CartException('Первый месяц годового тарифа должен быть 1, продолжительность 12');
                break;
            case self::FIRSTHALF:
                if (!($first_month == 1 and $duration == 6))
                    throw new CartException('Первый месяц тарифа первого полугодия должен быть 1, продолжительность 6');
                break;
            case self::SECONDHALF:
                if (!($first_month == 7 and $duration == 6))
                    throw new CartException('Первый месяц тарифа второго полугодия должен быть 7, продолжительность 6');
                break;
            case self::MONTHFIRSTHALF:
                if ( !in_array($first_month, range(1, 6)) )
                    throw new CartException('Первый месяц месячного тарифа первого полугодия должен быть в диапазоне от 1 до 6');

                $last_month = $first_month + $duration - 1;
                if ($last_month > 6)
                    throw new CartException('Нельзя использовать тариф месяца I полугодия для второго полугодия');
                break;
            case self::MONTHSECONDHALF:
                if ( !in_array($first_month, range(7, 12)))
                    throw new CartException('Первый месяц месячного тарифа второго полугодия должен быть в диапазоне от 7 до 12');

                $last_month = $first_month + $duration - 1;
                if ($last_month > 12)
                    throw new CartException('Номер последнего месяца не может быть больше 12');
                break;
            default:
                throw new \Exception('Unknown bitmask of timeunit!');
        }

        return true;
    }

    public function validateQty($qty)
    {
        $options = [
            'options' => [
                'min_range' => 1,
            ],
        ];

        return filter_var($qty, FILTER_VALIDATE_INT, $options);
    }

}