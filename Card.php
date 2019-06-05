<?php
class cardSuit {
    //const POINTS = array('2', 'Q', '3', '8', '5', '9', '4', 'J', '6', '10', '7', 'K', 'A');
    const POINTS = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
    const SUITS = array('S', 'H', 'D', 'C'); //S黑桃H愛心D菱形C黑咪 
}
class Card {
    private $_suit;
    private $_point; 

    public function __construct($suit, $point) {
        $this->_point = $point;
        $this->_suit = $suit;
    }

    public function getInfo() {
        return $this->_suit.$this->_point;
    }

    public function getPoint() {
        return $this->_point;
    }

    public function getSuit() {
        return $this->_suit;
    }
}

class Deck {
    private $_cards;

    public function __construct() {
        $pointArray = cardSuit::POINTS;
        $suitArray = cardSuit::SUITS;

        foreach ($suitArray as $suit) {
            foreach ($pointArray as $point) {
                $this->_cards[] = new Card($suit, $point);
            }
        }
    }

    public function shuffle() {//洗牌
        shuffle($this->_cards);
    }

    public function cut() {//slice和splice差別
        $randNum = array_rand($this->_cards, 1);
        $frontArray = array_slice($this->_cards, $randNum);
        $backArray = array_slice($this->_cards, 0, $randNum);
        $cutResult = array_merge($frontArray, $backArray);
        $this->_cards = $cutResult;
        //print_r($cutResult);
        return $this->_cards;
    }

    public function getDeck() {
        return $this->_cards;
    }

    public function getCardByPosition($key) {
        if ($key >= 52) {
            throw new Exception("超過撲克牌數量\n");
        }
        return $this->_cards[$key];
    }
}

class Games {
    public $players;
    public $deck;
    private $_result;
    private $_suitCount = ["S" => 0, "H" => 0, "D" => 0, "C" => 0];
    private $_pointCount = [ "2" => 0, "3" => 0, "4" => 0, "5" => 0, "6" => 0, "7" => 0, "8" => 0, 
    "9" => 0, "10" => 0, "J" => 0, "Q" => 0, "K" => 0, "A" => 0];
    private $handCardNum = 13;
    private $_handCard;
    private $_suitCountPoint;

    public function __construct() {
        $this->deck = new Deck();
        $this->players[0] = new Player("Amy");
        $this->players[1] = new Player("Tony");
        $this->players[2] = new Player("Tom");
        $this->players[3] = new Player("Nick");
        // $this->players[4] = new Player("A");
        // $this->players[5] = new Player("B");
        // $this->players[6] = new Player("C");
        // $this->players[7] = new Player("D");
        // $this->players[8] = new Player("E");
        // $this->players[9] = new Player("F");
    }

    public function deal() {
        $count = count($this->players);

        try {
            for ($key = 0; $key < $count * $this->handCardNum; $key++) {
                $card = $this->deck->getCardByPosition($key);
                $this->players[$key % $count]->setHand($card);
            }
        } catch(Exception $ex) {
            echo 'Message: '.$ex;
            exit;
        }    
    }

    public function showPlayerInfo() {
        //print_r($this->players);//array型態要轉換成object才能用函示
        foreach($this->players as $player) {
            print_r($player->getName().": \n").$this->judgeCard($player);
            echo "\n";
        }
    }
   
    public function judgeCard($player) {//判斷牌型
        $this->_suitCount = ["S" => 0, "H" => 0, "D" => 0, "C" => 0];
        $this->_pointCount = ["2" => 0, "3" => 0, "4" => 0, "5" => 0, "6" => 0, "7" => 0, "8" => 0, 
        "9" => 0, "10" => 0, "J" => 0, "Q" => 0, "K" => 0, "A" => 0];
        $this->_suitCountPoint = null;
        foreach($player->getHand() as $card) {
            print_r($card->getSuit());
            print_r($card->getPoint());
            echo " ";
            $this->_suitCount[$card->getSuit()] += 1;
            $this->_pointCount[$card->getPoint()] += 1;
            $this->_suitCountPoint[$card->getSuit()][] = $card->getPoint(); 
        }
            foreach($this->_suitCountPoint as $value2) {    
                if(count($value2) >= 5) {
                    $suitNum[] = $value2;
                }
            }
            foreach($suitNum as $key => $value4) {
            $newPointCount = ["2" => 0, "3" => 0, "4" => 0, "5" => 0, "6" => 0, "7" => 0, "8" => 0, 
            "9" => 0, "10" => 0, "J" => 0, "Q" => 0, "K" => 0, "A" => 0];
                foreach($value4 as $value5) {
                    $newPointCount[$value5]+=1 ;
                }
            }
            foreach($newPointCount as $key => $value) {
                if($newPointCount[$key] == 1 && $newPointCount[$key + 1] == 1 && $newPointCount[$key + 2] == 1 && $newPointCount[$key + 3] == 1  && $newPointCount[$key + 4] == 1) {
                    print_r($suitNum);
                    print_r("同花順\n");
                    return true;
                }
                if($newPointCount['A'] == 1 && $newPointCount['2'] == 1 && $newPointCount['3'] == 1 && $newPointCount['4'] == 1  && $newPointCount['5'] == 1 ||
                $newPointCount['7'] == 1 && $newPointCount['8'] == 1 && $newPointCount['9'] == 1 && $newPointCount['10'] == 1  && $newPointCount['J'] == 1 ||
                $newPointCount['8'] == 1 && $newPointCount['9'] == 1 && $newPointCount['10'] == 1 && $newPointCount['J'] == 1  && $newPointCount['Q'] == 1 ||
                $newPointCount['9'] == 1 && $newPointCount['10'] == 1 && $newPointCount['J'] == 1 && $newPointCount['Q'] == 1  && $newPointCount['K'] == 1 ||
                $newPointCount['10'] == 1 && $newPointCount['J'] == 1 && $newPointCount['Q'] == 1 && $newPointCount['K'] == 1  && $newPointCount['A'] == 1){
                    print_r($suitNum);
                    print_r("同花順\n");
                    return true;
                }
            }      
            
        foreach($this->_pointCount as $value) {
            if($value == 4) {
                $this->_result = "鐵支";
                print_r($this->_result."\n");
                return ture;
            }
        }
        foreach($this->_pointCount as $value) {
            if($value == 3 ) {
                $tree = true;
            }
            if($value == 2 ) {
                $two = true;
            }
            if($tree && $two) {
                $this->_result = "葫蘆";
                print_r($this->_result."\n");
                return ture;
            }
        }
        foreach($this->_suitCount as $value) {
            if($value >= 5) {
                $this->_result = "同花";
                print_r($this->_result."\n");
                return ture;
            }
        }   
        foreach($this->_pointCount as $key => $value) {
            if($this->_pointCount[$key] == 1 && $this->_pointCount[$key + 1] == 1 && $this->_pointCount[$key + 2] == 1 && $this->_pointCount[$key + 3] == 1  && $this->_pointCount[$key + 4] == 1) {
                print_r("順子\n");
                return true;
            }
            if($this->_pointCount['A'] == 1 && $this->_pointCount['2'] == 1 && $this->_pointCount['3'] == 1 && $this->_pointCount['4'] == 1  && $this->_pointCount['5'] == 1 ||
            $this->_pointCount['7'] == 1 && $this->_pointCount['8'] == 1 && $this->_pointCount['9'] == 1 && $this->_pointCount['10'] == 1  && $this->_pointCount['J'] == 1 ||
            $this->_pointCount['8'] == 1 && $this->_pointCount['9'] == 1 && $this->_pointCount['10'] == 1 && $this->_pointCount['J'] == 1  && $this->_pointCount['Q'] == 1 ||
            $this->_pointCount['9'] == 1 && $this->_pointCount['10'] == 1 && $this->_pointCount['J'] == 1 && $this->_pointCount['Q'] == 1  && $this->_pointCount['K'] == 1 ||
            $this->_pointCount['10'] == 1 && $this->_pointCount['J'] == 1 && $this->_pointCount['Q'] == 1 && $this->_pointCount['K'] == 1  && $this->_pointCount['A'] == 1){
                print_r("順子\n");
                return true;
            }
        }
        foreach($this->_pointCount as $value) {
            if($value == 3) {
                $this->_result = "三條";
                print_r($this->_result."\n");
                return ture;
            }
        }
        foreach($this->_pointCount as $value) {
            if($value == 2) {
                $count++;
                if($count == 2) {
                    $this->_result = "二對";
                    print_r($this->_result."\n");
                    return ture;
                }
            }
        }
        foreach($this->_pointCount as $value) {
            if($value == 2) {
                $this->_result = "一對";
                print_r($this->_result."\n");
                return ture;
            }
        }
        foreach($this->_pointCount as $value) {
            if($value < 2) {
                $this->_result = "散牌";
                print_r($this->_result."\n");
                return ture;
            }
        }
    }
}

class Player {
    private $name;
    private $hand;

    public function __construct($name) {
        $this->name = $name;
        $this->hand = [];
    }

    public function getName() {    
        return $this->name;
    }

    public function setHand($hand) {
        //$this->hand = $hand;
        array_push($this->hand, $hand);
    }

    public function getValue() {
        foreach($this->hand as $value) {           
            print_r($value->getInfo()." ");
        }
    }
    
    public function getHand() {
        return $this->hand ;
    }
}

    $game = new Games();
    $game->deck->shuffle();
    $game->deck->cut();
    $game->deal(); 
    $game->showPlayerInfo(); 

