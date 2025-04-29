<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase {
    
    private $pdo;

    protected function setUp(): void {
        // Conectar a la base de datos de prueba
        $this->pdo = new PDO('mysql:host=localhost;dbname=pruebas_hospital', 'usuario', 'contraseña');
    }

    public function testUserLogin(): void {
        // Preparar datos de usuario de prueba
        $username = 'testuser';
        $password = 'password123';

        // Consultar si el usuario existe
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, md5($password)]);
        $user = $stmt->fetch();

        // Afirmar que el usuario existe en la base de datos
        $this->assertNotEmpty($user, "El usuario debería poder iniciar sesión con credenciales válidas.");
    }

    protected function tearDown(): void {
        // Cerrar conexión de base de datos
        $this->pdo = null;
    }
}
