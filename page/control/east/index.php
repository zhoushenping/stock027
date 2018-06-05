<?php
$total = eastMoney::getTotal();
if ($total == 0) {
    Browser::headerRedirect("?a=east&m=login");
}
