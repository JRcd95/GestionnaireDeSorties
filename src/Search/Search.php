<?php
namespace App\Search;

use App\Entity\Campus;
use App\Entity\Sortie;
use DateTime;

class Search {

    /**
     * @var Campus
     */
    public $campusSearch;
    /**
     * @var string
     */
    public $recherche = '';
    /**
     * @var DateTime
     */
    public $dateDebutSearch;
    /**
     * @var DateTime
     */
    public $dateFinSearch;

    public $sortieOrganisee;

    public $sortieInscrit;

    public $sortieNonInscrit;

    public $sortiePassee;




}