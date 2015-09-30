<?php
class beginTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function test_statement()
    {
        // define customer
        $customer = new Customer('Adam Culp');

        // choose movie to be rented, define rental, add it to the customer
        $movie = new Movie('Gladiator', 0);
        $rental = new Rental($movie, 1);
        $customer->addRental($rental);

        // choose 2nd movie to be rented, define rental, add it to the customer
        $movie = new Movie('Spiderman', 1);
        $rental = new Rental($movie, 2);
        $customer->addRental($rental);


        $actual = $customer->statement();

        $expect = "Rental Record for Adam Culp\n".
                  "\tGladiator\t2\n".
                  "\tSpiderman\t6\n".
                  "Amount owed is 8\n".
                  "You earned 3 frequent renter points";

        $this->assertEquals($expect, $actual);
    }

}
