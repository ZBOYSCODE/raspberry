<?php

namespace App\Models;

class UserDetails extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $rut;

    /**
     *
     * @var string
     */
    public $location;

    /**
     *
     * @var string
     */
    public $phone_fixed;

    /**
     *
     * @var string
     */
    public $phone_mobile;

    /**
     *
     * @var string
     */
    public $comments;        

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('user_id', __NAMESPACE__.'\Users', 'id', array('alias' => 'Users'));
        $this->belongsTo('medical_plan_id', __NAMESPACE__.'\MedicalPlan', 'id', array('alias' => 'MedicalPlan'));
        $this->belongsTo('cities_id', __NAMESPACE__.'\Cities', 'id', array('alias' => 'Cities'));

    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_details';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserDetails[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
