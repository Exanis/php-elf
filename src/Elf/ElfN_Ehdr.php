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
  const ELFOSABI_GNU = 3;
  const ELFOSABI_LINUX = 3;
  const ELFOSABI_SOLARIS = 6;
  const ELFOSABI_AIX = 7;
  const ELFOSABI_IRIX = 8;
  const ELFOSABI_FREEBSD = 9;
  const ELFOSABI_TRU64 = 10;
  const ELFOSABI_MODESTO = 11;
  const ELFOSABI_OPENBSD = 12;
  const ELFOSABI_ARM_AEABI = 64;
  const ELFOSABI_ARM = 97;
  const ELFOSABI_STANDALONE = 255;

  const ET_NONE = 0;
  const ET_REL = 1;
  const ET_EXEC = 2;
  const ET_DYN = 3;
  const ET_CORE = 4;
  const ET_NUM = 5;
  const ET_LOOS = 0xfe00;
  const ET_HIOS = 0xfeff;
  const ET_LOPROC = 0xff00;
  const ET_HIPROC = 0xffff;

  const EM_NONE = 0;
  const EM_M32 = 1;
  const EM_SPARC = 2;
  const EM_386 = 3;
  const EM_68K = 4;
  const EM_88K = 5;
  const EM_860 = 7;
  const EM_MIPS = 8;
  const EM_S370 = 9;
  const EM_MIPS_RS3_LE = 10;
  const EM_PARISC = 15;
  const EM_VPP500 = 17;
  const EM_SPARC32PLUS = 18;
  const EM_960 = 19;
  const EM_PPC = 20;
  const EM_PPC64 = 21;
  const EM_S390 = 22;
  const EM_V800 = 36;
  const EM_FR20 = 37;
  const EM_RH32 = 38;
  const EM_RCE = 39;
  const EM_ARM = 40;
  const EM_FAKE_ALPHA = 41;
  const EM_SH = 42;
  const EM_SPARCV9 = 43;
  const EM_TRICORE = 44;
  const EM_ARC = 45;
  const EM_H8_300 = 46;
  const EM_H8_300H = 47;
  const EM_H8S = 48;
  const EM_H8_500 = 49;
  const EM_IA_64 = 50;
  const EM_MIPS_X = 51;
  const EM_COLDFIRE = 52;
  const EM_68HC12 = 53;
  const EM_MMA = 54;
  const EM_PCP = 55;
  const EM_NCPU = 56;
  const EM_NDR1	= 57;
  const EM_STARCORE = 58;
  const EM_ME16 = 59;
  const EM_ST100 = 60;
  const EM_TINYJ = 61;
  const EM_X86_64 = 62;
  const EM_PDSP = 63;
  const EM_FX66 = 66;
  const EM_ST9PLUS = 67;
  const EM_ST7 = 68;
  const EM_68HC16 = 69;
  const EM_68HC11 = 70;
  const EM_68HC08 = 71;
  const EM_68HC05 = 72;
  const EM_SVX = 73;
  const EM_ST19 = 74;
  const EM_VAX = 75;
  const EM_CRIS = 76;
  const EM_JAVELIN = 77;
  const EM_FIREPATH = 78;
  const EM_ZSP = 79;
  const EM_MMIX = 80;
  const EM_HUANY = 81;
  const EM_PRISM = 82;
  const EM_AVR = 83;
  const EM_FR30 = 84;
  const EM_D10V = 85;
  const EM_D30V = 86;
  const EM_V850 = 87;
  const EM_M32R = 88;
  const EM_MN10300 = 89;
  const EM_MN10200 = 90;
  const EM_PJ = 91;
  const EM_OPENRISC = 92;
  const EM_ARC_A5 = 93;
  const EM_XTENSA = 94;
  const EM_NUM = 95;
  
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
  protected $_e_flags;
  protected $_e_ehsize;
  protected $_e_phentsize;
  protected $_e_phnum;
  protected $_e_shentsize;
  protected $_e_shnum;
  protected $_e_shstrndx;

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
    $this->_parse_e_flags();
    $this->_parse_e_ehsize();
    $this->_parse_e_phentsize();
    $this->_parse_e_phnum();
    $this->_parse_e_shentsize();
    $this->_parse_e_shnum();
    $this->_parse_e_shstrndx();
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

  protected function _parse_e_flags()
  {
    $this->_e_flags = $this->_reader->readUInt();
  }

  public function e_flags()
  {
    return $this->_e_flags;
  }

  protected function _parse_e_ehsize()
  {
    $this->_e_ehsize = $this->_reader->readUShort();
  }

  public function e_ehsize()
  {
    return $this->_e_ehsize;
  }

  protected function _parse_e_phentsize()
  {
    $this->_e_phentsize = $this->_reader->readUShort();
  }

  public function e_phentsize()
  {
    return $this->_e_phentsize;
  }

  protected function _parse_e_phnum()
  {
    $this->_e_phnum = $this->_reader->readUShort();
  }

  public function e_phnum()
  {
    return $this->_e_phnum;
  }

  protected function _parse_e_shentsize()
  {
    $this->_e_shentsize = $this->_reader->readUShort();
  }

  public function e_shentsize()
  {
    return $this->_e_shentsize;
  }

  protected function _parse_e_shnum()
  {
    $this->_e_shnum = $this->_reader->readUShort();
  }

  public function e_shnum()
  {
    return $this->_e_shnum;
  }

  protected function _parse_e_shstrndx()
  {
    $this->_e_shstrndx = $this->_reader->readUShort();
  }

  public function e_shstrndx()
  {
    return $this->_e_shstrndx;
  }

  abstract public function getPhdr();
}