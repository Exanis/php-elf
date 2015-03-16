<?php

namespace Elf;

include ('Elf32_Ehdr.php');
include ('Elf64_Ehdr.php');

abstract class ElfN_Ehdr
{
  const ELFMAG0 = 0x7f;
  const ELFMAG1 = 'E';
  const ELFMAG2 = 'L';
  const ELFMAG3 = 'F';
  const ELFCLASS32 = 1;
  const ELFCLASS64 = 2;

  protected $_isValidFile;

  static public function readFile($file)
  {
    $fd = fopen($file, "rb");
    if ($fd === false)
      throw new \Exception("Cannot open $file for reading.\n");

    if (!self::_isValidElfFile($fd))
      throw new \Exception("Invalid ELF File: $file");
    
    if (self::_getArchitectureTarget($fd) === self::ELFCLASS32)
      return new Elf32_Ehdr($fd);
    else
      return new Elf64_Ehdr($fd);
  }
	
  static protected function _isValidElfFile($fd)
  {
    $magic = fread($fd, 4);
    return (strlen($magic) === 4
	    && $magic{0} == self::ELFMAG0
	    && $magic{1} === self::ELFMAG1
	    && $magic{2} === self::ELFMAG2
	    && $magic{3} === self::ELFMAG3);
  }

  static protected function _getArchitectureTarget($fd)
  {
    $arch = fread($fd, 1);
    if ($arch == self::ELFCLASS32)
      return self::ELFCLASS32;
    else if ($arch == self::ELFCLASS64)
      return self::ELFCLASS64;
    throw new \Exception("Unsupported architecture for ELF file.\n");
  }
}