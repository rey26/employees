<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\EmployeeFacade;
use Nette\Application\UI\Presenter;

final class HomepagePresenter extends Presenter
{
    private EmployeeFacade $employeeFacade;

    public function __construct(EmployeeFacade $employeeFacade)
    {
        $this->employeeFacade = $employeeFacade;
    }

    public function renderDefault(): void
    {
        $this->template->employees = $this->employeeFacade->getAll();
    }
}
