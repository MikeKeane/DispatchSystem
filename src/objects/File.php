<?php
/**
 * Created by PhpStorm.
 * User: sales
 * Date: 06/09/2020
 * Time: 19:37
 */

namespace App;

/**
 * Class File
 *
 * I wanted to keep the process of uploading files separated.  So this class
 * holds method for creating the necessary files and also methods for
 * uploading them depending on the Courier's preference (FTP, email, etc).
 *
 * @package App
 */
class File {

    public function __construct() {

    }

    /**
     * Creates a file with a simple list of consignment numbers
     *
     * @param $filePath string - path to save the file
     * @param $consignments array Consignment- Consignment
     */
    public function createFile($filePath, $consignments) {
        foreach($consignments as $con) {
            file_put_contents($filePath, $con->getNumber() . "\n", FILE_APPEND);
        }
    }

    /**
     * Uploads the file using FTP
     *
     * Uploads a file to the Courier's FTP server, using the config specified.
     *
     * @param $filePath
     * @param $config
     * @return int
     */
    public function uploadFTP($filePath, $config) {
        $ftpConn = ftp_connect($config["server"]);

        if(ftp_login($ftpConn, $config["username"], $config["password"]) == FALSE) {
            return 1; //error with upload
        }

        if(ftp_put($ftpConn, pathinfo($filePath)["basename"], $filePath, FTP_ASCII) == FALSE) {
            return 1; //error with upload
        }

        return 0; //upload successful
    }

    /**
     * Uploads a file using email.
     *
     * Send a file to the Courier using email.
     * I am not familiar with sending files by email using PHP.
     *
     * @param $filePath
     * @param $config
     * @return int
     */
    public function uploadEmail($filePath, $config) {
        //i'm not hot on sending email with PHP
        //TODO: attach file @ $filePath
        if(mail($config["email_address"], "Gear4Music Consignment Record", "Please find attached Gear4Music consignment record.") == FALSE) {
            return 1; //error with upload
        } else {
            return 0; //upload successful
        }
    }
}
