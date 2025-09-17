<?php
class task
{
    private const FILE = __DIR__ . "/../../resources/tasks.json";

    private static function loadFile(): array
    {
        if (!file_exists(self::FILE)) {
            file_put_contents(self::FILE, json_encode(["tasks" => [], "last_updated" => date('Y-m-d H:i:s')], JSON_PRETTY_PRINT));
        }
        return json_decode(file_get_contents(self::FILE), true);
    }

    private static function saveFile(array $tasks): void
    {
        file_put_contents(
            self::FILE, json_encode(
                [
                "tasks" => $tasks,
                "last_updated" => date('Y-m-d H:i:s')
                ], JSON_PRETTY_PRINT
            )
        );
    }

    public static function all(): array
    {
        return self::loadFile()['tasks'];
    }

    public static function create(string $title): void
    {
        $data = self::loadFile();
        $data['tasks'][] = ['name' => $title, 'completed' => false];
        self::saveFile($data['tasks']);
    }

    public static function delete(int $id): void
    {
        $data = self::loadFile();
        unset($data['tasks'][$id]);
        self::saveFile($data['tasks']);
    }

    public static function toggle(int $id): void
    {
        $data = self::loadFile();
        $data['tasks'][$id]['completed'] = !$data['tasks'][$id]['completed'];
        self::saveFile($data['tasks']);
    }
}
