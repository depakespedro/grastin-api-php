<?php namespace Depakespedro\Grastin;

use Illuminate\Support\Facades\Log;

class Grastin
{
    const URL_API = 'http://api.grastin.ru/api.php';

    private $key = '';

    private $send_xml = '';

    private $responce_xml = '';

    private $parse_xml = '';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    //оправка запроса на апи урл
    private function sendXML($xml)
    {
        if ($this->logger) {
            $this->logger->info('Grastin sendXML : '.$xml);
        }

        $this->send_xml = $xml;
        $responce = $this->send($xml);

        if ($this->logger) {
            $this->logger->info('Grastin responce : '.$responce);
        }

        $this->responce_xml = $responce;
        return $responce;
    }


    protected function send($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL_API);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'XMLPackage=' . urlencode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $responce = curl_exec($ch);

        if ($error = curl_errno($ch)) {
            throw new GrastinException('Grastin CUrl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $responce;
    }


    //преоьраузет xml в simple xml
    private function parseXML($xml)
    {
        $errLevel = error_reporting(0);
        $parse_xml = simplexml_load_string($xml);
        error_reporting($errLevel);

        if (false === $parse_xml) {
            throw new GrastinException("Grastin: Failed to parse XML: " . $xml);
        }

        $json = json_encode($parse_xml);
        $array = json_decode($json, TRUE);
        $this->parse_xml = $array;
        return $array;
    }

    //отправляет запрос и парсит ответ
    private function sendRequest($xml)
    {
        $responce_xml = $this->sendXML($xml);
        $parse_responce_xml = $this->parseXML($responce_xml);

        if (!empty($parse_responce_xml['Error'])) {
            throw new GrastinException("Grastin Error: ". $parse_responce_xml['Error']);
        }

        return $parse_responce_xml;
    }

    private function parse_parameters_calc_shiping($data, $check, $default)
    {
        if(isset($data[$check]) and !empty($data[$check]))
            return $data[$check];
        else
            return $default;
    }

    //++++++++++++++++++++++++++++++ Методы для работы
    //получение списка пунктов самовывоза
    public function selfpickup()
    {
        $xml = "<File><API>" . $this->key . "</API><Method>selfpickup</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['Selfpickup'];
    }

    //Получение списка складов приёмки
    public function warehouse()
    {
        $xml = "<File> <API>" . $this->key . "</API> <Method>warehouse</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['Warehouse'];
    }

    //Получение списка регионов доставки
    public function DeliveryRegion()
    {
        $xml = "<File> <API>" . $this->key . "</API> <Method>DeliveryRegion</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['Region'];
    }

    //Получение списка пунктов самовывоза Boxberry
    public function boxberryselfpickup()
    {
        $xml = "<File> <API>" . $this->key . "</API> <Method>boxberryselfpickup</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['SelfpickupBoxberry'];
    }

    //Получение списка доступных индексов Boxberry
    public function boxberrypostcode()
    {
        $xml = "<File> <API>" . $this->key . "</API> <Method>boxberrypostcode</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['PostcodeBoxberry'];
    }

    //Получение списка пунктов самовывоза Hermes ------------------- не работет на их стороне
    public function hermesselfpickup()
    {
        $xml = "<File><API>" . $this->key . "</API><Method>hermesselfpickup</Method></File>";
        $data = $this->sendRequest($xml);
        return $data;
    }

    //Добавление заказа в курьерскую службу
    public function newordercourier(array $og)
    {
        $orders_xml = '';
        foreach ($og as $item) {
            $orders_xml .= $item->convertToXML();
        }
        $xml = "<File><API>" . $this->key . "</API><Method>newordercourier</Method><Orders>" . $orders_xml . "</Orders></File>";

        $data = $this->sendRequest($xml);

        $orders = $data['Order'];

        if(isset($orders['number'])){
            $orders = [$orders];
        }

        return $orders;
    }

    //Получение информации по заказу
    public function orderinformationdynumber(array $og)
    {
        //формируем заказы
        $orders_xml = '<Orders>';
        foreach ($og as $item) {
            $orders_xml .= '<Order>' . $item->get_arg('number') . '</Order>';
        }
        $orders_xml .= '</Orders>';

        $xml = "<File><API>$this->key</API><Method>orderinformation</Method>$orders_xml</File>";

        $data = $this->sendRequest($xml);
        return $data['Order'];
    }

    //Получение информации по заказу
    public function orderinformationbydate(\DateTime $start, \DateTime $end)
    {
        $start = $start->format('dmY');
        $end = $end->format('dmY');

        $xml = "<File><API>$this->key</API><Method>orderinformation</Method><datedeliverystart>$start</datedeliverystart><datedeliveryend>$end</datedeliveryend></File>";

        $data = $this->sendRequest($xml);
        return $data['Order'];
    }

    //Получение истории статусов заказов
    public function statushistory(array $og){
        //формируем заказы
        $orders_xml = '<Orders>';
        foreach ($og as $item) {
            $orders_xml .= '<Order>' . $item->get_arg('number') . '</Order>';
        }
        $orders_xml .= '</Orders>';

        $xml = "<File><API>".$this->key."</API> <Method>statushistory</Method>$orders_xml</File>";

        $data = $this->sendRequest($xml);
        return $data['Order'];
    }

    //Получение стоимости доставки, передается массив с параметрами
    /*
     * number - Номер заказа. Он нужен для расшифровки ответа сервиса чтобы сопоставить суммы в ответе.
     * idregion - Уникальный идентификатор региона в который происходит доставка заказа. Список регионов можно получить методом DeliveryRegion.
     * weight - Вес заказа в граммах
     * assessedsumma - Оценочная стоимость заказа
     * summa - Сумма заказа
     * bulky - Признак что заказ крупногабаритный (значение “true” или “false”)
     * volume - Объем заказа в м3. Необходимо заполнять в случае крупногабаритного заказа. Если заказ не крупногабаритный передается “0”
     * width - Ширина заказа (см). Необходимо заполнять в случае крупногабаритного заказа. Если заказ не крупногабаритный передается “0”
     * height - Высота заказа (см). Необходимо заполнять в случае крупногабаритного заказа. Если заказ не крупногабаритный передается “0”
     * length - Длина заказа (см). Необходимо заполнять в случае крупногабаритного заказа. Если заказ не крупногабаритный передается “0”
     * selfpickup - Признак что заказ будет отправляться на пункт самовывоза (значение “true” или “false”)
     * transportcompany - Признак что заказ будет отправляться в транспортную компанию (значение “true” или “false”)
     * paiddistance - Оплачиваемое расстояние в км.
     * idpickup - ID пункта самовывоза сторонней службы доставки. Берётся на основании метода  boxberryselfpickup.
     * idpostcode - ID индекса сторонней службы доставки. Берётся на основании метода boxberrypostcode
     *
     */
    public function CalcShipingCost(array $orders){
        $orders_xml = '<Orders>';
        foreach($orders as $order){
            //пропарсим пришедшие данные
            $order['number'] = $this->parse_parameters_calc_shiping($order,'number','0');
            $order['idregion'] = $this->parse_parameters_calc_shiping($order,'idregion','0');
            $order['weight'] = $this->parse_parameters_calc_shiping($order,'weight','0');
            $order['assessedsumma'] = $this->parse_parameters_calc_shiping($order,'assessedsumma','0');
            $order['summa'] = $this->parse_parameters_calc_shiping($order,'summa','0');
            $order['bulky'] = $this->parse_parameters_calc_shiping($order,'bulky',false);
            $order['volume'] = $this->parse_parameters_calc_shiping($order,'volume','0');
            $order['width'] = $this->parse_parameters_calc_shiping($order,'width','0');
            $order['height'] = $this->parse_parameters_calc_shiping($order,'height','0');
            $order['length'] = $this->parse_parameters_calc_shiping($order,'length','0');
            $order['selfpickup'] = $this->parse_parameters_calc_shiping($order,'length',false);
            $order['transportcompany'] = $this->parse_parameters_calc_shiping($order,'transportcompany',false);
            $order['paiddistance'] = $this->parse_parameters_calc_shiping($order,'paiddistance','0');
            $order['idpickup'] = $this->parse_parameters_calc_shiping($order,'idpickup','');
            $order['idpostcode'] = $this->parse_parameters_calc_shiping($order,'idpostcode','');

            $order_xml = "<Order ";
            foreach ($order as $key=>$value){
                $order_xml.=$key.'="'.$value.'" ';
            }
            $order_xml.="/>";
            $orders_xml.=$order_xml;
        }
        $orders_xml.="</Orders>";

        $xml = "<File><API>".$this->key."</API><Method>CalcShipingCost</Method>$orders_xml</File>";

        $data = $this->sendRequest($xml);
        return $data['Order'];
    }

    //Получение списка отчетов агента
    public function agentreportlist(\DateTime $start, \DateTime $end)
    {
        $start = $start->format('dmY');
        $end = $end->format('dmY');

        $xml = "<File><API>$this->key</API><Method>agentreportlist</Method><Datestart>$start</Datestart><Dateend>$end</Dateend></File>";

        $data = $this->sendRequest($xml);
        return $data['AgentReport'];
    }

    //Получение списка договоров
    public function ContractList()
    {
        $xml = "<File><API>$this->key</API><Method>ContractList</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['Contract'];
    }

    //Получение списка заказов по статусу
    /*
     * draft - черновик, принят через API или личный кабинет и ждет рассмотрения логиста;
     * new - Новый. Рассмотрен логистом, ждет получения заказа на складе;
     * return - Возврат;
     * done - Выполнен;
     * shipping - На доставке. Заказ отгружен курьеру;
     * received - Получен от клиента. Заказ получен на склад от клиента;
     * canceled - Заказ отменен;
     * prepared for shipment - Заказ подготовлен к отгруке курьеру в машину;
     * problem - Проблемный заказ.
     * returned to customer - возвращен клиенту;
     * decommissioned - списан.
     *
     */
    public function orderlist($state)
    {
        $xml = "<File><API>$this->key</API><Method>orderlist</Method><Status>received</Status></File>";
        $data = $this->sendRequest($xml);
        return $data['Number'];
    }

    //Получение списка офисов транспортных компаний
    public function tcofficelist()
    {
        $xml = "<File><API>$this->key</API><Method>tcofficelist</Method></File>";
        $data = $this->sendRequest($xml);
        return $data['office'];
    }


}
