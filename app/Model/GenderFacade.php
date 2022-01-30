<?php

namespace App\Model;

use Nette\Database\Explorer;
use Nette\SmartObject;

final class GenderFacade
{
    use SmartObject;

    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function getGenders()
    {
        return $this->database->table('genders')->fetchPairs('id', 'name');
    }
}
