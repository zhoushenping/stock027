<?php

/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/11/2
 * Time: 下午3:43
 */
class eastAutoTrade
{

    const table = 'east_auto_trade';

    //对于指定股票 如果到达指定的低位  自动买入
    static function buyLow()
    {
        foreach (self::readRecord() as $item) {
            if ($item['enable'] == 0) continue;

            $symbol      = $item['symbol'];
            $targetMoney = $item['targetMoney'];//最多成交多少钱的
            $targetPrice = $item['targetPrice'];//目标价格
            $priceNow    = RealTime::getCurrentSellPrice($symbol, time() + 86400);//加100是为了忽略服务器之间的时间不准的差距

            if ($priceNow > 0 && $priceNow <= $targetPrice) {
                $money  = self::releaseMoney($targetMoney);//自动释放购买资金
                $amount = eastTrade::getBuyAmount($money, $priceNow);
                eastTrade::buy($symbol, $priceNow, $amount);
            }
        }
    }

    static function readRecord()
    {
        return DBHandle::select(self::table);
    }

    //取消买入委托以释放资金 使得可用余额尽可能达到$targetMoney
    static function releaseMoney($targetMoney)
    {
        $Kyzj = eastPosition::getKyzj();
        if ($Kyzj >= $targetMoney) {
            return $Kyzj;
        }

        foreach (eastRevoke::getList() as $item) {
            if ($item['Mmsm'] == 'S') continue;//只取消买入委托  todo
            eastRevoke::revoke(["{$item['Wtrq']}_{$item['Wtbh']}"]);
            $Kyzj = eastPosition::getKyzj();
            if ($Kyzj >= $targetMoney) {
                return $Kyzj;
            }
        }

        return $Kyzj;
    }

}
