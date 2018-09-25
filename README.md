# grastin-api-php
Неофициальный PHP-клиент для API службы доставки Грастин

Установка
------------

Добавить в composer.json
```
  "repositories": {
    "depakespedro/grastin-api-php": {
      "type": "vcs",
      "url": "https://github.com/depakespedro/grastin-api-php.git"
    }
  }
```

Установить пакет:
```
    composer require depakespedro/grastin-api-php
```


Использование
-----

### Добавить заказ
```
    $product = new ProductGrastin();
    $product->set_amount('100')
        ->set_article('123')
        ->set_cost('100')
        ->set_name('213');
    
    $product2 = new ProductGrastin();
    $product2->set_amount('322')
        ->set_article('434')
        ->set_cost('100')
        ->set_name('432');
    
    $og = new OrderGrastin($isTest = true);
    $og->set_number('Ваш уникальный номер заказа')
        ->set_address('Адрес получателя')
        ->set_comment('Комментарий курьеру')
        ->set_shippingtimefrom('13:00')
        ->set_shippingtimefor('15:00')
        ->set_shippingdate('01022017') // DDMMYYYY
        ->set_buyer('ФИО получателя')
        ->set_summa('100') // Сумма заказ
        ->set_assessedsumma('100') // Оценочная стоимость
        ->set_phone1('89177755684')
        ->set_service('6') // Код услуги, см. `\Depakespedro\Grastin\Enum\ServiceType`
        ->set_takewarehouse('Москва') // Город отгрузки
        ->set_goods([$product,$product2]);
    
    $grastin = new Grastin('API key');
    $data_responce = $grastin->newordercourier([$og]);
```
