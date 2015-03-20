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
}