<?php

namespace Rg\ApiBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Rg\ApiBundle\Entity\Area;
use Rg\ApiBundle\Entity\Pin;
use Rg\ApiBundle\Entity\Promo;
use Rg\ApiBundle\Entity\Tariff;
use Rg\ApiBundle\Entity\Zone;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PromoFetcher
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Вернёт null, если промокод либо протух, либо ещё не добавлен
     * @param Request $request
     * @param SessionInterface $session
     * @return null|Promo
     */
    public function getPromoOrNull(Request $request, SessionInterface $session)
    {
        $promocode = $session->get('promocode');
        if (is_null($promocode)) return null;

        $promo = null;

        try {
            $promo = $this->fetchPromoFromDB($promocode, $request);
        } catch (\Exception $e) {
            // промокод протух, наверное?
            $session->remove('promocode');
        }

        return $promo;
    }

    public function fetchPromoFromDB(string $promocode, Request $request)
    {
        $raw_promo = explode('/', $promocode);
        $code = $raw_promo[0];

        $doctrine = $this->doctrine;

        /** @var \Rg\ApiBundle\Entity\Promo $promo */
        $promo = $doctrine
            ->getRepository('RgApiBundle:Promo')
            ->findOneBy(['code' => $code]);

        if (is_null($promo)) {
            $error = 'Промокод не найден';
            throw new \Exception($error);
        }

        ## всё, что не так с промокодом -- объясняю подробно
        // 0. Наш промокод с пин-кодами?
        if ($promo->getPins()->count() > 0) {
            // пользователь отправил пинкод?
            if (count($raw_promo) < 2) {
                $error = 'Пин-код промокода не передан.';
                throw new \Exception($error);
            }

            // пин есть у нас в таблице?
            $user_pin = trim($raw_promo[1]);

            if (!$this->isValidPin($user_pin)) {
                $error = 'Пин-код промокода не передан или неправильный.';
                throw new \Exception($error);
            }

            $pins = $promo->getPins()->filter(
                function (Pin $pin) use($user_pin) {
                    return $pin->getValue() == $user_pin;
                }
            );

            if ($pins->count() != 1) {
                $error = 'Пин-код не найден.';
                throw new \Exception($error);
            }

            $pin = $pins->current();

            // пин уже использован?
            if ($pin->getOrder() != null) {
                $error = 'Пин-код уже активирован.';
                throw new \Exception($error);
            }
        }

        // 1. area, zone
        $from_front_id = $this->getFrontId($request);
        if (is_null($from_front_id)) {
            $error = 'Регион не определён. Видимо, его нет в cookie.';
            throw new \Exception($error);
        }

        /** @var null|Area $area */
        $user_area = $this->getArea($from_front_id);
        if (is_null($user_area)) {
            $error = 'В базе не найден регион с id ' . $from_front_id;
            throw new \Exception($error);
        }

        /** @var Area $promo_area */
        $promo_area = $promo->getArea();
        if (!is_null($promo_area)) {
            if ($user_area->getId() != $promo_area->getId()) {
                $error = 'Промокод действителен только для ' . $promo_area->getName()
                    . ', ваш регион определён как ' . $user_area->getName();
                throw new \Exception($error);
            }
        }

        $promo_zones = $promo->getZones();
        if ($promo_zones->count() > 0) {
            /** @var Zone $user_zone */
            $user_zone = $user_area->getZone();

            if (!$promo_zones->contains($user_zone)) {
                $error = 'Промокод недействителен для вашей группы регионов, определённой как '
                    . $user_zone->getName();
                throw new \Exception($error);
            }
        }

        // 2. is active?
        if ($promo->getIsActive() == false) {
            $error = "Промокод не активен.";
            throw new \Exception($error);
        }

        // 3. has started, ended?
        $start = $promo->getStart();
        $end = $promo->getEnd();
        if (!is_null($start) && !is_null($end)) {
            $date = new \DateTime(date('Y-m-d'));
            if ($date < $start) {
                $error = "Период действия промокода ещё не начался.";
                throw new \Exception($error);
            }
            if ($date > $end) {
                $error = "Период действия промокода уже закончился.";
                throw new \Exception($error);
            }
        }

        // 4. amount is not exhausted?
        $amount = $promo->getAmount();
        if (!is_null($amount)) {
            $sold = $promo->getSold();

            if ($amount <= $sold) {
                $error = "Достигнут лимит активаций по этому промокоду.";
                throw new \Exception($error);
            }
        }

        return $promo;
    }

    private function isValidPin($pin)
    {
        if (strlen($pin) < 1) return false;

        return true;
    }

    private function getFrontId($request)
    {
        $from_front_id = $request->cookies->get('cityId') ?? null;

        return $from_front_id;
    }

    private function getArea($from_front_id)
    {
        $area = $this->doctrine->getRepository('RgApiBundle:Area')
            ->findOneBy(['from_front_id' => $from_front_id]);

        return $area;
    }

    public function doesPromoFitTariff(Promo $p, Tariff $t)
    {
        $promo_area = $p->getArea();
        if (!is_null($promo_area)) {
            // промокод регионированный
            if ($promo_area->getZone()->getId() != $t->getZone()->getId()) return false;
        } else {
            // промо зонированный
            if (!$p->getZones()->contains($t->getZone())) return false;
        }

        if ($p->getTimeunit()->getId() != $t->getTimeunit()->getId()) return false;

        if (!$p->getProducts()->contains($t->getProduct())) return false;

        return true;
    }

    public function isValidPromocode(string $promocode)
    {
        if (strlen($promocode) > 255 or empty($promocode)) return false;
        if (preg_match('#[^0-9A-Za-z_%/-]#', $promocode)) return false;
        return true;
    }
}
