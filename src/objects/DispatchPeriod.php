<?php

namespace App;

/**
 * Class DispatchPeriod
 *
 * I have used a Singleton Pattern here.  Under the assumption
 * that only one Dispatch Period can be live each day, creation of a
 * Dispatch Period is limited to one instance alone, until the active
 * Dispatch Period is stopped.
 *
 * As the program is not continually running, and so the state of
 * the Dispatch Period is not saved, I save it to a file to be
 * retrieved when making further calls to the system; adding
 * consignments or indeed when it comes to stop the Dispatch
 * Period at the end of a day.
 *
 */
class DispatchPeriod {
    private static $instance = null;
    private $startTime = null;

    /**
     * @var array - holds the consignment numbers
     */
    private $consignments = array();

    /**
     * DispatchPeriod constructor.
     *
     * Constructor is set to private to stop instantiating more than one
     * Dispatch Period
     */
    private function __construct() {

    }

    /**
     * Saves the Dispatch Period's state.
     *
     * Serializes and saves the Dispatch Period.
     */
    private function save() {
        file_put_contents(__DIR__ . "/../../data-store/dispatchPeriod.txt", serialize(self::$instance));
    }

    /**
     * Gets or creates the instance of DispatchPeriod
     *
     * Checks whether an instance has been created, whether it be previously
     * saved or just instantiated.  Returns the active Dispatch Period
     *
     * @return DispatchPeriod
     */
    public static function get() {
        if(self::$instance == null) {
            if(file_get_contents(__DIR__ . "/../../data-store/dispatchPeriod.txt") == "") {
                self::$instance = new DispatchPeriod();
            } else {
                self::$instance = unserialize(file_get_contents(__DIR__ . "/../../data-store/dispatchPeriod.txt"));
            }
        }

        return self::$instance;
    }

    /**
     * Starts the Dispatch Period
     *
     * @return int
     */
    public function start() {
        //checks if start time exists - if so this means the Dispatch Period has already
        //been started
        if($this->startTime == null) {
            $this->startTime = date("Y-m-d H:i:s");
            $this->save();
            return 0; //successful start
        } else {
            return 1; //dispatch period already started
        }
    }

    /**
     * Gets the start time of the running instance
     *
     * @return null
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * Stops the active Dispatch Period
     *
     * @return int
     */
    public function stop() {
        //check if there is an active Dispatch Period to stop
        if(file_get_contents(__DIR__ . "/../../data-store/dispatchPeriod.txt") == "") {
            return 1; //No current dispatch period to stop
        }

        //remove contents from save file
        file_put_contents(__DIR__ . "/../../data-store/dispatchPeriod.txt", "");

        //reset singleton instance to allow for creation of new
        self::$instance = null;

        return 0; //successfully stopped
    }

    /**
     * Adds a new Consignment to the Dispatch Period
     *
     * @param $cons Consignment
     */
    public function addConsignment($cons) {
        //add Consignment
        //consignments are stored, split up by courier
        if(isset($this->consignments[$cons->getCourierName()])) {
            $this->consignments[$cons->getCourierName()][$cons->getNumber()] = $cons;
        } else {
            $this->consignments[$cons->getCourierName()] = array($cons->getNumber() => $cons);
        }

        //save Dispatch Period
        $this->save();
    }

    /**
     * Get the consignments
     *
     * Get all consignments or specify the Courier name to retrieve
     * only those specific consignments.
     *
     * @param null $courierName
     * @return array
     */
    public function getConsignments($courierName = null) {
        if($courierName == null) {
            return $this->consignments;
        } else {
            return $this->consignments[$courierName];
        }
    }

    /**
     * Uploads the Dispatch period's Consignments
     */
    public function uploadConsignments() {
        foreach($this->consignments as $courierName => $consignments) {
            $courier = new Courier($courierName);

            $courier->uploadConsignments($consignments);
        }



    }
}
