<?php namespace Depakespedro\Grastin\Enum;

class ServiceType
{
    const DELIVERY_NO_PAYMENT = 1;                  // Доставка без оплаты
    const DELIVERY_WITH_PAYMENT = 2;                // Доставка с оплатой
    const DELIVERY_WITH_OFFICIAL_PAYMENT = 3;       // Доставка с кассовым обслуживанием
    const LOAD_CARGO = 4;                           // Забор товара
    const PICKUP_NO_PAYMENT = 5;                    // Самовывоз без оплаты
    const PICKUP_WITH_PAYMENT = 6;                  // Самовывоз с оплатой
    const PICKUP_WITH_OFFICIAL_PAYMENT = 7;         // Самовывоз с кассовым обслуживанием
    const BIG_DELIVERY_NO_PAYMENT = 8;              // Большой доставка без оплаты
    const BIG_DELIVERY_WITH_CASH = 9;               // Большой доставка и забор наличных
    const BIG_DELIVERY_WITH_OFFICIAL_PAYMENT = 10;  // Большой доставка с кассовым обслуживанием
    const PICKUP_CARGO = 11;                        // Обмен/забор товара на самовывозе
    const TC_CARCO = 12;                            // Транспортная компания
    const POST = 13;                                // Почтовая доставка
    const POST_ONLINE = 14;                         // Посылка онлайн
    const POST_COURIER_ONLINE = 15;                 // Курьер онлайн
    const PICKUP_WITH_CARD_PAYMENT = 16;            // Самовывоз с оплатой картой
    const LOAD_FROM_VENDOR = 17;                    // Забор товара у поставщика (закупки)
    const BIG_LOAD_FROM_VENDOR = 18;                // Забор БОЛЬШОЙ товара у поставщика (закупки)
    const DELIVERY_WITH_CARD_PAYMENT = 19;          // Доставка с оплатой картой
    const PRODUCT_EXCHANGE = 924;                   // Обмен товара тоже видимо по комментариям
}
