<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2011 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2011 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.6, 2011-02-27
 */

define('IDENTIFIER_OLE', pack('CCCCCCCC', 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1));
#[\AllowDynamicProperties]
class PHPExcel_Shared_OLERead {
	private $data = '';

	// OLE identifier
	const IDENTIFIER_OLE = IDENTIFIER_OLE;

	// Size of a sector = 512 bytes
	const BIG_BLOCK_SIZE					= 0x200;

	// Size of a short sector = 64 bytes
	const SMALL_BLOCK_SIZE					= 0x40;

	// Size of a directory entry always = 128 bytes
	const PROPERTY_STORAGE_BLOCK_SIZE		= 0x80;

	// Minimum size of a standard stream = 4096 bytes, streams smaller than this are stored as short streams
	const SMALL_BLOCK_THRESHOLD				= 0x1000;

	// header offsets
	const NUM_BIG_BLOCK_DEPOT_BLOCKS_POS	= 0x2c;
	const ROOT_START_BLOCK_POS				= 0x30;
	const SMALL_BLOCK_DEPOT_BLOCK_POS		= 0x3c;
	const EXTENSION_BLOCK_POS				= 0x44;
	const NUM_EXTENSION_BLOCK_POS			= 0x48;
	const BIG_BLOCK_DEPOT_BLOCKS_POS		= 0x4c;

	// property storage offsets (directory offsets)
	const SIZE_OF_NAME_POS					= 0x40;
	const TYPE_POS							= 0x42;
	const START_BLOCK_POS					= 0x74;
	const SIZE_POS							= 0x78;



	public $wrkbook						= null;
	public $summaryInformation			= null;
	public $documentSummaryInformation	= null;


	/**
	 * Read the file
	 *
	 * @param $sFileName string Filename
	 * @throws Exception
	 */
	public function read($sFileName)
	{
		// Check if file exists and is readable
		if(!is_readable($sFileName)) {
			throw new Exception("Could not open " . $sFileName . " for reading! File does not exist, or it is not readable.");
		}

		// Get the file data
		$this->data = file_get_contents($sFileName);

		// Check OLE identifier
		if (substr($this->data, 0, 8) != self::IDENTIFIER_OLE) {
			throw new Exception('The filename ' . $sFileName . ' is not recognised as an OLE file');
		}

		// Total number of sectors used for the SAT
		$this->numBigBlockDepotBlocks = self::_GetInt4d($this->data, self::NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);

		// SecID of the first sector of the directory stream
		$this->rootStartBlock = self::_GetInt4d($this->data, self::ROOT_START_BLOCK_POS);

		// SecID of the first sector of the SSAT (or -2 if not extant)
		$this->sbdStartBlock = self::_GetInt4d($this->data, self::SMALL_BLOCK_DEPOT_BLOCK_POS);

		// SecID of the first sector of the MSAT (or -2 if no additional sectors are used)
		$this->extensionBlock = self::_GetInt4d($this->data, self::EXTENSION_BLOCK_POS);

		// Total number of sectors used by MSAT
		$this->numExtensionBlocks = self::_GetInt4d($this->data, self::NUM_EXTENSION_BLOCK_POS);

		$bigBlockDepotBlocks = array();
		$pos = self::BIG_BLOCK_DEPOT_BLOCKS_POS;

		$bbdBlocks = $this->numBigBlockDepotBlocks;

		if ($this->numExtensionBlocks != 0) {
			$bbdBlocks = (self::BIG_BLOCK_SIZE - self::BIG_BLOCK_DEPOT_BLOCKS_POS)/4;
		}

		for ($i = 0; $i < $bbdBlocks; ++$i) {
			  $bigBlockDepotBlocks[$i] = self::_GetInt4d($this->data, $pos);
			  $pos += 4;
		}

		for ($j = 0; $j < $this->numExtensionBlocks; ++$j) {
			$pos = ($this->extensionBlock + 1) * self::BIG_BLOCK_SIZE;
			$blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, self::BIG_BLOCK_SIZE / 4 - 1);

			for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; ++$i) {
				$bigBlockDepotBlocks[$i] = self::_GetInt4d($this->data, $pos);
				$pos += 4;
			}

			$bbdBlocks += $blocksToRead;
			if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
				$this->extensionBlock = self::_GetInt4d($this->data, $pos);
			}
		}

		$pos = $index = 0;
		$this->bigBlockChain = array();

		$bbs = self::BIG_BLOCK_SIZE / 4;
		for ($i = 0; $i < $this->numBigBlockDepotBlocks; ++$i) {
			$pos = ($bigBlockDepotBlocks[$i] + 1) * self::BIG_BLOCK_SIZE;

			for ($j = 0 ; $j < $bbs; ++$j) {
				$this->bigBlockChain[$index] = self::_GetInt4d($this->data, $pos);
				$pos += 4 ;
				++$index;
			}
		}

		$pos = $index = 0;
		$sbdBlock = $this->sbdStartBlock;
		$this->smallBlockChain = array();

		while ($sbdBlock != -2) {
			$pos = ($sbdBlock + 1) * self::BIG_BLOCK_SIZE;

			for ($j = 0; $j < $bbs; ++$j) {
				$this->smallBlockChain[$index] = self::_GetInt4d($this->data, $pos);
				$pos += 4;
				++$index;
			}

			$sbdBlock = $this->bigBlockChain[$sbdBlock];
		}

		// read the directory stream
		$block = $this->rootStartBlock;
		$this->entry = $this->_readData($block);

		$this->_readPropertySets();
	}

	/**
	 * Extract binary stream data
	 *
	 * @return string
	 */
	public function getStream($stream)
	{
		if (is_null($stream)) {
			return null;
		}

		$streamData = '';

		if ($this->props[$stream]['size'] < self::SMALL_BLOCK_THRESHOLD) {
			$rootdata = $this->_readData($this->props[$this->rootentry]['startBlock']);

			$block = $this->props[$stream]['startBlock'];

			while ($block != -2) {
	  			$pos = $block * self::SMALL_BLOCK_SIZE;
				$streamData .= substr($rootdata, $pos, self::SMALL_BLOCK_SIZE);

				$block = $this->smallBlockChain[$block];
			}

			return $streamData;
		} else {
			$numBlocks = $this->props[$stream]['size'] / self::BIG_BLOCK_SIZE;
			if ($this->props[$stream]['size'] % self::BIG_BLOCK_SIZE != 0) {
				++$numBlocks;
			}

			if ($numBlocks == 0) return '';

			$block = $this->props[$stream]['startBlock'];

			while ($block != -2) {
				$pos = ($block + 1) * self::BIG_BLOCK_SIZE;
				$streamData .= substr($this->data, $pos, self::BIG_BLOCK_SIZE);
				$block = $this->bigBlockChain[$block];
			}

			return $streamData;
		}
	}

	/**
	 * Read a standard stream (by joining sectors using information from SAT)
	 *
	 * @param int $bl Sector ID where the stream starts
	 * @return string Data for standard stream
	 */
	private function _readData($bl)
	{
		$block = $bl;
		$data = '';

		while ($block != -2)  {
			$pos = ($block + 1) * self::BIG_BLOCK_SIZE;
			$data .= substr($this->data, $pos, self::BIG_BLOCK_SIZE);
			$block = $this->bigBlockChain[$block];
		}
		return $data;
	 }

	/**
	 * Read entries in the directory stream.
	 */
	private function _readPropertySets() {
		$offset = 0;

		// loop through entires, each entry is 128 bytes
		$entryLen = strlen($this->entry);
		while ($offset < $entryLen) {
			// entry data (128 bytes)
			$d = substr($this->entry, $offset, self::PROPERTY_STORAGE_BLOCK_SIZE);

			// size in bytes of name
			$nameSize = ord($d[self::SIZE_OF_NAME_POS]) | (ord($d[self::SIZE_OF_NAME_POS+1]) << 8);

			// type of entry
			$type = ord($d[self::TYPE_POS]);

			// sectorID of first sector or short sector, if this entry refers to a stream (the case with workbook)
			// sectorID of first sector of the short-stream container stream, if this entry is root entry
			$startBlock = self::_GetInt4d($d, self::START_BLOCK_POS);

			$size = self::_GetInt4d($d, self::SIZE_POS);

			$name = str_replace("\x00", "", substr($d,0,$nameSize));

			$this->props[] = array (
				'name' => $name,
				'type' => $type,
				'startBlock' => $startBlock,
				'size' => $size);

			// Workbook directory entry (BIFF5 uses Book, BIFF8 uses Workbook)
			if (($name == 'Workbook') || ($name == 'Book') || ($name == 'WORKBOOK') || ($name == 'BOOK')) {
				$this->wrkbook = count($this->props) - 1;
			}

			// Root entry
			if ($name == 'Root Entry' || $name == 'ROOT ENTRY' || $name == 'R') {
				$this->rootentry = count($this->props) - 1;
			}

			// Summary information
			if ($name == chr(5) . 'SummaryInformation') {
//				echo 'Summary Information<br />';
				$this->summaryInformation = count($this->props) - 1;
			}

			// Additional Document Summary information
			if ($name == chr(5) . 'DocumentSummaryInformation') {
//				echo 'Document Summary Information<br />';
				$this->documentSummaryInformation = count($this->props) - 1;
			}

			$offset += self::PROPERTY_STORAGE_BLOCK_SIZE;
		}

	}

	/**
	 * Read 4 bytes of data at specified position
	 *
	 * @param string $data
	 * @param int $pos
	 * @return int
	 */
	private static function _GetInt4d($data, $pos)
	{
		// FIX: represent numbers correctly on 64-bit system
		// http://sourceforge.net/tracker/index.php?func=detail&aid=1487372&group_id=99160&atid=623334
		// Hacked by Andreas Rehm 2006 to ensure correct result of the <<24 block on 32 and 64bit systems
		$_or_24 = ord($data[$pos + 3]);
		if ($_or_24 >= 128) {
			// negative number
			$_ord_24 = -abs((256 - $_or_24) << 24);
		} else {
			$_ord_24 = ($_or_24 & 127) << 24;
		}
		return ord($data[$pos]) | (ord($data[$pos + 1]) << 8) | (ord($data[$pos + 2]) << 16) | $_ord_24;
	}

}
