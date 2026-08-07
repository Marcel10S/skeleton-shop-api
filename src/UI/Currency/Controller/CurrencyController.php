<?php

declare(strict_types=1);

namespace App\UI\Currency\Controller;

use App\Infrastructure\Currency\DTO\CurrencyFormDTO;
use App\Infrastructure\Currency\DTO\CurrencySettingsDTO;
use App\Infrastructure\Currency\Handler\CurrencyCreate;
use App\Infrastructure\Currency\Handler\CurrencyConfigure;
use App\Infrastructure\Currency\Provider\CurrencyProvider;
use App\UI\Currency\Form\CurrencySettingsType;
use App\UI\Currency\Form\CurrencyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('currencies/', name: 'shop_currencies_')]
class CurrencyController extends AbstractController
{
    #[Route('new', name: 'create')]
    public function create(Request $request, CurrencyCreate $handler): Response
    {
        $form = $this->createForm(CurrencyType::class, new CurrencyFormDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->createByDTO($form->getData());

            return $this->redirectToRoute('shop_currencies_list');
        }

        return $this->render('@ui/Currency/View/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('', name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request, CurrencyProvider $provider, CurrencyConfigure $handler): Response
    {
        $currencies = $provider->findAll();
        $form = $this->createForm(CurrencySettingsType::class, CurrencySettingsDTO::fromCurrencies($currencies), [
            'currency_choices' => array_column(
                array_map(
                    static fn ($currency) => [
                        'label' => sprintf('%s (%s)', $currency->getName(), $currency->getCode()),
                        'code' => $currency->getCode(),
                    ],
                    $currencies,
                ),
                'code',
                'label',
            ),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->updateByDTO($form->getData());

            return $this->redirectToRoute('shop_currencies_list');
        }

        return $this->render('@ui/Currency/View/list.html.twig', ['form' => $form->createView()]);
    }
}
