<?php

namespace Elf;

class Elf64_Ehdr extends ElfN_Ehdr
{
  public function ei_class()
  {
    return ElfN_Ehdr::ELFCLASS64;
  }

  protected function _parse_e_entry()
  {
    $this->_e_entry = $this->_reader->readULongLong();
  }

  protected function _parse_e_phoff()
  {
    $this->_e_phoff = $this->_reader->readULongLong();
  }

  protected function _parse_e_shoff()
  {
    $this->_e_shoff = $this->_reader->readULongLong();
  }

  public function getPhdr()
  {
    $phdrs = [];

    for ($i = 0; $i < $this->e_phnum(); $i++)
      $phdrs[] = new Elf64_Phdr($this, $i, $this->_reader);
    return $phdrs;
  }
}