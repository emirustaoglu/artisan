# Artisan Komut Satırı Aracı 

<details>
<summary>Başlıklar</summary>

<!-- TOC -->
* [Artisan Komut Satırı Aracı](#artisan-komut-satırı-aracı-)
  * [📜 Genel Bilgiler](#-genel-bilgiler)
    * [Özellikler](#özellikler-)
  * [🚀 Kurulum](#-kurulum)
  * [Gereksinimler](#gereksinimler)
  * [🛠️ İlk Yapılandırma](#-ilk-yapılandırma)
  * [📋 Komut Listesi](#-komut-listesi)
  * [Örnek Kullanım](#örnek-kullanım-)
  * [🔧 Katkıda Bulunma](#-katkıda-bulunma)
  * [📜 Lisans](#-lisans)
  * [🎉 Teşekkürler!](#-teşekkürler)
<!-- TOC -->
</details>

## 📜 Genel Bilgiler

**Proje Adı:** `artisan`

`artisan`, Laravel’in komut satırı benzeri araçlarını, Laravel kullanmayan ancak özel bir boilerplate ile çalışan
geliştiriciler için sunmayı hedefler.  
Bu araç sayesinde, Laravel’in sunduğu gibi, migration, seed, controller oluşturma ve projeyi başlatma gibi işlemleri
kolayca yapabilirsiniz.

**Kimler İçin?**

- Laravel komut satırını kullanmayı seven ancak Laravel kullanmayan geliştiriciler.
- Hızlı ve kolay bir şekilde dosya ve proje yapılandırmalarını gerçekleştirmek isteyenler.

### Özellikler 

- Controller, model, view gibi yapıların hızlı oluşturulması.
- Migration ve seed yönetimi.
- Projenin hızlı bir şekilde ayağa kaldırılması.
- Minimal ve özelleştirilebilir yapı.

---

## 🚀 Kurulum

Projeyi Composer ile kurabilirsiniz:

```bash
composer require emirustaoglu/artisan
```

Composer kurulumu tamamlandıktan sonra terminal üzerinden şu kodu çalıştırın
```bash
php -r "require_once 'vendor/autoload.php'; emirustaoglu\install\FirstInstall::createArtisan();" 
```

Böylelikle projenizin ana dizinine `artisan` dosyası oluşturulacaktır.

## Gereksinimler

- PHP Sürümü:
    - Minimum: PHP 7.4
    - Tavsiye Edilen: PHP 8.1 veya üzeri (Enum desteği için)

---

Kurulum sonrasında projenizin ana dizinine otomatik olarak bir `artisan` dosyası oluşturulur. Bu dosyanın yapılandırması
şu şekildedir:

```php
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

```
📌 Not: Yukarıdaki ayarların proje gereksinimlerinize uygun şekilde yapılandırılması gereklidir.

## 🛠️ İlk Yapılandırma
- `artisan` dosyasını oluşturduktan sonra içerisine proje dizin yollarını ve veritabanı ayarlarını düzenlemelisiniz.
- Örnek:
- `db_host`, `db_name`, `db_user`, `db_password` alanlarını veritabanı bilgilerinize göre değiştirin.
- Dosya yollarını projenize uygun olarak özelleştirin.

---

## 📋 Komut Listesi

| Komut | Açıklama  | Kullanım |
|-------------|--------------------|----------------|
| -list | Tüm tanımlı komutları listeler. | php artisan -list |
| -version | PHP sürüm bilgisi döner. | php artisan -version |
| migrate | Veritabanı eşitlemesi yapar. | php artisan migrate |
| seeds | Veritabanı seed (sabit veri) işlemlerini çalıştırır. | php artisan seeds |
| serve | Projeyi ayağa kaldırır. | php artisan serve |
| make:view | Yeni bir view (görünüm) dosyası oluşturur. | php artisan make:view viewName |
| make:migration | Yeni bir migration dosyası oluşturur. | php artisan make:migration migrationName |
| make:seed | Yeni bir seed dosyası oluşturur. | php artisan make:seed seedName |
| make:controller | Yeni bir controller dosyası oluşturur. | php artisan make:controller controllerName |
| make:middlewares | Yeni bir middleware dosyası oluşturur. | php artisan make:middlewares middlewareName |
| make:model | Yeni bir model dosyası oluşturur. | php artisan make:model modelName |
| make:enum | Yeni bir enum dosyası oluşturur (PHP >= 8.1). | php artisan make:enum enumName |

## Örnek Kullanım 

1. Migration ve Seed Çalıştırma:
```bash
php artisan migrate
php artisan seeds
```

2. Yeni Controller Oluşturma:
```bash
php artisan make:controller UserController
```

3. Projeyi Ayağa Kaldırma:
```bash
php artisan serve
```

---

## 🔧 Katkıda Bulunma
Bu proje açık kaynaklıdır. İsteyen herkes katkıda bulunabilir ve kullanabilir.

## 📜 Lisans
Bu proje MIT Lisansı altında lisanslanmıştır. Dilediğiniz gibi kullanabilir ve dağıtabilirsiniz.

## 🎉 Teşekkürler!
