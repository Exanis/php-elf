<?php

namespace Elf;

class Elf32_Ehdr extends ElfN_Ehdr
{
  public function ei_class()
  {
    return ElfN_Ehdr::ELFCLASS32;
  }

  protected function _parse_e_entry()
  {
    $this->_e_entry = $this->_reader->readUInt();
  }

  protected function _parse_e_phoff()
  {
    $this->_e_phoff = $this->_reader->readUInt();
  }

  protected function _parse_e_shoff()
  {
    $this->_e_shoff = $this->_reader->readUInt();
  }

  public function getPhdr()
  {
    $phdrs = [];

    for ($i = 0; $i < $this->e_phnum(); $i++)
      $phdrs[] = new Elf32_Phdr($this, $i, $this->_reader);
    return $phdrs;
  }
}