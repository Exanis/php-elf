<?php

namespace Elf;

class Elf64_Phdr
{
  protected function _parse_p_flags_64()
  {
    $this->_p_flags = $this->_reader->readUInt();
  }

  protected function _parse_p_offset()
  {
    $this->_p_offset = $this->_reader->readULongLong();
  }

  protected function _parse_p_vaddr()
  {
    $this->_p_vaddr = $this->_reader->readULongLong();
  }

  protected function _parse_p_paddr()
  {
    $this->_p_paddr = $this->_reader->readULongLong();
  }

  protected function _parse_p_filesz()
  {
    $this->_p_filesz = $this->_reader->readULongLong();
  }

  protected function _parse_p_memsz()
  {
    $this->_p_memsz = $this->_reader->readULongLong();
  }

  protected function _parse_p_align()
  {
    $this->_p_align = $this->_reader->readULongLong();
  }
}