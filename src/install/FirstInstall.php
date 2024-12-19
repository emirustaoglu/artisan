<?php

namespace emirustaoglu\install;

class FirstInstall
{
    public static function createArtisan()
    {
        // Ana proje dizinine gider
        $projectRoot = dirname(__DIR__, 5); // Vendor klasöründen çık
        $proxyPath = $projectRoot . '/artisan/artisan'; // Ana dizindeki artisan proxy dosyası

        // Eğer artisan proxy dosyası yoksa oluştur
        if (!file_exists($proxyPath)) {
            $proxyContent = <<<'PHP'
<?php
require_once __DIR__ . '/vendor/autoload.php';

use emirustaoglu\artisan;

$artisan = new artisan([
    'migration_path' => __DIR__ . '/databases/migrations/',
    'seed_path' => __DIR__ . '/databases/seeds/',
    'view_path' => __DIR__ . '/resources/view/',
    'controller_path' => __DIR__ . '/app/Controllers/',
    'middleware_path' => __DIR__ . '/app/Middlewares/',
    'model_path' => __DIR__ . '/app/Model/',
    'enum_path' => __DIR__.'/app/Enum/',
    'contoller_name_space' => 'App\Controllers',
    'middleware_name_space' => 'App\Middlewares',
    'model_name_space' => 'App\Model',
    'enum_name_space' => 'App\Enum',
    'db_host' => 'localhost',
    'db_name' => '',
    'db_user' => '',
    'db_password' => '',
    'migration_table' => 't_migrations',
    'seed_table' => 't_seeds'
]);

return $artisan->getCommand($argv);
PHP;
            file_put_contents($proxyPath, $proxyContent); // Proxy dosyasını yaz
            chmod($proxyPath, 0755); // Çalıştırılabilir hale getir
            echo "emirustaoglu\artisan komut sistemi başarıyla oluşturulmuştur. Lütfen projenizin ana dizinine eklenen artisan dosyası içerisindeki ilk yapılandırmalarınızı yapınız.";
        } else {
            echo "Farklı bir artisan komut sistemi tespit edildiğinden kurulum tamamlanamadı. Bu komut sistemi hatalı çalışabilir.";
        }
    }
}
