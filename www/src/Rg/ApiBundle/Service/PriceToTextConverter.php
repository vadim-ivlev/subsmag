<?php
/**
 * Copied and refactored from works.rg.ru, MoneyToText
 */

namespace Rg\ApiBundle\Service;

class PriceToTextConverter
{
    const RUB  = ['рубль', 'рубля', 'рублей'];
    const KOP = ['копейка', 'копейки', 'копеек'];

    /**
     * @param $int int целая часть числа
     * @param $dec int дробная часть числа
     * @return string
     */
    public function convert(int $int, int $dec)
    {
        $ret = '';

        $Kop = $dec;

        $Rub = $int;

        if($Rub)
        {
            // значение $Rub изменяется внутри функции DescrSot
            // новое значение: $Rub %= 1000000000 для миллиарда
            if($Rub >= 1000000000)
                $ret .= $this->DescrSot($Rub, 1000000000,
                        array('миллиард', 'миллиарда', 'миллиардов')) . ' ';
            if($Rub >= 1000000)
                $ret .= $this->DescrSot($Rub, 1000000,
                        array('миллион', 'миллиона', 'миллионов') ) . ' ';
            if($Rub >= 1000)
                $ret .= $this->DescrSot($Rub, 1000,
                        array('тысяча', 'тысячи', 'тысяч'), true) . ' ';

            $ret .= $this->DescrSot($Rub, 1, self::RUB) . ' ';

            $ret = mb_strtoupper(mb_substr($ret, 0, 1)).mb_substr($ret, 1, mb_strlen($ret));
        }
        if($Kop < 10)
            $ret .= '0';
        $ret .= $Kop . ' ' . self::KOP[ $this->DescrIdx($Kop) ];

        return $ret;
    }

    // IN: $in - число,
    // $raz - разряд числа - 1, 1000, 1000000 и т.д.
    // внутри функции число $in меняется
    // $ar_descr - массив описаний разряда ('миллион', 'миллиона', 'миллионов') и т.д.
    // $fem - признак женского рода разряда числа (true для тысячи)
    function DescrSot(&$in, $raz, $ar_descr, $fem = false)
    {
        $ret = '';

        $conv = intval($in / $raz);
        $in %= $raz;

        $descr = $ar_descr[ $this->DescrIdx($conv%100) ];

        if($conv >= 100)
        {
            $Sot = array('сто', 'двести', 'триста', 'четыреста', 'пятьсот',
                'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
            $ret = $Sot[intval($conv/100) - 1] . ' ';
            $conv %= 100;
        }

        if($conv >= 10)
        {
            $i = intval($conv / 10);
            if($i == 1)
            {
                $DesEd = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать',
                    'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать',
                    'восемнадцать', 'девятнадцать' );
                $ret .= $DesEd[ $conv - 10 ] . ' ';
                $ret .= $descr;
                // возвращаемся здесь
                return $ret;
            }
            $Des = array('двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят',
                'семьдесят', 'восемьдесят', 'девяносто' );
            $ret .= $Des[$i - 2] . ' ';
        }

        $i = $conv % 10;
        if($i > 0)
        {
            if( $fem && ($i==1 || $i==2) )
            {
                // для женского рода (сто одна тысяча)
                $Ed = array('одна', 'две');
                $ret .= $Ed[$i - 1] . ' ';
            }
            else
            {
                $Ed = array('один', 'два', 'три', 'четыре', 'пять',
                    'шесть', 'семь', 'восемь', 'девять' );
                $ret .= $Ed[$i - 1] . ' ';
            }
        }
        $ret .= $descr;

        return $ret;
    }

    // функция возвращает необходимый индекс описаний разряда
    // ('миллион', 'миллиона', 'миллионов') для числа $ins
    // например для 29 вернется 2 (миллионов)
    // $ins максимум два числа
    function DescrIdx($ins)
    {
        if(intval($ins/10) == 1) // числа 10 - 19: 10 миллионов, 17 миллионов
            return 2;
        else
        {
            // для остальных десятков возьмем единицу
            $tmp = $ins%10;
            if($tmp == 1) // 1: 21 миллион, 1 миллион
                return 0;
            else if($tmp >= 2 && $tmp <= 4)
                return 1; // 2-4: 62 миллиона
            else
                return 2; // 5-9 48 миллионов
        }
    }
}