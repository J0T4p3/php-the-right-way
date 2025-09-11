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

if (setupTasks()) {
    echo "Found tasks file!" . PHP_EOL;
} else {
    echo "Tasks file not found. New one created!" . PHP_EOL;
}

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
    $task = $tasks["$taskId"];
    $task["completed"] = !$task["completed"];
    $tasks["$taskId"] = $task;
    saveTasksToFile($tasks);
}

function createTask($task)
{
    $tasksFile = getTasksFile();
    $tasks = $tasksFile['tasks'];
    $tasks[] = $task;
    saveTasksToFile($tasks);
}

function deleteTask($taskId)
{
    $tasksFile = getTasksFile();
    $tasks = $tasksFile['tasks'];
    unset($tasks[$taskId]);
    saveTasksToFile($tasks);
}

$task = [
    "nome" => "Quarto teste",
    "completed" => false,
];
?>

<!DOCTYPE html>
<html>

<body>

    <h1>My First Heading</h1>
    <p>My first paragraph.</p>

</body>

</html>
