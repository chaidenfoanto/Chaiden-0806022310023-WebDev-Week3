<?php
class PrintedBook extends Book {
    private $numberOfPages;

    public function __construct($title, $author, $publicationYear, $numberOfPages) {
        parent::__construct($title, $author, $publicationYear);
        $this->numberOfPages = $numberOfPages;
    }

    public function getDetails() {
        return parent::getDetails() . ", Jumlah Halaman: {$this->numberOfPages}";
    }
}
?>
