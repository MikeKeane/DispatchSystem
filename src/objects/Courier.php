<?php

namespace App;

/**
 * Class Courier
 *
 * @package App
 */
class Courier {
    private $name;

    /**
     * Stores upload configurations for all couriers.
     *
     * Set as private so it is not possible to retrieve the incorrect configurations
     * from an instance of Courier.
     *
     * @var array
     */
    private $uploadConfigurations = array(
        "DPD" => array("method" => "email", "email_address" => "19mdk86@dpd.co.uk"),
        "Royal Mail" => array("method" => "FTP", "server" => "ftp.royalmail.co.uk", "username" => "anonymous", "password" => "mike.keane@gear4music.com")
    );
    /**
     * Courier constructor
     *
     * @param $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return string - the name of the Courier
     */
    public function getName() {
        return $this->name;
    }

    public function setUploadConfiguration($uploadConfig) {
        $this->uploadConfigurations[$this->name] = $uploadConfig;
    }

    public function getUploadConfiguration() {
        return $this->uploadConfigurations[$this->name];
    }

    /**
     * Generates a consignment number using the Courier's algorithm
     *
     * Algorithms are all stored here but calling this function ensures the incorrect
     * algorithm is not return from an instance of Courier.
     *
     * @param $description - description of the consignment
     * @return Consignment
     */
    public function generateConsignment($description = "") {
        $consignment = new Consignment();
        $consignment->setCourierName($this->name);
        $consignment->setDescription($description);

        //create the consignment number
        $consNumber = "";

        //uniqid is only being used as a placeholder
        switch($this->name) {
            case "DPD":
                //DPD's consignment algorithm goes here

                $consNumber = "DPD" . uniqid();
                break;
            case "ANC":
                //ANC's consignment algorithm goes here

                $consNumber = "ANC" . uniqid();
                break;
            case "Royal Mail":
                //Royal Mail's consignment algorithm goes here

                $consNumber = "RM" . uniqid();
                break;
            default:
                //throw error - courier not known

        }

        //set the number on the consignment
        $consignment->setNumber($consNumber);

        return $consignment;
    }

    /**
     * Uploads the Consignments to the Courier
     *
     * Takes an array of Consignments and uploads them to the Courier.
     *
     * @param $consignments
     * @return int
     */
    public function uploadConsignments($consignments) {
        $config = $this->uploadConfigurations[$this->name];

        //path to save the file
        $fileName = __DIR__ . "/../../data-store/consignments/" . $this->name . date("Y-m-d") . ".txt";

        //create file
        $file = new File();
        $file->createFile($fileName, $consignments);

        //upload file
        $uploadResult = null;
        if($config["method"] == "FTP") {
            $uploadResult = $file->uploadFTP($fileName, $config);
        } else if($config["method"] == "email") {
            $uploadResult = $file->uploadEmail($fileName, $config);
        } else {
            //method not supported yet
            $uploadResult = 1;
        }

        return $uploadResult;
    }
}
