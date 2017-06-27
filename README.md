# grastin-api-php

--- Create order

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

$number = '1';
$shippingtimefrom = '13:00';
$shippingtimefor = '15:00';
$shippingdate = '20062017';
$phone1 = '89177755684';
$summa = '100';
$assessedsumma = '100';
$service = '6';
$buyer = 'rteter';
$address = 'hgfhgf';
$comment = 'gdfhgfh';
$city = 'jhkjh';

  $og = new OrderGrastin(true);
  $og->set_number($number)
      ->set_address($address)
      ->set_comment($comment)
      ->set_shippingtimefrom($shippingtimefrom)
      ->set_shippingtimefor($shippingtimefor)
      ->set_shippingdate($shippingdate)
      ->set_buyer($buyer)
      ->set_summa($summa)
      ->set_assessedsumma($assessedsumma)
      ->set_phone1($phone1)
      ->set_service($service)
      ->set_takewarehouse($city)
      ->set_goods([$product,$product2]);


  $grastin = new Grastin('123-123-123-123');
  $data_responce = $grastin->newordercourier([$og]);
