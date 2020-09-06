<?php

namespace App;

/**
 * Class Consignment
 *
 * A Consignment is constructed given the Courier name, consignment number and
 * a description of the consignment.
 *
 * @package App
 */
class Consignment {
    private $courierName = "";
    private $number = "";
    private $description = "";

    /**
     * Consignment constructor.
     *
     * I chose to make the parameters optional so that a Consignment Object
     * can be created at any point in the interface.  This allows for consignments to be
     * added manually but also, the main way a consignment would be generated is using
     * the Courier's algorithm.
     *
     * @param $courierName
     * @param $number
     * @param $description
     */
    public function __construct($courierName = "", $number = "", $description = "") {
        $this->courierName = $courierName;
        $this->number = $number;
        $this->description = $description;
    }

    /**
     * Sets the Courier name
     *
     * @param $courierName
     */
    public function setCourierName($courierName) {
        $this->courierName = $courierName;
    }

    /**
     * Gets the Courier name
     *
     * @return string - the name of the Courier
     */
    public function getCourierName() {
        return $this->courierName;
    }

    /**
     * Sets the consignment number
     *
     * @param $number - the consignment number
     */
    public function setNumber($number) {
        $this->number = $number;
    }

    /**
     * Get the consignment number
     *
     * @return string - the consignment number
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Sets the description of the consignment
     *
     * @param $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Gets the description of the consignment
     *
     * @return string - the description of the consignment
     */
    public function getDescription() {
        return $this->description;
    }
}
