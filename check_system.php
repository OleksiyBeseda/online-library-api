<?php
require __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Controller\BookController;

echo "=== SYSTEM CHECK ===\n";

// 1️⃣ Проверка подключения к БД
try {
    $conn = DriverManager::getConnection([
        'driver'   => 'pdo_pgsql',
        'user'     => getenv('DB_USER') ?: 'library_user',
        'password' => getenv('DB_PASSWORD') ?: 'library_pass',
        'host'     => getenv('DB_HOST') ?: 'db',
        'port'     => getenv('DB_PORT') ?: 5432,
        'dbname'   => getenv('DB_NAME') ?: 'library',
    ]);

    // Попробуем запросить список таблиц
    $tables = $conn->createSchemaManager()->listTableNames();
    echo "[OK] Database connection successful.\n";

    if (in_array('book', $tables)) {
        echo "[OK] Table 'book' exists.\n";
    } else {
        echo "[WARN] Table 'book' does not exist.\n";
    }

} catch (\Exception $e) {
    echo "[FAIL] Database connection failed: " . $e->getMessage() . "\n";
}

// 2️⃣ Проверка контроллера через контейнер Symfony
try {
    $container = new ContainerBuilder();
    $controller = new BookController();
    if ($controller) {
        echo "[OK] BookController is instantiable.\n";
    }
} catch (\Exception $e) {
    echo "[FAIL] BookController cannot be instantiated: " . $e->getMessage() . "\n";
}

// 3️⃣ Проверка роутов через bin/console debug:router
echo "[INFO] To verify routes, run:\n";
echo "docker compose exec app php bin/console debug:router\n";
