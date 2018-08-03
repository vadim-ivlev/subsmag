<?php

namespace Rg\ApiBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;

/**
 * Class OFDReceipt
 * @package Rg\ApiBundle\Service
 */
class OFDReceipt
{
    private $doctrine;
    private $item_name;

    public function __construct(
        ItemName $item_name,
        Registry $doctrine
    )
    {
        $this->doctrine = $doctrine;
        $this->item_name = $item_name;
    }

    public function prepareOFDReceiptByOrder(Order $order, \SimpleXMLElement $ofd_receipt_xml)
    {
        $doctrine = $this->doctrine;

        $vendor = $doctrine->getRepository('RgApiBundle:Vendor')
            ->findOneBy(['keyword' => 'zaorg']); // magic word. What if there'd be more than one vendor?

        // подготовить товарную составляющую
        $items = $order->getItems();
        $patritems = $order->getPatritems();
        $goods = [];

        /** @var Item $item */
        foreach ($items as $item) {
            $item_name = mb_substr($this->item_name->form($item), 0, 124);
            ## для каталожной цены
            $discountedCatCost = $item->getDiscountedCatCost();
            if ($discountedCatCost > 0) {
                $goods[] = [
                    'name' => $item_name . ' кат',
                    'price' => $discountedCatCost,
                    'quantity' => $item->getQuantity(),
                    'vat' => 10,
                ];
            }

            ## для доставочной
            $discountedDelCost = $item->getDiscountedDelCost();
            if ($discountedDelCost > 0) {
                $goods[] = [
                    'name' => $item_name . ' дост',
                    'price' => $discountedDelCost,
                    'quantity' => $item->getQuantity(),
                    'vat' => 18,
                ];
            };
        }
        /** @var Patritem $patritem */
        foreach ($patritems as $patritem) {
            $patriff = $patritem->getPatriff();
            $name = "Родина №" . $patriff->getIssue()->getMonth() . "-" . $patriff->getIssue()->getYear();
            ## для каталожной цены
            $pi_discountedCatCost = $patritem->getDiscountedCatCost();
            if ($pi_discountedCatCost > 0) {
                $goods[] = [
                    'name' => $name . ' кат',
                    'price' => $pi_discountedCatCost,
                    'quantity' => $patritem->getQuantity(),
                    'vat' => 10,
                ];
            }

            ## для доставочной
            $pi_discountedDelCost = $patritem->getDiscountedDelCost();
            if ($pi_discountedDelCost > 0) {
                $goods[] = [
                    'name' => $name . ' дост',
                    'price' => $pi_discountedDelCost,
                    'quantity' => $patritem->getQuantity(),
                    'vat' => 18,
                ];
            }
        }

        $goods = array_map(
            function (array $g) {
                $g['cost'] = $g['price'] * $g['quantity'];
                return $g;
            },
            $goods
        );

        // подготовить чековую составляющую

        $pg = [
            'status' => (string) $ofd_receipt_xml->pg_status,
            'receipt_status' => (string) $ofd_receipt_xml->pg_receipt_status,
            'fiscal_receipt_number' => (string) $ofd_receipt_xml->pg_fiscal_receipt_number,
            'shift_number' => (string) $ofd_receipt_xml->pg_shift_number,
            'receipt_date' => (string) $ofd_receipt_xml->pg_receipt_date,
            'fn_number' => (string) $ofd_receipt_xml->pg_fn_number,
            'ecr_registration_number' => (string) $ofd_receipt_xml->pg_ecr_registration_number,
            'fiscal_document_number' => (string) $ofd_receipt_xml->pg_fiscal_document_number,
            'fiscal_document_attribute' => (string) $ofd_receipt_xml->pg_fiscal_document_attribute,
        ];

        return [
            'vendor' => $vendor,
            'order' => $order,
            'total' => number_format($order->getTotal(), 2, ',', ''),
            'goods' => $goods,
            'pg' => $pg,
        ];
    }

}
