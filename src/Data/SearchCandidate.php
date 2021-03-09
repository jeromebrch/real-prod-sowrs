<?php


namespace App\Data;


use App\Entity\Cause;
use App\Entity\ContractType;
use App\Entity\Country;
use App\Entity\LevelExperience;
use App\Entity\LevelStudy;
use App\Entity\Remuneration;

class SearchCandidate
{
    /**
     * @var string
     */
    public $txt = '';

    /**
     * @var Country
     */
    public $localization;

    /**
     * @var Remuneration
     */
    public $remuneration;

    /**
     * @var ContractType
     */
    public $contractType;

    /**
     * @var Cause
     */
    public $cause;

    /**
     * @var LevelStudy
     */
    public $levelStudy;

    /**
     * @var LevelExperience
     */
    public $levelExp;

    /**
     * @var Country
     */
    public $authorizedCountry;


}