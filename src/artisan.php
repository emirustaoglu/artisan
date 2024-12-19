<?php

namespace emirustaoglu;


use emirustaoglu\databases\SeedsManager;
use emirustaoglu\databases\MigrationManager;

class artisan
{
    private $seedPath;
    private $migrationPath;
    private $seedTable;
    private $migrationTable;
    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPassword;
    private $viewPath;
    private $middlewarePath;
    private $modelPath;
    private $enumPath;
    private $controllerPath;
    private $controllerNameSpace;
    private $middlewareNameSpace;
    private $modelNameSpace;
    private $enumNameSpace;

    public function __construct(array $setting)
    {
        $this->seedPath = $setting['seed_path'];
        $this->migrationPath = $setting['migration_path'];
        $this->seedTable = $setting['seed_table'];
        $this->migrationTable = $setting['migration_table'];
        $this->dbHost = $setting['db_host'];
        $this->dbName = $setting['db_name'];
        $this->dbUser = $setting['db_user'];
        $this->dbPassword = $setting['db_password'];
        $this->viewPath = $setting['view_path'];
        $this->middlewarePath = $setting['middleware_path'];
        $this->modelPath = $setting['model_path'];
        $this->enumPath = $setting['enum_path'];
        $this->controllerPath = $setting['controller_path'];
        $this->controllerNameSpace = $setting['contoller_name_space'];
        $this->middlewareNameSpace = $setting['middleware_name_space'];
        $this->modelNameSpace = $setting['model_name_space'];
        $this->enumNameSpace = $setting['enum_name_space'];
    }

    public function getCommand($argv)
    {
        // CLI argümanlarını al
        $command = explode(":", $argv[1]); // İlk argüman komut adı olacak
        if (isset($argv[2])) {
            $option = $argv[2] == null ? null : $argv[2]; // İkinci argüman ise seçenek olacak
        }

        switch ($command[0]) {
            case "-list":
                echo " -list => Tanımlı komutları döner.\n -version => PHP Sürüm bilginizi döner.\n migrate => Veritabanı eşitlemesini yapar.\n seeds => Veritabanı sabit verilerinizi (seeds) çalıştırır.\n serve => Uygulamanızı başlatır. \n make:view => Yeni bir view dosyası oluşturur. => make:view viewAdi \n make:migration => Yeni Bir migrate dosyası oluşturur. => make:view viewAdi \n make:seed => Yeni bir seed dosyası oluşturur. make:seed seedAdi \n make:controller => Yeni bir controller dosyası oluşturur. => make:controller controllerAdi =\n make:middlewares => Yeni bir middlewares dosyası oluşturur. => make:middlewares middlewaresAdi \n make:model => Yeni bir model dosyası oluşturur. => make:model modelAdi \n make:enum => Yeni bir enum dosyası oluşturur. => make:enum enumAdi";
                break;
            case "-version":
                echo $this->phpSurum();
                break;
            case "migrate":
                //Veritabanı eşitlemesi yapar.
                $manager = new MigrationManager($this->migrationPath, [
                    'tableName' => $this->migrationTable,
                    'host' => $this->dbHost,
                    'dbname' => $this->dbName,
                    'username' => $this->dbUser,
                    'password' => $this->dbPassword
                ]);
//                if ($option === "down") {
//                    $manager->migrateDown();
//                } else {
                    $manager->migrateUp();
                //}
                break;
            case "seeds":
                //Veritabanı sabit verileri çalıştırır.
                $manager = new SeedsManager($this->seedPath, [
                    'tableName' => $this->seedTable,
                    'host' => $this->dbHost,
                    'dbname' => $this->dbName,
                    'username' => $this->dbUser,
                    'password' => $this->dbPassword
                ]);
                if ($option == "down") {
                    $manager->seedsDown();
                } else {
                    $manager->seedsUp();
                }
                break;
            case "make":
                switch ($command[1]) {
                    case "view":
                        //Yeni view dosyası oluşturur.
                        $this->makeView($option);
                        break;
                    case "migration":
                        //Yeni migration dosyası oluşturur.
                        $this->makeMigrations($option);
                        break;
                    case "seed":
                        //Yeni seed dosyası oluşturur.
                        $this->makeSeeds($option);
                        break;
                    case "controller":
                        //Yeni controller dosyası oluşturur.,
                        $this->makeController($option);
                        break;
                    case "middlewares":
                        //Yeni middlewares dosyası oluşturur.
                        $this->makeMiddleware($option);
                        break;
                    case "model":
                        //Yeni model dosyası oluşturur.
                        $this->makeModel($option);
                        break;
                    case "enum":
                        //Yeni enum dosyası oluşturur.
                        $this->makeEnum($option);
                        break;
                }
                break;
            default:
                echo "Tanımsız komut. Geçerli komut listesi için 'php artisan -list' komutunu kullanınız.";
                break;
        }
    }

    private function phpSurum($num = 0)
    {
        $output = shell_exec('php -v');

        // Çıktının ilk satırını alalım
        $lines = explode("\n", trim($output));
        $versionLine = $lines[0];

        // Sadece PHP versiyon numarasını alalım
        if (preg_match('/^PHP (\S+)/', $versionLine, $matches)) {
            if ($num == 1)
                return str_replace(".", "", $matches[1]);
            return "PHP Sürümünüz: " . $matches[1];
        } else {
            return "PHP Sürümü Öğrenilemedi";
        }
    }

    private function makeView($option)
    {
        $fileOption = explode("/", $option);

        $fileOption = array_filter($fileOption, fn($v) => !empty($v));

        $folderName = "";
        $fileName = "";

        if (count($fileOption) > 1) {
            $folderName = implode("/", array_slice($fileOption, 0, -1));
            $fileName = "/" . end($fileOption);
        } else {
            $fileName = $fileOption[0];
        }

        if (empty($fileName)) {
            die("Bir View adı belirtmelisiniz. Örnek: php artisan make:view viewName\n");
        }

        //$newViewPath = $this->viewPath . $folderName;

        if (!file_exists($this->viewPath)) {
            mkdir($this->viewPath, 0777, true);
        }

        $newViewFile = $this->viewPath . $fileName . ".blade.php";//echo $newViewFile;exit;
        $viewData = "{{-- Dosya Adı: %DosyaAdi% --}}
{{-- Eklenme Tarihi: %EklenmeTarihi% --}}";
        if (file_put_contents($newViewFile, str_replace('%DosyaAdi%', $fileName, str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $viewData))) !== false) {
            echo "Yeni view dosyası oluşturuldu: $newViewFile\n";
        } else {
            echo "Yeni view dosyası oluşturulamadı!\n";
        }
    }

    private function makeMigrations($migrationName)
    {
        if (!$migrationName) {
            die("Bir migration adı belirtmelisiniz. Örnek: php artisan make:migrations migrationName\n");
        }

        // Template dosyasının yolu
        $templatePath = __DIR__ . '/temp/' . 'Migratoins.php';

        // Yeni migration dosyasının oluşturulacağı yol
        $newMigrationPath = $this->migrationPath . date("Y-m-d-H-i-s") . "-" . $migrationName . '.php';

        if (!file_exists($templatePath)) {
            die("Template dosyası bulunamadı!\n");
        }

        // Template dosyasını oku
        $templateContent = file_get_contents($templatePath);

        $templateContent = str_replace('%DosyaAdi%', $migrationName, str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $templateContent));

        if (!file_exists($this->migrationPath)) {
            mkdir($this->migrationPath, 0777, true);
        }

        // Yeni migration dosyasını oluştur ve içeriğini yaz
        if (file_put_contents($newMigrationPath, $templateContent) !== false) {
            echo "Yeni migration dosyası oluşturuldu: $newMigrationPath\n";
        } else {
            echo "Yeni migration dosyası oluşturulamadı!\n";
        }
        exit;
    }

    private function makeSeeds($seedsName)
    {
        if (!$seedsName) {
            die("Bir seeds adı belirtmelisiniz. Örnek: php artisan make:seed seedsName\n");
        }

        // Template dosyasının yolu
        $templatePath = __DIR__ . '/temp/' . 'Seeds.php';

        // Yeni seeds dosyasının oluşturulacağı yol
        $newSeedsPath = $this->seedPath . date("Y-m-d-H-i-s") . "-" . $seedsName . '.php';

        if (!file_exists($templatePath)) {
            die("Template dosyası bulunamadı!\n");
        }

        // Template dosyasını oku
        $templateContent = file_get_contents($templatePath);

        $templateContent = str_replace('%DosyaAdi%', $seedsName, str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $templateContent));

        if (!file_exists($this->seedPath)) {
            mkdir($this->seedPath, 0777, true);
        }

        // Yeni migration dosyasını oluştur ve içeriğini yaz
        if (file_put_contents($newSeedsPath, $templateContent) !== false) {
            echo "Yeni seeds dosyası oluşturuldu: $newSeedsPath\n";
        } else {
            echo "Yeni seeds dosyası oluşturulamadı!\n";
        }
        exit;
    }

    private function makeController($controllerName)
    {
        if (!$controllerName) {
            die("Bir controller adı belirtmelisiniz. Örnek: php artisan make:controller controllerName\n");
        }

        // Template dosyasının yolu
        $templatePath = __DIR__ . '/temp/' . 'Controller.php';

        // ControllerName'deki son bölümü sınıf adı olarak al
        $controllerNameParts = explode('/', $controllerName);
        $className = end($controllerNameParts);  // Gelen son parçayı alır, örn: AuthController

        // Yeni controller dosyasının oluşturulacağı yol
        $newControllerPath = $this->controllerPath . $controllerName . '.php';

        // Dosyanın oluşturulacağı klasör yapısını kontrol et, klasör yoksa oluştur
        $folderPath = dirname($newControllerPath);
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true); // Gereken klasör yapısını oluştur
        }

        if (!file_exists($templatePath)) {
            die("Template dosyası bulunamadı!\n");
        }

        // Namespace'i oluştur (App\Controllers\Kullanici gibi)
        $namespace = $this->controllerNameSpace;
        if (count($controllerNameParts) > 1) {
            // className dışındaki parçaları namespace'e ekle
            $namespace .= '\\' . implode('\\', array_slice($controllerNameParts, 0, -1));
        }

        // Template dosyasını oku
        $templateContent = file_get_contents($templatePath);

        // Template içeriğini değiştirme
        $templateContent = "<?php \n" . str_replace(
                '%NameSpace%',
                "/*
 * Dosya Adı => %DosyaAdi%
 * Eklenme Tarihi => %EklenmeTarihi%
 *
 */

namespace " . $namespace . ";", // Dinamik namespace ekle
                str_replace(
                    '%UseArea%',
                    "use Core\Bootstrap; use Core\Controller;",
                    str_replace('%ClassArea%', "class " . $className . " { }", $templateContent)
                )
            );

        $templateContent = str_replace(
            '%DosyaAdi%', $className,
            str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $templateContent)
        );

        // Yeni controller dosyasını oluştur ve içeriğini yaz
        if (file_put_contents($newControllerPath, $templateContent) !== false) {
            echo "Yeni controller dosyası oluşturuldu: $newControllerPath\n";
        } else {
            echo "Yeni controller dosyası oluşturulamadı!\n";
        }
        exit;
    }

    private function makeMiddleware($middlewareName)
    {


        if (!$middlewareName) {
            die("Bir middleware adı belirtmelisiniz. Örnek: php artisan make:middleware middlewareName\n");
        }

        // Template dosyasının yolu
        $templatePath = __DIR__ . '/temp/' . 'Middlewares.php';

        // MiddlewaresName'deki son bölümü sınıf adı olarak al
        $middlewareNameParts = explode('/', $middlewareName);
        $className = end($middlewareNameParts);  // Gelen son parçayı alır, örn: AuthMiddleware

        // Yeni middleware dosyasının oluşturulacağı yol
        $newMiddlewaresPath = $this->middlewarePath . $middlewareName . '.php';

        // Dosyanın oluşturulacağı klasör yapısını kontrol et, klasör yoksa oluştur
        $folderPath = dirname($newMiddlewaresPath);
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true); // Gereken klasör yapısını oluştur
        }

        if (!file_exists($templatePath)) {
            die("Template dosyası bulunamadı!\n");
        }

        // Namespace'i oluştur (App\Controllers\Kullanici gibi)
        $namespace = $this->middlewareNameSpace;
        if (count($middlewareNameParts) > 1) {
            // className dışındaki parçaları namespace'e ekle
            $namespace .= '\\' . implode('\\', array_slice($middlewareNameParts, 0, -1));
        }

        // Template dosyasını oku
        $templateContent = file_get_contents($templatePath);

        // Template içeriğini değiştirme
        $templateContent = "<?php \n" . str_replace(
                '%NameSpace%',
                "/*
 * Dosya Adı => %DosyaAdi%
 * Eklenme Tarihi => %EklenmeTarihi%
 *
 */

namespace " . $namespace . ";", // Dinamik namespace ekle
                str_replace(
                    '%UseArea%',
                    "use Core\Bootstrap;",
                    str_replace('%ClassArea%', "class " . $className . " { }", $templateContent)
                )
            );

        $templateContent = str_replace(
            '%DosyaAdi%', $className,
            str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $templateContent)
        );

        // Yeni controller dosyasını oluştur ve içeriğini yaz
        if (file_put_contents($newMiddlewaresPath, $templateContent) !== false) {
            echo "Yeni middlewares dosyası oluşturuldu: $newMiddlewaresPath\n";
        } else {
            echo "Yeni middlewares dosyası oluşturulamadı!\n";
        }
        exit;
    }

    private function makeModel($modelName)
    {

        if (!$modelName) {
            die("Bir model adı belirtmelisiniz. Örnek: php artisan make:model modelName\n");
        }

        // Template dosyasının yolu
        $templatePath = __DIR__ . '/temp/' . 'Model.php';

        // MiddlewaresName'deki son bölümü sınıf adı olarak al
        $modelNameParts = explode('/', $modelName);
        $className = end($modelNameParts);  // Gelen son parçayı alır, örn: AuthMiddleware

        // Yeni middleware dosyasının oluşturulacağı yol
        $newModelPath = $this->modelPath . $modelName . '.php';

        // Dosyanın oluşturulacağı klasör yapısını kontrol et, klasör yoksa oluştur
        $folderPath = dirname($newModelPath);
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true); // Gereken klasör yapısını oluştur
        }

        if (!file_exists($templatePath)) {
            die("Template dosyası bulunamadı!\n");
        }

        // Namespace'i oluştur (App\Controllers\Kullanici gibi)
        $namespace = $this->modelNameSpace;
        if (count($modelNameParts) > 1) {
            // className dışındaki parçaları namespace'e ekle
            $namespace .= '\\' . implode('\\', array_slice($modelNameParts, 0, -1));
        }

        // Template dosyasını oku
        $templateContent = file_get_contents($templatePath);

        // Template içeriğini değiştirme
        $templateContent = "<?php \n" . str_replace(
                '%NameSpace%',
                "/*
 * Dosya Adı => %DosyaAdi%
 * Eklenme Tarihi => %EklenmeTarihi%
 */

namespace " . $namespace . ";", // Dinamik namespace ekle
                str_replace('%ClassArea%', "class " . $className . " { }", $templateContent)
            );

        $templateContent = str_replace(
            '%DosyaAdi%', $className,
            str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $templateContent)
        );

        // Yeni controller dosyasını oluştur ve içeriğini yaz
        if (file_put_contents($newModelPath, $templateContent) !== false) {
            echo "Yeni model dosyası oluşturuldu: $newModelPath\n";
        } else {
            echo "Yeni model dosyası oluşturulamadı!\n";
        }
        exit;
    }

    private function makeEnum($enumName)
    {
        $phpVersion = $this->phpSurum(1);
        if ($phpVersion < 810) {
            die('Enumerations desteği PHP 8.1.0 ve üzeri sürümlerde kullanılabilir. Lütfen ENUM desteği için PHP Sürümünüzü yükseltin.');
        }
        global $argv;
        if (!$enumName) {
            die("Bir enum adı belirtmelisiniz. Örnek: php artisan make:enum enumName\n");
        }

        // Template dosyasının yolu
        $templatePath = __DIR__ . '/temp/' . 'Enum.php';

        // MiddlewaresName'deki son bölümü sınıf adı olarak al
        $enumNameParts = explode('/', $enumName);
        $className = end($enumNameParts);  // Gelen son parçayı alır, örn: AuthMiddleware
        if (isset($argv[3])) {
            $className .= " :" . $argv[3];
        }

        // Yeni middleware dosyasının oluşturulacağı yol
        $newEnumPath = $this->enumPath . $enumName . '.php';

        // Dosyanın oluşturulacağı klasör yapısını kontrol et, klasör yoksa oluştur
        $folderPath = dirname($newEnumPath);
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true); // Gereken klasör yapısını oluştur
        }

        if (!file_exists($templatePath)) {
            die("Template dosyası bulunamadı!\n");
        }

        // Namespace'i oluştur (App\Controllers\Kullanici gibi)
        $namespace = $this->enumNameSpace;
        if (count($enumNameParts) > 1) {
            // className dışındaki parçaları namespace'e ekle
            $namespace .= '\\' . implode('\\', array_slice($enumNameParts, 0, -1));
        }

        // Template dosyasını oku
        $templateContent = file_get_contents($templatePath);

        // Template içeriğini değiştirme
        $templateContent = "<?php \n" . str_replace(
                '%NameSpace%',
                "/*
 * Dosya Adı => %DosyaAdi%
 * Eklenme Tarihi => %EklenmeTarihi%
 */

namespace " . $namespace . ";", // Dinamik namespace ekle
                str_replace('%EnumArea%', "enum " . $className . " { }", $templateContent)
            );

        $templateContent = str_replace(
            '%DosyaAdi%', $className,
            str_replace('%EklenmeTarihi%', date('Y-m-d H:i:s'), $templateContent)
        );

        // Yeni controller dosyasını oluştur ve içeriğini yaz
        if (file_put_contents($newEnumPath, $templateContent) !== false) {
            echo "Yeni enum dosyası oluşturuldu: $newEnumPath\n";
        } else {
            echo "Yeni enum dosyası oluşturulamadı!\n";
        }
        exit;
    }

}