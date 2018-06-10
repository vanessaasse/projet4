<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;

class CustomerManager
{

    /**
     * @return Customer
     */
    public function initCustomer()
    {
        $customer = new Customer();
        return $customer;
    }


    /**
     * @param Customer $customer
     */
    public function getCurrentCustomer(Customer $customer)
    {
        $customer->getLastname();
        $customer->getFirstname();
        $customer->getEmail();
    }


}