<?php
namespace tppro;

/**
 * Autogenerated by Thrift Compiler (0.9.0)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Exception\TApplicationException;


class Document {
  static $_TSPEC;

  public $fields = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'fields',
          'type' => TType::MAP,
          'ktype' => TType::STRING,
          'vtype' => TType::STRING,
          'key' => array(
            'type' => TType::STRING,
          ),
          'val' => array(
            'type' => TType::STRING,
            ),
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['fields'])) {
        $this->fields = $vals['fields'];
      }
    }
  }

  public function getName() {
    return 'Document';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::MAP) {
            $this->fields = array();
            $_size0 = 0;
            $_ktype1 = 0;
            $_vtype2 = 0;
            $xfer += $input->readMapBegin($_ktype1, $_vtype2, $_size0);
            for ($_i4 = 0; $_i4 < $_size0; ++$_i4)
            {
              $key5 = '';
              $val6 = '';
              $xfer += $input->readString($key5);
              $xfer += $input->readString($val6);
              $this->fields[$key5] = $val6;
            }
            $xfer += $input->readMapEnd();
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('Document');
    if ($this->fields !== null) {
      if (!is_array($this->fields)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('fields', TType::MAP, 1);
      {
        $output->writeMapBegin(TType::STRING, TType::STRING, count($this->fields));
        {
          foreach ($this->fields as $kiter7 => $viter8)
          {
            $xfer += $output->writeString($kiter7);
            $xfer += $output->writeString($viter8);
          }
        }
        $output->writeMapEnd();
      }
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class Hit {
  static $_TSPEC;

  public $score = null;
  public $doc = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'score',
          'type' => TType::DOUBLE,
          ),
        2 => array(
          'var' => 'doc',
          'type' => TType::STRUCT,
          'class' => '\tppro\Document',
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['score'])) {
        $this->score = $vals['score'];
      }
      if (isset($vals['doc'])) {
        $this->doc = $vals['doc'];
      }
    }
  }

  public function getName() {
    return 'Hit';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::DOUBLE) {
            $xfer += $input->readDouble($this->score);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRUCT) {
            $this->doc = new \tppro\Document();
            $xfer += $this->doc->read($input);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('Hit');
    if ($this->score !== null) {
      $xfer += $output->writeFieldBegin('score', TType::DOUBLE, 1);
      $xfer += $output->writeDouble($this->score);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->doc !== null) {
      if (!is_object($this->doc)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('doc', TType::STRUCT, 2);
      $xfer += $this->doc->write($output);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class QueryResult {
  static $_TSPEC;

  public $totalHits = null;
  public $parsedQuery = null;
  public $hits = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'totalHits',
          'type' => TType::I32,
          ),
        2 => array(
          'var' => 'parsedQuery',
          'type' => TType::STRING,
          ),
        3 => array(
          'var' => 'hits',
          'type' => TType::LST,
          'etype' => TType::STRUCT,
          'elem' => array(
            'type' => TType::STRUCT,
            'class' => '\tppro\Hit',
            ),
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['totalHits'])) {
        $this->totalHits = $vals['totalHits'];
      }
      if (isset($vals['parsedQuery'])) {
        $this->parsedQuery = $vals['parsedQuery'];
      }
      if (isset($vals['hits'])) {
        $this->hits = $vals['hits'];
      }
    }
  }

  public function getName() {
    return 'QueryResult';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->totalHits);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 2:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->parsedQuery);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        case 3:
          if ($ftype == TType::LST) {
            $this->hits = array();
            $_size9 = 0;
            $_etype12 = 0;
            $xfer += $input->readListBegin($_etype12, $_size9);
            for ($_i13 = 0; $_i13 < $_size9; ++$_i13)
            {
              $elem14 = null;
              $elem14 = new \tppro\Hit();
              $xfer += $elem14->read($input);
              $this->hits []= $elem14;
            }
            $xfer += $input->readListEnd();
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('QueryResult');
    if ($this->totalHits !== null) {
      $xfer += $output->writeFieldBegin('totalHits', TType::I32, 1);
      $xfer += $output->writeI32($this->totalHits);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->parsedQuery !== null) {
      $xfer += $output->writeFieldBegin('parsedQuery', TType::STRING, 2);
      $xfer += $output->writeString($this->parsedQuery);
      $xfer += $output->writeFieldEnd();
    }
    if ($this->hits !== null) {
      if (!is_array($this->hits)) {
        throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
      }
      $xfer += $output->writeFieldBegin('hits', TType::LST, 3);
      {
        $output->writeListBegin(TType::STRUCT, count($this->hits));
        {
          foreach ($this->hits as $iter15)
          {
            $xfer += $iter15->write($output);
          }
        }
        $output->writeListEnd();
      }
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class ParseException extends TException {
  static $_TSPEC;

  public $msg = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'msg',
          'type' => TType::STRING,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['msg'])) {
        $this->msg = $vals['msg'];
      }
    }
  }

  public function getName() {
    return 'ParseException';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::STRING) {
            $xfer += $input->readString($this->msg);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('ParseException');
    if ($this->msg !== null) {
      $xfer += $output->writeFieldBegin('msg', TType::STRING, 1);
      $xfer += $output->writeString($this->msg);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class NonExistentPartitionException extends TException {
  static $_TSPEC;

  public $partitionId = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'partitionId',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['partitionId'])) {
        $this->partitionId = $vals['partitionId'];
      }
    }
  }

  public function getName() {
    return 'NonExistentPartitionException';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->partitionId);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('NonExistentPartitionException');
    if ($this->partitionId !== null) {
      $xfer += $output->writeFieldBegin('partitionId', TType::I32, 1);
      $xfer += $output->writeI32($this->partitionId);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}

class PartitionAlreadyExistsException extends TException {
  static $_TSPEC;

  public $partitionId = null;

  public function __construct($vals=null) {
    if (!isset(self::$_TSPEC)) {
      self::$_TSPEC = array(
        1 => array(
          'var' => 'partitionId',
          'type' => TType::I32,
          ),
        );
    }
    if (is_array($vals)) {
      if (isset($vals['partitionId'])) {
        $this->partitionId = $vals['partitionId'];
      }
    }
  }

  public function getName() {
    return 'PartitionAlreadyExistsException';
  }

  public function read($input)
  {
    $xfer = 0;
    $fname = null;
    $ftype = 0;
    $fid = 0;
    $xfer += $input->readStructBegin($fname);
    while (true)
    {
      $xfer += $input->readFieldBegin($fname, $ftype, $fid);
      if ($ftype == TType::STOP) {
        break;
      }
      switch ($fid)
      {
        case 1:
          if ($ftype == TType::I32) {
            $xfer += $input->readI32($this->partitionId);
          } else {
            $xfer += $input->skip($ftype);
          }
          break;
        default:
          $xfer += $input->skip($ftype);
          break;
      }
      $xfer += $input->readFieldEnd();
    }
    $xfer += $input->readStructEnd();
    return $xfer;
  }

  public function write($output) {
    $xfer = 0;
    $xfer += $output->writeStructBegin('PartitionAlreadyExistsException');
    if ($this->partitionId !== null) {
      $xfer += $output->writeFieldBegin('partitionId', TType::I32, 1);
      $xfer += $output->writeI32($this->partitionId);
      $xfer += $output->writeFieldEnd();
    }
    $xfer += $output->writeFieldStop();
    $xfer += $output->writeStructEnd();
    return $xfer;
  }

}


