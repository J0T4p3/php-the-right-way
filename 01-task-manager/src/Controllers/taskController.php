<?php

require_once __DIR__ . "/../Models/tasks.php";

class taskController
{
    public function index()
    {
        $tasks = Task::all();
        include __DIR__ . "/../Views/tasks/index.php";
    }

    public function create()
    {
        if (!empty($_POST['newTask'])) {
            Task::create($_POST['newTask']);
        }
        header("Location: /");
    }

    public function toggle()
    {
        if (isset($_POST['toggTask'])) {
            Task::toggle((int)$_POST['toggTask']);
        }
        header("Location: /");
    }

    public function delete()
    {
        if (isset($_POST['delTask'])) {
            Task::delete((int)$_POST['delTask']);
        }
        header("Location: /");
    }
}
