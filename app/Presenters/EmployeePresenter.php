<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;

final class EmployeePresenter extends Presenter
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function renderShow(int $employeeId): void
    {
        $employee = $this->database
            ->table('employees')
            ->get($employeeId);

        if (!$employee) {
            $this->error('Employee not found!');
        }

        $this->template->employee = $employee;
    }

    public function renderEdit(int $employeeId): void
    {
        $employee = $this->database
            ->table('employees')
            ->get($employeeId);

        if (!$employee) {
            $this->error('Employee not found');
        }

        $this->getComponent('employeeForm')
            ->setDefaults($employee->toArray());
    }

    public function renderDelete(int $employeeId): void
    {
        $employee = $this->database
            ->table('employees')
            ->get($employeeId);

        if (!$employee) {
            $this->error('Employee not found');
        }

        $employee->delete();

        $this->flashMessage('Employee deleted', 'success');
        $this->redirect('Homepage:default');
    }

    public function employeeFormSucceeded(array $data): void
    {
        $employeeId = $this->getParameter('employeeId');

        if ($employeeId) {
            $employee = $this->database
                ->table('employees')
                ->get($employeeId);
            $employee->update($data);
        } else {
            $employee = $this->database
            ->table('employees')
            ->insert($data);
        }

        $this->flashMessage('Employee saved', 'success');
        $this->redirect('Employee:show', $employee->id);
    }

    protected function createComponentEmployeeForm(): Form
    {
        $form = new Form();
        $form->addText('name', 'Name: ')->setRequired();
        $form->addInteger('age', 'Age: ')->setRequired();

        $genders = [1 => 'Male', 2 => 'Female'];
        $form->addSelect('gender_id', 'Gender', $genders);

        $form->addSubmit('send', 'Save');
        $form->onSuccess[] = [$this, 'employeeFormSucceeded'];

        return $form;
    }
}
