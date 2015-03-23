<?php

namespace Elf;

abstract class ElfN_Phdr
{
  protected $_reader;

  protected $_p_type;
  protected $_p_flags;
  protected $_p_offset;
  protected $_p_vaddr;
  protected $_p_paddr;
  protected $_p_filesz;
  protected $_p_memsz;
  protected $_p_align;

  public function __construct(\Elf\ElfN_Ehdr $ehdr, $phdr_num, \Elf\Readers\IReader $reader)
  {
    if ($ehdr->e_phoff === 0)
      throw new \Exception("This file does not contian a program header table.");
    if ($phdr_num < 0 || $phdr_num >= $ehdr->e_phnum())
      throw new \Exception("phdr_num out of range : $phdr_num");
    $this->_reader->seek($ehdr->e_phoff() + $phdr_num * $ehdr->phentsize());

    $this->_parse_p_type();
    $this->_parse_p_flags_64();
    $this->_parse_p_offset();
    $this->_parse_p_vaddr();
    $this->_parse_p_paddr();
    $this->_parse_p_filesz();
    $this->_parse_p_memsz();
    $this->_parse_p_flags_32();
    $this->_parse_p_align();
  }

  protected function _parse_p_type()
  {
    $this->_p_type = $this->_reader->readUInt();
  }

  public function p_type()
  {
    return $this->_p_type();
  }

  protected function _parse_p_flags_64()
  {
  }

  protected function _parse_p_flags_32()
  {
  }

  public function p_flags()
  {
    return $this->_p_flags;
  }

  abstract protected function _parse_p_offset();

  public function p_offset()
  {
    return $this->_p_offset;
  }

  abstract protected function _parse_p_vaddr();
  
  public function p_vaddr()
  {
    return $this->_p_vaddr;
  }

  abstract protected function _parse_p_paddr();
  
  public function p_paddr()
  {
    return $this->_p_paddr;
  }

  abstract protected function _parse_p_filesz();
  
  public function p_filesz()
  {
    return $this->_p_filesz;
  }

  abstract protected function _parse_p_memsz();
  
  public function p_memsz()
  {
    return $this->_p_memsz;
  }

  abstract protected function _parse_p_align();
  
  public function p_align()
  {
    return $this->_p_align;
  }
}