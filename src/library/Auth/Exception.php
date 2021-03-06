<?php
namespace App\library\Auth;

class Exception extends \Exception
{

    protected $field;

    public function __construct($message, $field = null, $code = 0, Exception $previous = null) {

        $this->field = $field;

        parent::__construct($message, $code, $previous);

    }

    public function getField(){
        return $this->field;
    }


}
