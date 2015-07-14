<?php
/**
 * 
 * Date: 24.11.14
 * Time: 16:45
 */

class Log_1C {

    private $log_file;

    public function __construct($log_file = '') {
        if(file_exists($log_file)) {
            $this->log_file = $log_file;
        }
    }

    public function add($message, $error = false) {
        if($error) {
            $message = "\n\t" . $message . "\n";
        } else {
            $message = "\n" . $message . "\n";
        }
        if($this->log_file) {
            file_put_contents($this->log_file, $message, FILE_APPEND);
        }
        if($error) {
            file_put_contents('php://stderr', $message, FILE_APPEND);
        } else {
            print $message;
        }

        return true;
    }
} 