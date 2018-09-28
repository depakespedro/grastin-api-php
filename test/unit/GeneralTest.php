<?php namespace Gravitel\Test;

use Depakespedro\Grastin\Grastin;
use Depakespedro\Grastin\GrastinException;
use Depakespedro\Grastin\Test\TestLogger;

/**
 * @see \Depakespedro\Grastin\Grastin
 */
class GeneralTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Мок ответа
     */
    public function testMock()
    {
        $grastin = $this->createPartialMock(Grastin::class, ['send']);
        $grastin->expects($this->once())
            ->method('send')
            ->will($this->returnValue($r = '<ContractList><Contract><Name>Название</Name><Id>123</Id></Contract></ContractList>'));

        /** @var Grastin $grastin */
        $grastin->setLogger($logger = new TestLogger);

        $result = $grastin->ContractList();

        $this->assertEquals('Grastin sendXML : <File><API></API><Method>ContractList</Method></File>', $logger->getLog(0));
        $this->assertEquals('Grastin responce : '.$r, $logger->getLog(1));
    }


    /**
     * 500 ошибка, Грастин упал и отдает не XML
     */
    public function test500error()
    {
        $grastin = $this->createPartialMock(Grastin::class, ['send']);
        $grastin->expects($this->once())
            ->method('send')
            ->will($this->returnValue($r = '500 error: not XML response'));

        $this->expectException(GrastinException::class);
        $this->expectExceptionMessage('Failed to parse XML');
        $grastin->ContractList();
    }


    /**
     * Ошибка авторизации
     */
    public function testAuthError()
    {
        $grastin = $this->createPartialMock(Grastin::class, ['send']);
        $grastin->expects($this->once())
            ->method('send')
            ->will($this->returnValue($r = '<Errors><Error>Client not found</Error></Errors>'));

        $this->expectException(GrastinException::class);
        $this->expectExceptionMessage('Client not found');
        $grastin->ContractList();
    }
}
