<?php
class Voting {
  static protected $conn = false;
  public $affected_rows = 0;
  protected $voter = '';
  protected $nrvot = 0;
  protected $svoting = 'mysql';
  public $votitems = 'voting';
  public $votusers = 'votusers';
  protected $tdy;
  public $eror = false;

  public function __construct() {
    if(defined('NRVOT')) $this->nrvot = NRVOT;
    if(defined('SVOTING')) $this->svoting = SVOTING;
    if(defined('USRVOTE') && USRVOTE === 0) { if(defined('VOTER')) $this->voter = VOTER; }
    else $this->voter = $_SERVER['REMOTE_ADDR'];
    $this->tdy = date('j');

    if($this->svoting != 'mysql') {
      $this->votitems = '../votingtxt/'.$this->votitems.'.txt';
      $this->votusers = '../votingtxt/'.$this->votusers.'.txt';
    }
  }

  protected function setConn() {
    try {
      self::$conn = new PDO("mysql:host=".DBHOST."; dbname=".DBNAME, DBUSER, DBPASS);
      self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::$conn->exec('SET CHARACTER SET utf8');
    }
    catch(PDOException $e) {
      $this->eror = 'Unable to connect to MySQL: '. $e->getMessage();
    }
  }

  public function sqlExecute($sql) {
    if(self::$conn===false OR self::$conn===NULL) $this->setConn();
    $re = true;

    if(self::$conn !== false) {
      $ar_mode = explode(' ', trim($sql), 2);
      $mode = strtolower($ar_mode[0]);

      try {
        if($sqlprep = self::$conn->prepare($sql)) {
          if($sqlprep->execute()) {
            if($mode == 'select') {
              $re = array();
              if(($row = $sqlprep->fetch(PDO::FETCH_ASSOC)) !== false){
                do {
                  foreach($row AS $k=>$v) {
                    if(is_numeric($v)) $row[$k] = $v + 0;
                  }
                  $re[] = $row;
                }
                while($row = $sqlprep->fetch(PDO::FETCH_ASSOC));
              }
              $this->affected_rows = count($re);
            }
          }
          else $this->eror = 'Cannot execute the sql query';
        }
        else {
          $eror = self::$conn->errorInfo();
          $this->eror = 'Error: '. $eror[2];
        }
      }
      catch(PDOException $e) {
        $this->eror = $e->getMessage();
      }
    }
    if($this->eror !== false) { echo $this->eror; $re = false; }
    return $re;
  }

  public function getVoting($items, $vote = '') {
    $votstdy = $this->votstdyCo($items);
    if(!empty($vote)) {
      if($this->voter === '') return "alert('Vote Not registered.\\nYou must be logged in to can vote')";
      else {
        if($this->svoting == 'mysql') {
          $votstdy = array_unique(array_merge($votstdy, $this->votstdyDb()));
        }
        else {
          $all_votstdy = $this->votstdyTxt();
          $votstdy = array_unique(array_merge($votstdy, $all_votstdy[$this->tdy]));
        }
        if(in_array($items[0], $votstdy) || ($this->nrvot === 1 && count($votstdy) > 0)) {
          $votstdy[] = $items[0];
          setcookie("votings", implode(',', array_unique($votstdy)), strtotime('tomorrow'));
          return '{"'.$items[0].'":[0,0,3]}';
        }
        else if($this->svoting == 'mysql') $this->setVotDb($items, $vote, $votstdy);
        else $this->setVotTxt($items, $vote, $all_votstdy);

       array_push($votstdy, $items[0]);
     }
    }

    $setvoted = ($this->nrvot === 1 && count($votstdy) > 0) ? 1 : 0;
    $votitems = ($this->svoting == 'mysql') ? $this->getVotDb($items, $votstdy, $setvoted) : $this->getVotTxt($items, $votstdy, $setvoted);

    return json_encode($votitems);
  }

  protected function setVotDb($items, $vote, $votstdy) {
    $this->sqlExecute("INSERT INTO `$this->votitems` (`item`, `vote`) VALUES ('".$items[0]."', $vote) ON DUPLICATE KEY UPDATE `vote`=`vote`+$vote, `nvotes`=`nvotes`+1");

    $this->sqlExecute("DELETE FROM `$this->votusers` WHERE `day`!=$this->tdy");

    $this->sqlExecute("INSERT INTO `$this->votusers` (`day`, `voter`, `item`) VALUES ($this->tdy, '$this->voter', '".$items[0]."')");

    $votstdy[] = $items[0];
    setcookie("votings", implode(',', array_unique($votstdy)), strtotime('tomorrow'));
  }

  protected function getVotDb($items, $votstdy, $setvoted) {
    $re = array_fill_keys($items, array(0,0,$setvoted));

    function addSlhs($elm){return "'".$elm."'";}
    $resql = $this->sqlExecute("SELECT * FROM `$this->votitems` WHERE `item` IN(".implode(',', array_map('addSlhs', $items)).")");
    if($this->affected_rows > 0) {
      for($i=0; $i<$this->affected_rows; $i++) {
        $voted = in_array($resql[$i]['item'], $votstdy) ? $setvoted + 1 : $setvoted;
        $re[$resql[$i]['item']] = array($resql[$i]['vote'], $resql[$i]['nvotes'], $voted);
      }
    }

    return $re;
  }

  protected function setVotTxt($items, $vote, $all_votstdy) {
    if(file_exists($this->votitems)) {
      $rows = file($this->votitems, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $nrrows = count($rows);
      if($nrrows > 0) {
        for($i=0; $i<$nrrows; $i++) {
          $row = explode('^', $rows[$i]);
          if($row[0] == $items[0]) {
            $rows[$i] = $items[0].'^'.($row[1] + $vote).'^'.($row[2] + 1);
            $rowup = 1; break;
          }
        }
      }
    }
    if(!isset($rowup)) $rows[] = $items[0].'^'.$vote.'^1';

    file_put_contents($this->votitems, implode(PHP_EOL, $rows));

    $all_votstdy['all'][] = $this->tdy.'^'.$this->voter.'^'.$items[0];
    file_put_contents($this->votusers, implode(PHP_EOL, $all_votstdy['all']));

    $all_votstdy[$this->tdy][] = $items[0];
    setcookie("votings", implode(',', array_unique($all_votstdy[$this->tdy])), strtotime('tomorrow'));
  }

  protected function getVotTxt($items, $votstdy, $setvoted) {
    $re = array_fill_keys($items, array(0,0,$setvoted));

    if(file_exists($this->votitems)) {
      $rows = file($this->votitems, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $nrrows = count($rows);

      if($nrrows > 0) {
        for($i=0; $i<$nrrows; $i++) {
          $row = explode('^', $rows[$i]);
          $voted = in_array($row[0], $votstdy) ? $setvoted + 1 : $setvoted;
          if(in_array($row[0], $items)) $re[$row[0]] = array($row[1], $row[2], $voted);
        }
      }
    }

    return $re;
  }

  protected function votstdyCo() {
    $votstdy = array();

    if(isset($_COOKIE['votings'])) {
      $votstdy = array_filter(explode(',', $_COOKIE['votings']));
    }

    return $votstdy;
  }

  protected function votstdyDb() {
    $votstdy = array();
    $resql = $this->sqlExecute("SELECT `item` FROM `$this->votusers` WHERE `day`=$this->tdy AND `voter`='$this->voter'");
    if($this->affected_rows > 0) {
      for($i=0; $i<$this->affected_rows; $i++) {
        $votstdy[] = $resql[$i]['item'];
      }
    }

    return $votstdy;
  }

  protected function votstdyTxt() {
    $re['all'] = array();  $re[$this->tdy] = array();
    if(file_exists($this->votusers)) {
      $rows = file($this->votusers, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $nrrows = count($rows);

      if($nrrows > 0) {
        for($i=0; $i<$nrrows; $i++) {
          $row = explode('^', $rows[$i]);
          if($row[0] == $this->tdy) {
            $re['all'][] = $rows[$i];
            if($row[1] == $this->voter) $re[$this->tdy][] = $row[2];
          }
        }
      }
    }

    return $re;
  }
}