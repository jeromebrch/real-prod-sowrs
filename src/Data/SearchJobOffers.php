<?php


namespace App\Data;


use App\Entity\Cause;
use App\Entity\ContractType;
use App\Entity\Country;
use App\Entity\LevelExperience;
use App\Entity\Remuneration;

class SearchJobOffers
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Country
     */
    public $country;

    /**
     * @var ContractType
     */
    public $contractType;

    /**
     * @var Cause
     */
    public $cause;

    /**
     * @var Remuneration
     */
    public $remuneration;

    /**
     * @var LevelExperience
     */
    public $levelStudy;

    /**
     * @var LevelExperience
     */
    public $levelExperience;

    /**
     * @var boolean
     */
    public $freshness;
}