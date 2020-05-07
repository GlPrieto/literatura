<?php
//Al generar una nueva clase de servicio es
// necesario definir un servico para dicha clase
// config/services.php
use App\Service\SubidaArchivos;

$container->autowire(SubidaArchivos::class)
    ->setArgument('$targetDirectory', '%brochures_directory%');