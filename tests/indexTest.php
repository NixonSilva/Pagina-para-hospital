<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testPageLoadsSuccessfully()
    {
        // Captura la salida de index.php
        ob_start();
        include __DIR__ . '/../index.php';
        $output = ob_get_clean();

        // Comprueba que la página carga la imagen de portada
        $this->assertStringContainsString('<img src="../public/img/portada.jpg"', $output);
        
        // Verifica que se muestra el título de bienvenida
        $this->assertStringContainsString('<h1>Bienvenido a Clínica Securitas</h1>', $output);
        
        // Verifica que se muestra el título de los servicios
        $this->assertStringContainsString('<h3>servicios especializados</h3>', $output);
    }

    public function testServiceLinks()
    {
        // Captura la salida de index.php
        ob_start();
        include __DIR__ . '/../index.php';
        $output = ob_get_clean();

        // Comprueba algunos de los enlaces de los servicios
        $this->assertStringContainsString("onclick=\"location.href='../src/login.php'\"", $output);
        $this->assertStringContainsString("onclick=\"location.href='../src/atention.php'\"", $output);
        $this->assertStringContainsString("onclick=\"location.href='../src/emergencies.php'\"", $output);
    }
}
