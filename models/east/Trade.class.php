<?php

/**
 * eastTrade::buy('sh600372', 0.01, 1000);
 * eastTrade::sell('sh600549', 99.99, 1000);
 */
class eastTrade
{

    static function sell($symbol, $price, $amount)
    {
        $params = self::makeParams($symbol, $price, -1 * (abs($amount)));
        self::doTrade($params);
    }

    static function buy($symbol, $price, $amount)
    {
        $params = self::makeParams($symbol, $price, abs($amount));
        self::doTrade($params);
    }

    private function doTrade($params)
    {
        $rs = CURL::mfetch($params, 'post', eastLogin::getHeaders());
        foreach ($rs as $item) {
            //json_decode($item['content']);
        }
    }

    private static function makeParams($symbol, $price, $amount)
    {
        $params = [];
        $url    = "https://jy.xzsec.com/Trade/SubmitTrade?validatekey=" . eastLogin::getToken();

        $params[] = array(
            "url"    => $url,
            "params" => array(
                'stockCode' => String::filterNoNumber($symbol),
                'price'     => $price,
                'amount'    => abs($amount),
                'tradeType' => $amount > 0 ? 'B' : 'S',//B=买 S=卖
                'zqmc'      => '证券名称',//证券名称
            ),
        );

        return $params;
    }

    //$money能够以$price买多少股
    static function getBuyAmount($money, $price)
    {
        return 100 * floor($money / $price / 100);
    }

    //需要以$price卖多少股才能得到$money
    static function getSellAmount($money, $price)
    {
        return 100 * ceil($money / $price / 100);
    }

}
