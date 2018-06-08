<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;

class CustomerManager
{
    public function initCustomer()
    {
        $customer = new Customer();
        return $customer;
    }
}