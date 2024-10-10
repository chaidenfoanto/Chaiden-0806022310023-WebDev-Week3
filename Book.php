<?php
class Book {
    protected $title;
    protected $author;
    protected $publicationYear;

    public function __construct($title, $author, $publicationYear) {
        $this->title = $title;
        $this->author = $author;
        $this->publicationYear = $publicationYear;
    }

    public function getDetails() {
        return "Judul: {$this->title}, Penulis: {$this->author}, Tahun Terbit: {$this->publicationYear}";
    }
}
?>
