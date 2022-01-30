<?php

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\SmartObject;

class EmployeeFacade
{
    use SmartObject;

    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function getAll(): Selection
    {
        return $this->database
            ->table('employees')
            ->select('employees.*, gender.name AS gender_name')
            ->order('created_at DESC')
            ->limit(10);
    }

    public function getById(int $id): ?ActiveRow
    {
        return $this->database
            ->table('employees')
            ->select('employees.*, gender.id AS gender_id, gender.name AS gender_name')
            ->get($id);
    }

    public function deleteById(int $id): bool
    {
        $employee = $this->database
            ->table('employees')
            ->get($id);

        if (!$employee) {
            return false;
        }

        $employee->delete();

        return true;
    }

    public function updateById(int $id, array $data): ?int
    {
        $employee = $this->getById($id);

        if (!$employee) {
            return null;
        }
        $employee->update($data);

        return $employee->id;
    }

    public function create(array $data): int
    {
        $employee = $this->database
            ->table('employees')
            ->insert($data);

        return $employee->id;
    }
}
