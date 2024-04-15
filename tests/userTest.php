<?php

use PHPUnit\Framework\TestCase;
use Login\classes\user;


class UserTest extends TestCase {
    private $user;

    protected function setUp(): void {
        $dbMock 
        = $this->createMock(PDO::class);
        $this->user = new User($dbMock);
    }

    public function testRegisterUser() {
        // Arrange
        $username = "testuser";
        $password = "testpassword";

        // Act
        $result = $this->user->registerUser($username, $password);

        // Assert
        $this->assertFalse($result);
    }

    public function testLoginUser() {
        // Arrange
        $username = "testuser";
        $password = "testpassword";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Mocking the PDO Statement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')
            ->willReturn(['password' => $hashedPassword, 'role' => 'gebruiker']);

        $dbMock = $this->createMock(PDO::class);
        $dbMock->method('prepare')->willReturn($stmtMock);

        $user = new User($dbMock);

        // Act
        $result = $user->loginUser($username, $password);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($_SESSION['username'], $username);
        $this->assertEquals($_SESSION['role'], 'gebruiker');
    }
    
    public function testIsLoggedIn() {
        // Arrange
        $_SESSION['username'] = "testuser";

        // Act
        $result = $this->user->isLoggedin();

        // Assert
        $this->assertTrue($result);
    }
    
}
?>