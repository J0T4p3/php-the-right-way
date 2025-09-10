<?php

const TASKSFILE = "tasks.json";

# Setting up tasks database file
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

    echo "task created!", PHP_EOL;
}

# Configuring CRUD operations for tasks

# Task model:
/* Task: {
 *      "name": string,
 *      "completed": bool,
 * }
 **/

function createTask($task)
{
    $tasksFile = getTasksFile();
    $tasks = $tasksFile['tasks'];
    $tasks[] = $task;
    saveTasksToFile($tasks);
}

$task = [
    "nome" => "outro teste",
    "completed" => true,
];

createTask($task);
