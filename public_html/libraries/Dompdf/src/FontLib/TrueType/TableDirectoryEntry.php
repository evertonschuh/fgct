<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

//namespace FontLib\TrueType;

//use FontLib\Table\DirectoryEntry;

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'FontLib' .DS. 'Table' .DS. 'DirectoryEntry.php');
/**
 * TrueType table directory entry.
 *
 * @package php-font-lib
 */
class TableDirectoryEntry extends DirectoryEntry {
  function __construct(FileTrueType $font) {
    parent::__construct($font);
  }

  function parse() {
    parent::parse();

    $font           = $this->font;
    $this->checksum = $font->readUInt32();
    $this->offset   = $font->readUInt32();
    $this->length   = $font->readUInt32();
    $this->entryLength += 12;
  }
}

