<?php
class PDF extends FPDI {

    var $_tplIdx;

    function Header() {

	global $fullPathToFile;

	if (is_null($this->_tplIdx)) {

	    // THIS IS WHERE YOU GET THE NUMBER OF PAGES
	    $this->numPages = $this->setSourceFile($fullPathToFile);
	    $this->_tplIdx = $this->importPage(1);

	}
	$this->useTemplate($this->_tplIdx);

    }

    function Footer() {}

}
?>
