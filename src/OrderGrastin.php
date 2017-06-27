<?php

namespace Depakespedro\Grastin;

class OrderGrastin
{
    private $args = [];
    private $products = [];

    public function __construct($test = false)
    {
        if($test){
            $this->args['test'] = 'yes';    
        }        
    }
    
    public function print_arg(){
        print_r($this->args);
        return $this;
    }
    
    public function get_args(){
        return $this->args;
    }

    public function get_arg($arg){
        return $this->args[$arg];
    }

    public function convertToXML(){
        $products_xml = '';

        foreach($this->products as $product_grastin){
            $products_xml .= $product_grastin->convertToXML();
        }

        $order_xml = '<Order ';
        foreach ($this->args as $key => $value) {
            $order_xml .= "$key = " . '"' . $value . '"' . ' ';
        }

        $order_xml.='>';

        $order_xml .= $products_xml;

        $order_xml.='</Order>';

        return $order_xml;
    }

    //Номер заказа в Вашей системе
    public function set_number($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['number'] = $arg;
        }
        return $this;
    }

    //Адрес доставки
    public function set_address($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['address'] = $arg;
        }
        return $this;
    }

    //Комментарий по доставке (не обязательно)
    public function set_comment($arg)
    {
        if (!is_null($arg)) {
            $this->args['comment'] = $arg;
        }
        return $this;
    }

    //Начало желаемого времени доставки. Задается в формате XX:XX(Не обязательно. Если не задано - 10:00)
    public function set_shippingtimefrom($arg)
    {
        if (!is_null($arg)) {
            $this->args['shippingtimefrom'] = $arg;
        }
        return $this;
    }

    //Окончание желаемого времени доставки. Задается в формате XX:XX(Не обязательно. Если не задано - 18:00)
    public function set_shippingtimefor($arg)
    {
        if (!is_null($arg)) {
            $this->args['shippingtimefor'] = $arg;
        }
        return $this;
    }

    //Дата доставки. Задается в формате ddmmMMMM
    public function set_shippingdate($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['shippingdate'] = $arg;
        }
        return $this;
    }

    //ФИО покупателя
    public function set_buyer($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['buyer'] = $arg;
        }
        return $this;
    }

    //Сумма заказа
    public function set_summa($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['summa'] = $arg;
        }
        return $this;
    }

    //Оценочная стоимость заказа
    public function set_assessedsumma($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['assessedsumma'] = $arg;
        }
        return $this;
    }

    //Номер телефона1 покупателя
    public function set_phone1($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['phone1'] = $arg;
        }
        return $this;
    }

    //Номер телефона2 покупателя (не обязательно)
    public function set_phone2($arg)
    {
        if (!is_null($arg)) {
            $this->args['phone2'] = $arg;
        }
        return $this;
    }

    //Код услуги доставки.
    //1 - Доставка без оплаты
    //2 - Доставка с оплатой
    //3 - Доставка с кассовым обслуживанием
    //4 - Обмен/забор товара
    //5 - Самовывоз без оплаты
    //6 - Самовывоз с оплатой
    //7 - Самовывоз с кассовым обслуживанием
    //8 - Большой доставка без оплаты
    //9 - Большой доставка и забор наличных
    //10 - Большой доставка с кассовым обслуживанием
    //11 - Обмен/забор товара на самовывозе
    //12 - Транспортная компания
    //13-Почтовая доставка
    //14-Посылка онлайн
    //15-Курьер онлайн
    //16-Самовывоз с оплатой картой
    //17-Забор товара у поставщика (закупки)
    //18-Забор БОЛЬШОЙ товара у поставщика  (закупки)
    //19-Доставка с оплатой картой
    public function set_service($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['service'] = $arg;
        }
        return $this;
    }

    //Тестовый режим использования API сервиса. Включается установкой значения параметра “yes”.При работе в тестовом режиме заказы на доставку не создаются.
    public function set_test($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['test'] = $arg;
        }
        return $this;
    }

    //Количество мест
    public function set_seats($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['seats'] = $arg;
        }
        return $this;
    }

    //Склад приёма заказа
    public function set_takewarehouse($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['takewarehouse'] = $arg;
        }
        return $this;
    }

    //Вид груза
    public function set_cargotype($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['cargotype'] = $arg;
        }
        return $this;
    }

    //Штрихкод (Правило формирования штрихкода для ваших заказов уточняйте у логиста)
    public function set_barcode($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['barcode'] = $arg;
        }
        return $this;
    }

    //Наименование сайта для вывода в маршрутный лист курьера. (необязательное для заполнения)
    public function set_sitename($arg)
    {
        if (!is_null($arg)) {
            $this->args['sitename'] = $arg;
        }
        return $this;
    }

    //Адрес электронной почты
    public function set_email($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['email'] = $arg;
        }
        return $this;
    }

    //УИД офиса транспортной компании (заполняется в случае если используется услуга 12 - Транспортная компания)
    public function set_tc_office($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_office'] = $arg;
        }
        return $this;
    }

    //1 - физ лицо
    //2 - юр лицо
    //(заполняется в случае если используется услуга 12 - Транспортная компания)
    public function set_tc_typerecipient($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_typerecipient'] = $arg;
        }
        return $this;
    }

    //Почтовый индекс получателя (заполняется в случае если используется услуга 12 - Транспортная компания)
    public function set_tc_postcode($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_postcode'] = $arg;
        }
        return $this;
    }

    //Адрес получателя (заполняется в случае если используется услуга 12 - Транспортная компания)
    public function set_tc_address($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_address'] = $arg;
        }
        return $this;
    }

    //ФИО получателя (заполняется в случае если используется услуга 12 - Транспортная компания и вид получателя физлицо )
    public function set_tc_fullname($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_fullname'] = $arg;
        }
        return $this;
    }

    //Телефон получателя (заполняется в случае если используется услуга 12 - Транспортная компания)
    public function set_tc_phone($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_phone'] = $arg;
        }
        return $this;
    }

    //Паспортные данные получателя (заполняется в случае если используется услуга 12 - Транспортная компания и вид получателя физлицо )
    public function set_tc_passport($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_passport'] = $arg;
        }
        return $this;
    }

    //Организация (заполняется в случае если используется услуга 12 - Транспортная компания и вид получателя юрлицо)
    public function set_tc_organization($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_organization'] = $arg;
        }
        return $this;
    }

    //ИНН организации (заполняется в случае если используется услуга 12 - Транспортная компания и вид получателя юрлицо)
    public function set_tc_INN($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_INN'] = $arg;
        }
        return $this;
    }

    //КПП организации (заполняется в случае если используется услуга 12 - Транспортная компания и вид получателя юрлицо)
    public function set_tc_KPP($arg)
    {
        if (!empty($arg) or !is_null($arg)) {
            $this->args['tc_KPP'] = $arg;
        }
        return $this;
    }
    
    public function set_goods(array $products_grastin){
        if (!is_null($products_grastin) or !empty($products_grastin)) {
            $this->products = $products_grastin;
        }
    }
}