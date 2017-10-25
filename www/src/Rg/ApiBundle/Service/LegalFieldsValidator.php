<?php

namespace Rg\ApiBundle\Service;

use Rg\ApiBundle\Exception\OrderException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FieldsValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateLegal(\stdClass $order_details)
    {
        if (empty($order_details->org_name)) throw new OrderException('Пустое наименование организации');
        if (empty($order_details->inn)) throw new OrderException('Пустой или некорректный ИНН');
        if (empty($order_details->kpp)) throw new OrderException('Пустой или некорректный КПП');
        if (empty($order_details->postcode)) throw new OrderException('Пустой или некорректный почтовый индекс');

        if (empty($order_details->contact_phone)) throw new OrderException('empty contact phone');

        if (!$this->isValidEmail($order_details->contact_email))
            throw new OrderException('Email ' . $order_details->contact_email . ' is not valid.');

        if (!$this->validateId($order_details->delivery_city_id))
            throw new OrderException('Email ' . $order_details->contact_email . ' is not valid.');

        if (empty($order_details->delivery_postcode)) throw new OrderException('Пустой или некорректный почтовый индекс');
    }

    public function isValidEmail(string $email)
    {
        $constraints = [
            new Email(),
            new NotBlank(),
        ];

        $error = $this->validator->validate($email, $constraints);

        return !(count($error) > 0);
    }

    public function validateId($id)
    {
        $options = [
            'options' => [
                'min_range' => 1
            ],
        ];

        return filter_var($id, FILTER_VALIDATE_INT, $options);
    }
}