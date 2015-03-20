<?php

namespace Elf;

include ('Elf32_Ehdr.php');
include ('Elf64_Ehdr.php');

abstract class ElfN_Ehdr
{
  const EI_NIDENT = 16;
  const EI_PAD = 9;

  const ELFMAG0 = 0x7f;
  const ELFMAG1 = 'E';
  const ELFMAG2 = 'L';
  const ELFMAG3 = 'F';
  const ELFCLASS32 = 1;
  const ELFCLASS64 = 2;

  const ELFDATANONE = 0;
  const ELFDATA2LSB = 1;
  const ELFDATA2MSB = 1;

  const EV_NONE = 0;
  const EV_CURRENT = 1;

  const ELFOSABI_NONE = 0;
  const ELFOSABI_SYSV = 0;
  const ELFOSABI_HPUX = 1;
  const ELFOSABI_NETBSD = 2;
  const ELFOSABI_LINUX = 3;
  const ELFOSABI_SOLARIS = 6;
  const ELFOSABI_IRIX = 8;
  const ELFOSABI_FREEBSD = 9;
  const ELFOSABI_TRU64 = 10;
  const ELFOSABI_ARM = 97;
  const ELFOSABI_STANDALONE = 255;

  const ET_NONE = 0;
  const ET_REL = 1;
  const ET_EXEC = 2;
  const ET_DYN = 3;
  const ET_CORE = 4;

  protected $_fd;
  protected $_reader;

  protected $_ei_data;
  protected $_ei_version;
  protected $_ei_osabi;
  protected $_ei_abiversion;

  protected $_e_type;
  protected $_e_machine;
  protected $_e_version;
  protected $_e_entry;
  protected $_e_phoff;
  protected $_e_shoff;

  static public function readFile($file)
  {
    $fd = @fopen($file, "rb");
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
    $magicDatas = fread($fd, 4);
    $magic = unpack("C", $magicDatas{0});
    return (strlen($magicDatas) === 4
	    && $magic[1] == self::ELFMAG0
	    && $magicDatas{1} === self::ELFMAG1
	    && $magicDatas{2} === self::ELFMAG2
	    && $magicDatas{3} === self::ELFMAG3);
  }

  static protected function _getArchitectureTarget($fd)
  {
    $archDatas = fread($fd, 1);
    $arch = unpack("C", $archDatas)[1];
    if ($arch == self::ELFCLASS32)
      return self::ELFCLASS32;
    else if ($arch == self::ELFCLASS64)
      return self::ELFCLASS64;
    throw new \Exception("Unsupported architecture for ELF file.\n");
  }

  abstract public function ei_class();

  public function __construct($fd)
  {
    $this->_fd = $fd;
    $this->_parse_elf_header();
  }

  protected function _parse_elf_header()
  {
    $this->_parse_e_ident();
    $this->_parse_e_type();
    $this->_parse_e_machine();
    $this->_parse_e_version();
    $this->_parse_e_entry();
    $this->_parse_e_phoff();
    $this->_parse_e_shoff();
  }

  protected function _read($size, $format)
  {
    $datas = fread($this->_fd, $size);
    if (strlen($datas) !== $size)
      throw new \Exception("Invalid read size");
    return unpack($format, $datas)[1];
  }

  protected function _parse_e_ident()
  {
    $this->_parse_ei_data();
    $this->_set_reader();
    $this->_parse_ei_version();
    $this->_parse_ei_osabi();
    $this->_parse_ei_abiversion();
    $this->_reader->skip(ElfN_Ehdr::EI_NIDENT - ElfN_Ehdr::EI_PAD);
  }

  protected function _parse_ei_abiversion()
  {
    $this->_ei_abiversion = $this->_reader->readUChar();
  }

  protected function _parse_ei_osabi()
  {
    $this->_ei_osabi = $this->_reader->readUChar();
  }

  protected function _parse_ei_version()
  {
    $this->_ei_version = $this->_reader->readUChar();
  }

  protected function _parse_ei_data()
  {
    $datas = $this->_read(1, "C");
    if ($datas === ElfN_Ehdr::ELFDATA2LSB)
      $this->_ei_data = ElfN_Ehdr::ELFDATA2LSB;
    else if ($datas === ElfN_Ehdr::ELFDATA2MSB)
      $this->_ei_data = ElfN_Ehdr::ELFDATA2MSB;
    else
      $this->_ei_data = ElfN_Ehdr::ELFDATANONE;
  }

  protected function _set_reader()
  {
    if ($this->_ei_data === ElfN_Ehdr::ELFDATA2LSB)
      $this->_reader = new \Elf\Readers\ReaderLittleEndian;
    else if ($this->_ei_data === ElfN_Ehdr::ELFDATA2MSB)
      $this->_reader = new \Elf\Readers\ReaderBigEndian;
    else
      throw new \Exception ("No valid reader for this kind of binary file.");
    $this->_reader->setFd($this->_fd);
  }

  public function ei_data()
  {
    return $this->_ei_data;
  }

  public function ei_version()
  {
    return $this->_ei_version;
  }
  
  public function ei_osabi()
  {
    return $this->_ei_osabi;
  }

  public function ei_abiversion()
  {
    return $this->_ei_abiversion;
  }

  protected function _parse_e_type()
  {
    $this->_e_type = $this->_reader->readUShort();
  }

  public function e_type()
  {
    return $this->_e_type;
  }
  
  protected function _parse_e_machine()
  {
    $this->_e_machine = $this->_reader->readUShort();
  }

  public function e_machine()
  {
    return $this->_e_machine;
  }

  protected function _parse_e_version()
  {
    $this->_e_version = $this->_reader->readUInt();
  }

  public function e_version()
  {
    return $this->_e_version;
  }

  abstract protected function _parse_e_entry();

  public function e_entry()
  {
    return $this->_e_entry;
  }

  abstract protected function _parse_e_phoff();

  public function e_phoff()
  {
    return $this->_e_phoff;
  }

  abstract protected function _parse_e_shoff();

  public function e_shoff()
  {
    return $this->_e_shoff;
  }
}