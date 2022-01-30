<?php

namespace App\Presenters;

use App\Model\EmployeeFacade;
use App\Model\GenderFacade;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

final class EmployeePresenter extends Presenter
{
    private EmployeeFacade $employeeFacade;
    private GenderFacade $genderFacade;

    public function __construct(EmployeeFacade $employeeFacade, GenderFacade $genderFacade)
    {
        $this->employeeFacade = $employeeFacade;
        $this->genderFacade = $genderFacade;
    }

    public function renderShow(int $employeeId): void
    {
        $employee = $this->employeeFacade->getById($employeeId);

        if (!$employee) {
            $this->error('Employee not found!');
        }

        $this->template->employee = $employee;
    }

    public function renderEdit(int $employeeId): void
    {
        $employee = $this->employeeFacade->getById($employeeId);

        if (!$employee) {
            $this->error('Employee not found');
        }

        $this->getComponent('employeeForm')->setDefaults($employee->toArray());
    }

    public function renderDelete(int $employeeId): void
    {
        $deletedSuccessfully = $this->employeeFacade->deleteById($employeeId);

        if ($deletedSuccessfully === false) {
            $this->error('Employee not found');
        }

        $this->flashMessage('Employee deleted', 'success');
        $this->redirect('Homepage:default');
    }

    public function employeeFormSucceeded(array $data): void
    {
        $employeeId = $this->getParameter('employeeId');

        if ($employeeId) {
            $returnedId = $this->employeeFacade->updateById($employeeId, $data);

            if (!$returnedId) {
                $this->error('Employee not found');
            }
        } else {
            $returnedId = $this->employeeFacade->create($data);
        }

        $this->flashMessage('Employee saved', 'success');
        $this->redirect('Employee:show', $returnedId);
    }

    protected function createComponentEmployeeForm(): Form
    {
        $form = new Form();
        $form->addText('name', 'Name: ')->setRequired();
        $form->addInteger('age', 'Age: ')->setRequired();

        $genders = $this->genderFacade->getGenders();
        $form->addSelect('gender_id', 'Gender', $genders);

        $form->addSubmit('send', 'Save');
        $form->onSuccess[] = [$this, 'employeeFormSucceeded'];

        return $form;
    }
}
