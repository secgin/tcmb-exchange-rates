# Türkiye Cumhuriye Merkez Bankası Döviz Kur API

```
composer require secgin/tcmb-exchange-rates
```

```php
$apiUrl = 'https://evds2.tcmb.gov.tr/service/evds/';
$apiKey = '...';

$tcmb = new Tcmb('https://evds2.tcmb.gov.tr/service/evds/', $apiKey);

$result = $tcmb->getCurrencyRates(['USD', 'EUR', 'GBP']);

// Seçilen Tüm Kurlar
$rates = $result->getRates();

// Seçilen kurlardan sadece bir tanesini almak için
$rate = $result->getRate('USD');
```

> **_NOT:_** Geriye CurencyRate sınıfı dönüyor. Bu sınıf içerisinde alış, satış ve zaman değerileri bulunmatadır.
