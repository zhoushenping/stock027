<?php
$fundsFlow = eastFundsFlow::getList();
if (count($fundsFlow) == 0) {
    Browser::headerRedirect("?a=east&m=login");
}
