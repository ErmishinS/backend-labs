<?php

namespace App\Services;

use Illuminate\Support\Arr;
use RuntimeException;

class JsonStorageService
{
    protected string $dir;

    public function __construct(string $dir = null)
    {
        $this->dir = $dir ?? storage_path('app/data');
        if (!is_dir($this->dir)) {
            if (!mkdir($this->dir, 0775, true) && !is_dir($this->dir)) {
                throw new RuntimeException("Unable to create data dir: {$this->dir}");
            }
        }
    }

    protected function path(string $name): string
    {
        return rtrim($this->dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name . '.json';
    }

    /**
     * Read array from a file (returns array)
     */
    public function read(string $name): array
    {
        $path = $this->path($name);
        if (!file_exists($path)) {
            return [];
        }

        $contents = file_get_contents($path);
        $data = json_decode($contents, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Write array to file atomically
     */
    public function write(string $name, array $data): void
    {
        $path = $this->path($name);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        // atomic write
        $tmp = $path . '.tmp';
        if (file_put_contents($tmp, $json, LOCK_EX) === false) {
            throw new RuntimeException("Unable to write to $tmp");
        }
        rename($tmp, $path);
    }

    /**
     * Create a new record in a collection (returns created item)
     */
    public function create(string $name, array $item): array
    {
        $items = $this->read($name);

        // determine next id (integer)
        $maxId = 0;
        foreach ($items as $it) {
            if (isset($it['id']) && is_numeric($it['id'])) {
                $maxId = max($maxId, (int)$it['id']);
            }
        }
        $item['id'] = $maxId + 1;

        $items[] = $item;
        $this->write($name, $items);

        return $item;
    }

    /**
     * Find item by id or return null
     */
    public function find(string $name, $id): ?array
    {
        $items = $this->read($name);
        foreach ($items as $it) {
            if ((string)$it['id'] === (string)$id) {
                return $it;
            }
        }
        return null;
    }

    /**
     * Delete item by id. Returns true if deleted
     */
    public function delete(string $name, $id): bool
    {
        $items = $this->read($name);
        $found = false;
        $new = [];
        foreach ($items as $it) {
            if ((string)$it['id'] === (string)$id) {
                $found = true;
                continue;
            }
            $new[] = $it;
        }
        if ($found) {
            $this->write($name, $new);
        }
        return $found;
    }

    /**
     * Get all items
     */
    public function all(string $name): array
    {
        return $this->read($name);
    }

    /**
     * Update item by id (partial replace). Returns updated item or null
     */
    public function update(string $name, $id, array $data): ?array
    {
        $items = $this->read($name);
        $updated = null;
        foreach ($items as &$it) {
            if ((string)$it['id'] === (string)$id) {
                $it = array_merge($it, $data);
                $updated = $it;
                break;
            }
        }
        if ($updated !== null) {
            $this->write($name, $items);
        }
        return $updated;
    }
}