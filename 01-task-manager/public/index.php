<?php

const TASKSFILE = "tasks.json";

# Setting up tasks "database" file
function setupTasks()
{
    if (!file_exists(TASKSFILE)) {
        $default_tasks = json_encode(
            [
                "tasks" => [],
                "last_updated" => date('Y-m-d H:i:s')
            ],
            JSON_PRETTY_PRINT
        );
        if (file_put_contents(TASKSFILE, $default_tasks) === false) {
            throw new RuntimeException("Failed to create tasks file: TASKSFILE");
        }
        return false; // new file created
    }
    return true; // file already existed
}

setupTasks();

function getTasksFile()
{
    return json_decode(
        file_get_contents(TASKSFILE),
        true,
    );
}

function saveTasksToFile($tasks)
{
    $tasksJSON = json_encode([
        "tasks" => $tasks,
        "last_updated" => date('Y-m-d H:i:s'),
    ]);

    if (file_put_contents(TASKSFILE, $tasksJSON) === false) {
        throw new RuntimeException("Failed to store tasks file: TASKSFILE");
    }
}

# Configuring CRUD operations for tasks

# Task model:
/* Task: {
 *      "name": string,
 *      "completed": bool,
 * }
 **/

function getAllTasks()
{
    $tasksFile = getTasksFile();
    return $tasksFile['tasks'];
}

function getTask($taskId)
{
    $tasksFile = getTasksFile();
    return $tasksFile['tasks']["$taskId"];
}

function toggleTask($taskId)
{
    $tasksFile = getTasksFile();
    $tasks = $tasksFile['tasks'];
    $task = $tasks[$taskId];
    $task['completed'] = !$task['completed'];
    $tasks[$taskId] = $task;
    saveTasksToFile($tasks);
}

function createTask($taskTitle)
{
    $tasksFile = getTasksFile();
    $tasks = $tasksFile['tasks'];

    $tasks[] = ['name' => $taskTitle, 'completed' => false,];
    saveTasksToFile($tasks);
}

function deleteTask($taskId)
{
    $tasksFile = getTasksFile();
    $tasks = $tasksFile['tasks'];
    unset($tasks[$taskId]);
    saveTasksToFile($tasks);
}

?>

<!doctype html>
<html lang="pt_br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Get Things Done</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <script>
        // Remove as variáveis da requisição
        window.history.replaceState(null, '', window.location.pathname);
    </script>

    <header>

        <nav class="navbar navbar-expand-md bg-primary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">GTD</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Início</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="bg-body p-4">
        <div class="card mx-5">
            <form class="card-body">
                <div class="mb-3">
                    <label for="task" class="form-label">Tarefa</label>
                    <input type="text" class="form-control" id="newTask" aria-describedby="task" name="newTask">
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
        <div class="row mt-4 mx-5 g-3">
            <?php

            // Inclui nova tarefa, se houver
            $newTask = $_GET["newTask"];
            if ($newTask) {
                createTask($newTask);
            }

            // remove a tarefa, se houver
            $delTask = $_GET["delTask"];
            if ($delTask) {
                deleteTask($delTask);
            }

            // Completa a tarefa
            $toggTask = $_GET["toggTask"];
            if ($toggTask) {
                toggleTask($toggTask);
            }

            // Lista todas as tarefas e apresenta em tela
            $tasks = getAllTasks();

            if (!$tasks) {
            ?>
                <div class="text-center mt-5">Nenhuma tarefa cadastrada</div>
                <?php
            } else {
                foreach ($tasks as $id => $task) {
                ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="row card-body">
                                <div class="col">
                                    <h5><?= $id . " - " . $task["name"] ?></h5>
                                    <p>Status de conclusão: <?= $task["completed"] ? 'Concluído' : 'Não Concluído' ?></p>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <form>
                                        <input onchange="this.form.submit()" <?= $task["completed"] ? 'checked' : '' ?> class="form-check-input" type="checkbox" value="<?= $id ?>" name="toggTask">
                                    </form>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form>
                                    <input name='delTask' value='<?= $id ?>' hidden>
                                    <button type="submit" class="btn btn-primary">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
