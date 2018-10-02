<?php namespace Gravitel\Test;

use Depakespedro\Grastin\Grastin;
use Depakespedro\Grastin\OrderGrastin;
use Depakespedro\Grastin\ProductGrastin;
use Depakespedro\Grastin\Test\TestLogger;

/**
 * @see \Depakespedro\Grastin\Grastin::newordercourier
 */
class NewOrderCourierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Успешный забмит
     */
    public function testSuccess()
    {
        $grastin = $this->createPartialMock(Grastin::class, ['send']);
        $grastin->expects($this->once())
            ->method('send')
            ->will($this->returnValue($r = '
                <Orders>
                    <Order>
                        <number>000006120</number>
                        <Status>Ok</Status>
                    </Order>
                </Orders>
            '))
            ->with('<File><API></API>'
                . '<Method>newordercourier</Method>'
                . '<Orders><Order '
                    . 'test = "yes" number = "1" address = "address" comment = "comment\'1&quot;&lt;h1&gt;&quot;&amp;" '
                    . 'shippingtimefrom = "13:00" shippingtimefor = "15:00" shippingdate = "01022017" '
                    . 'buyer = "ФИО получателя" summa = "100" assessedsumma = "100" phone1 = "89177755684" '
                    . 'service = "6" takewarehouse = "Город" >'
                    . '<good article="123" name="Product\'1&quot;&lt;h1&gt;&quot;&amp;" cost="100" amount="3" />'
                .'</Order></Orders></File>');

        /** @var Grastin $grastin */
        $grastin->setLogger($logger = new TestLogger);

        $product = new ProductGrastin();
        $product->set_amount('3')
            ->set_article('123')
            ->set_cost('100')
            ->set_name('Product\'1"<h1>"&');

        $order = new OrderGrastin(true);
        $order->set_number('1')
            ->set_address('address')
            ->set_comment('comment\'1"<h1>"&')
            ->set_shippingtimefrom('13:00')
            ->set_shippingtimefor('15:00')
            ->set_shippingdate('01022017')
            ->set_buyer('ФИО получателя')
            ->set_summa('100')
            ->set_assessedsumma('100')
            ->set_phone1('89177755684')
            ->set_service('6')
            ->set_takewarehouse('Город')
            ->set_goods([$product]);

        $result = $grastin->newordercourier([$order]);

        $this->assertSame([
            [
                'number' => '000006120',
                'Status' => 'Ok',
            ],
        ], $result);

        $xml = str_replace('Grastin sendXML :', '', $logger->getLog(0));
        $this->assertNotEmpty(simplexml_load_string($xml));
    }


    /**
     * Заказ с ошибкой
     */
    public function testError()
    {
        $grastin = $this->createPartialMock(Grastin::class, ['send']);
        $grastin->expects($this->once())
            ->method('send')
            ->will($this->returnValue($r = '
                <Orders>
                    <Order>
                        <number>000006120</number>
                        <Error>The service code is not found</Error>
                    </Order>
                </Orders>
            '));

        $order = new OrderGrastin(true);
        /** @var Grastin $grastin */
        $result = $grastin->newordercourier([$order]);

        $this->assertSame([
            [
                'number' => '000006120',
                'Error' => 'The service code is not found',
            ],
        ], $result);
    }
}
