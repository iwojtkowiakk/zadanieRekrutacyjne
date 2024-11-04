<?php

namespace App\Twig;

use App\Repository\WarehouseRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_warehouses', [$this, 'getWarehouses']),
        ];
    }

    public function getWarehouses($user)
    {
        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->warehouseRepository->findAll();
        } elseif ($user) {
            return $user->getWarehouses();
        }

        return [];
    }
}