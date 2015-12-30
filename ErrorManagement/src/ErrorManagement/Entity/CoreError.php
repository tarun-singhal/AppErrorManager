<?php
/**
 * To Set/Get the error code and message
 */
namespace ErrorManagement\Entity;

/**
 * CoreError Class defines the structure for errors being sent from the API
 */
class CoreError
{

    /**
     * $errorCode int Holds the code for error
     */
    public $errorCode;

    /**
     * $errorCode string module
     */
    public $module;
    
    /**
     * $errorCode string Holds the user friendly error message
     */
    public $userMessage;

    /**
     * $errorCode string Holds the user friendly error message
     */
    public $debugMessage;

    /**
     * $errorCode string Link to the documentation
     */
    public $link;
    
    

    public function get($property)
    {
        return $this->{$property};
    }

    public function set($property, $value)
    {
        $this->{$property} = $value;
    }
}

