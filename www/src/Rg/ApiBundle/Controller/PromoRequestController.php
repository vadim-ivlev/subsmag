<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\PromoRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

/**
 * Class PromoRequestController
 * @package Rg\ApiBundle\Controller
 */
class PromoRequestController extends Controller
{
    const maxWidth = 4096;
    const maxHeight = 4096;
    const maxSize = 2097152;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $email_constraint = new Assert\Email(
            [
                'message' => 'Неправильный email',
            ]
        );

        $doc_constraint = new Assert\Image([
            'maxSize' => self::maxSize,
            'maxWidth' => self::maxWidth,
            'maxHeight' => self::maxHeight,
            'mimeTypes' => [
                'image/png',
                'image/jpeg',
            ],
            'maxSizeMessage' => 'Пожалуйста, загрузите документ объёмом до ' . $this->formatSizeUnits(self::maxSize),
            'mimeTypesMessage' => 'Пожалуйста, загрузите документ в формате PNG или JPEG.',
            'maxHeightMessage' => 'Высота до ' . self::maxHeight . ' пикселей',
            'maxWidthMessage' => 'Ширина до ' . self::maxWidth . ' пикселей',
        ]);

        $constraint = new Assert\Collection(
            [
                'email' => [
                    new Assert\NotBlank(),
                    $email_constraint
                ],
                'document' => [
                    new Assert\NotBlank(['message' => 'Или файла не было, или он слишком большой.']),
                    $doc_constraint
                ],
            ]
        );

        $validator = Validation::createValidator();
        $email = $request->request->get('email');
        /** @var File $doc */
        $doc = $request->files->get('document');

        $violations = $validator->validate(
            [
                'email' => $email,
                'document' => $doc,
            ],
            $constraint
        );

        if ($violations->count() > 0) {
            $err_list = [];
            /** @var ConstraintViolation $v */
            foreach ($violations as $v) {
                $err_list[] = [
                    'field' => $v->getPropertyPath(),
                    'error' => $v->getMessage(),
                ];
            }

            return (new Out())->json(['error' => $err_list]);
        }

        $content = $doc->openFile('r')->fread($doc->getSize());

        $promo_request = new PromoRequest();
        $promo_request->setEmail($email);
        $promo_request->setImage(gzencode($content, 9));
        $promo_request->setMime($doc->getMimeType());
        $promo_request->setIsReplied(false);
        $promo_request->setCreated(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($promo_request);
        $em->flush();

        $answer = [
            'request_id' => $promo_request->getId(),
//            'doc' => [
//                'ext' => $doc->guessExtension(),
//                'size' => $doc->getSize(),
//                'mimi' => $promo_request->getMime(),
//                'content' => base64_encode(gzdecode($promo_request->getImage())),
//            ],
//            'email' => $email,
        ];

        return (new Out())->json($answer);
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
