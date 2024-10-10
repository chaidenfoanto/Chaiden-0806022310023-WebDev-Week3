<?php
class EBook extends Book {
    private $fileSize;

    public function __construct($title, $author, $publicationYear, $fileSize) {
        parent::__construct($title, $author, $publicationYear);
        $this->fileSize = $fileSize;
    }

    public function getDetails() {
        return parent::getDetails() . ", Ukuran File: {$this->fileSize} MB";
    }
}
?>
