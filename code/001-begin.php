<?php
/**
 * This is the beginning point of the refractoring journey. From here we have the
 * task of creating an HTML representation of the statement.  However, to get there
 * we have a bit of cleanup to do.
 *
 */

class Customer
{
    public $name;
    public $rentals;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addRental($rental)
    {
        $this->rentals[] = $rental;
    }

    public function getName()
    {
        return $this->name;
    }

    public function statement()
    {
        $result = "Rental Record for " . $this->getName() . "\n";

        foreach ($this->rentals as $rental) {
            $result .= "\t" . $rental->movie->getTitle() . "\t" . $rental->getCharge() . "\n";
        }

        // add footer lines
        $result .= "Amount owed is " . $this->getTotalCharge() . "\n";
        $result .= "You earned " . $this->getTotalFrequentRenterPoints() . " frequent renter points";

        return $result;
    }

    private function getTotalCharge()
    {
        $result = 0;
        foreach ($this->rentals as $rental) {
            $result += $rental->getCharge();
        }
        return $result;
    }

    private function getTotalFrequentRenterPoints()
    {
        $result = 0;
        foreach ($this->rentals as $rental) {
            $result+= $rental->getFrequentRenterPoints();
        }
        return $result;
    }
}

class Movie
{
    const CHILDRENS = 2;
    const REGULAR = 0;
    const NEW_RELEASE = 1;

    public $title;
    public $price;

    public function __construct($title, $priceCode)
    {
        $this->title = $title;
        $this->setPrice($priceCode);
    }

    public function getPriceCode()
    {
        return $this->price->getPriceCode();
    }

    public function setPrice($priceCode)
    {
        switch ($priceCode) {
            case self::REGULAR:
                $this->price = new RegularPrice();
                break;
            case self::CHILDRENS:
                $this->price = new ChildrensPrice();
                break;
            case self::NEW_RELEASE:
                $this->price = new NewReleasePrice();
                break;

            default:
                throw new Exception('Incorrect Price Code.');
                break;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCharge($daysRented)
    {
        return $this->price->getCharge($daysRented);
    }

    public function getFrequentRenterPoints($daysRented)
    {
        return $this->price->getFrequentRenterPoints($daysRented);
    }
}

class Rental
{
    public $movie;
    public $daysRented;

    public function __construct(Movie $movie, $daysRented)
    {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
    }

    public function getDaysRented()
    {
        return $this->daysRented;
    }

    public function getMovie()
    {
        return $this->movie;
    }

    public function getCharge()
    {
        return $this->movie->getCharge($this->getDaysRented());
    }

    public function getFrequentRenterPoints()
    {
        return $this->movie->getFrequentRenterPoints($this->getDaysRented());
    }
}

abstract class Price
{
    abstract protected function getPriceCode();

    abstract protected function getCharge($daysRented);

    public function getFrequentRenterPoints($daysRented)
    {
        return 1;
    }
}

class ChildrensPrice extends Price
{
    public function getPriceCode()
    {
        return Movie::CHILDRENS;
    }

    public function getCharge($daysRented)
    {
        $result = 1.5;
        if ($daysRented > 3) {
            $result += ($daysRented - 3) * 1.5;
        }
        return $result;
    }
}
class NewReleasePrice extends Price
{
    public function getPriceCode()
    {
        return Movie::NEW_RELEASE;
    }

    public function getCharge($daysRented)
    {
        return $daysRented * 3;
    }

    public function getFrequentRenterPoints($daysRented)
    {
        return ($daysRented > 1) ? 2 : 1;
    }
}
class RegularPrice extends Price
{
    public function getPriceCode()
    {
        return Movie::REGULAR;
    }

    public function getCharge($daysRented)
    {
        $result = 2;
        if ($daysRented > 2) {
            $result += ($daysRented - 2) * 1.5;
        }
        return $result;
    }
}

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

// print the statement
echo $customer->statement();
