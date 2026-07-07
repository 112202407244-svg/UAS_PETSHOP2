<?php

class Category extends Model
{
    protected string $table = 'categories';

    public function find($id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM categories WHERE id = ?",
            [(int) $id]
        );
    }

    public function getAll(string $extra = ''): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM categories {$extra} ORDER BY name ASC"
        );
    }

    public function store(array $data): bool
    {
        $name = trim($data['name'] ?? '');
        $slug = $this->createUniqueSlug($data['slug'] ?? $name);

        if ($name === '') {
            return false;
        }

        return $this->db->execute(
            "INSERT INTO categories (name, slug) VALUES (?, ?)",
            [$name, $slug]
        ) > 0;
    }

    public function update($id, array $data): int
    {
        $name = trim($data['name'] ?? '');
        $slug = $this->createUniqueSlug($data['slug'] ?? $name, (int) $id);

        if ($name === '') {
            return 0;
        }

        return $this->db->execute(
            "UPDATE categories SET name = ?, slug = ? WHERE id = ?",
            [$name, $slug, (int) $id]
        );
    }

    public function delete($id): int
    {
        try {
            return $this->db->execute(
                "DELETE FROM categories WHERE id = ?",
                [(int) $id]
            );
        } catch (PDOException $e) {
            return 0;
        }
    }

    private function createUniqueSlug(string $text, int $ignoreId = 0): string
    {
        $baseSlug = $this->slugify($text);
        $slugSeed = $baseSlug !== '' ? $baseSlug : 'kategori';
        $slug = $slugSeed;
        $suffix = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $suffix++;
            $slug = $slugSeed . '-' . $suffix;
        }

        return $slug;
    }

    private function slugExists(string $slug, int $ignoreId = 0): bool
    {
        $sql = "SELECT id FROM categories WHERE slug = ?";
        $params = [$slug];

        if ($ignoreId > 0) {
            $sql .= " AND id != ?";
            $params[] = $ignoreId;
        }

        return $this->db->fetchOne($sql, $params) !== false;
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim((string) $text, '-');
    }
}
