<?php

class NoteModel
{
    private static $file = __DIR__ . '/NoteData.json';

    /**
     * Ambil semua note dari file JSON.
     * @return array
     */
    public static function getAll()
    {
        if (!file_exists(self::$file)) return [];
        $json = file_get_contents(self::$file);
        return json_decode($json, true) ?: [];
    }

    /**
     * Tambah note baru ke file JSON.
     * @param string $text
     * @return array
     */
    public static function add($text)
    {
        $notes = self::getAll();
        $id = count($notes) > 0 ? max(array_column($notes, 'id')) + 1 : 1;
        $note = [
            'id' => $id,
            'text' => $text,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $notes[] = $note;
        file_put_contents(self::$file, json_encode($notes, JSON_PRETTY_PRINT));
        return $note;
    }

    /**
     * Hapus note berdasarkan id dari file JSON.
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        $notes = self::getAll();
        $notes = array_filter($notes, fn($n) => $n['id'] != $id);
        file_put_contents(self::$file, json_encode(array_values($notes), JSON_PRETTY_PRINT));
        return true;
    }
}