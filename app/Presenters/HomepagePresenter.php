<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;

final class HomepagePresenter extends Presenter
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function renderDefault(): void
    {
        $this->template->employees = $this->database
            ->table('employees')
            ->order('created_at DESC')
            ->limit(10);
    }
}
