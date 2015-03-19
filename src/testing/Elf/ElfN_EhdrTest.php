<?php

namespace testing\Elf;

class ElfN_EhdrTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \Elf\ElfN_Ehdr::readFile
   * @expectedException \Exception
   */
  public function testExceptionIsRaisedOnInvalidFile()
  {
    \Elf\ElfN_Ehdr::readFile("NonExistantFile");
  }

  /**
   * @covers \Elf\ElfN_Ehdr::readFile
   * @expectedException \Exception
   */
  public function testExceptionIsRaisedOnInvalidFormatFile()
  {
    \Elf\ElfN_Ehdr::readFile(__FILE__);
  }

  /**
   * @covers \Elf\ElfN_Ehdr::readFile
   * @uses \Elf\Elf32_Ehdr
   */
  public function test32bitsFileIsRecognized()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');
    
    $this->assertInstanceOf(\Elf\Elf32_Ehdr::class, $element);
    return $element;
  }

  /**
   * @covers \Elf\Elf32_Ehdr::ei_class
   * @uses \Elf\ElfN_Ehdr
   */
  public function test32bitsFileIsClassifiedAs32Bits()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');
    
    $this->assertEquals($element->ei_class(), \Elf\ElfN_Ehdr::ELFCLASS32);
    return $element;
  }

  /**
   * @covers \Elf\ElfN_Ehdr::ei_data
   * @uses \Elf\Elf32_Ehdr
   */
  public function testEiDataIsRecognized()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');
    
    $this->assertEquals($element->ei_data(), \Elf\ElfN_Ehdr::ELFDATA2LSB);
    return $element;
  }

  /**
   * @covers \Elf\\Elf\ElfN_Ehdr::ei_version
   * @uses \Elf\Elf32_Ehdr
   * @uses \Elf\Readers\AReader
   * @uses \Elf\Readers\ReaderLittleEndian
   */
  public function testEIVersionIsValid()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');

    $this->assertEquals($element->ei_version(), \Elf\ElfN_Ehdr::EV_CURRENT);
    return $element;
  }

  /**
   * @covers \Elf\ElfN_Ehdr::ei_osabi
   * @uses \Elf\Elf32_Ehdr
   * @uses \Elf\Readers\AReader
   * @uses \Elf\Readers\ReaderLittleEndian
   */
  public function testEIOSABIIsValid()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');

    $this->assertEquals($element->ei_osabi(), \Elf\ElfN_Ehdr::ELFOSABI_NONE);
    return $element;
  }

  /**
   * @covers \Elf\ElfN_Ehdr::ei_abiversion
   * @uses \Elf\Elf32_Ehdr
   * @uses \Elf\Readers\AReader
   * @uses \Elf\Readers\ReaderLittleEndian
   */
  public function testEIABIVersionIsValid()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');

    $this->assertEquals($element->ei_abiversion(), 0);
    return $element;
  }

  /**
   * @covers \Elf\ElfN_Ehdr::e_type
   * @uses \Elf\Elf32_Ehdr
   * @uses \Elf\Readers\AReader
   * @uses \Elf\Readers\ReaderLittleEndian
   */
  public function testETypeIsValid()
  {
    $element = \Elf\ElfN_Ehdr::readFile(__DIR__ . '/../content/ValidBasicFile');
    
    $this->assertEquals($element->e_type(), \Elf\ElfN_Ehdr::ET_EXEC);
    return $element;
  }
}