<?php

$TASKSFILE = "tasks.json";

function setupTasks($filename)
{
    if (!file_exists($filename)) {
        $default_tasks = json_encode(["tasks" => []], JSON_PRETTY_PRINT);
        if (file_put_contents($filename, $default_tasks) === false) {
            throw new RuntimeException("Failed to create tasks file: $filename");
        }
        return false; // new file created
    }
    return true; // file already existed
}

if (setupTasks($TASKSFILE)) {
    echo "Found tasks file!";
} else {
    echo "Tasks file not found. New one created!";
}
