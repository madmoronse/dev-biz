<?
/**
 *
 */
class  ModelCheckoutDelivery extends Model
{
  //payment
    const PAYMENT_100 = 'Предоплата 100%';
    const PAYMENT_ON_DELIVERY = 'Оплата при получении';
    const PAYMENT_PREPAYMENT_500 = 'Предоплата 500 рублей';
    const PAYMENT_PREPAYMENT_1000 = 'Предоплата 1000 рублей';
    const PAYMENT_PREPAYMENT_X = 'Предоплата X рублей. Оплата стоимости товара при получении.';
    const PAYMENT_PREPAYMENT_X150 = 'Предоплата X150 рублей. Оплата оставшейся суммы при получении товара.';
    const PAYMENT_PREPAYMENT_X250 = 'Предоплата X250 рублей. Оплата оставшейся суммы при получении товара.';
    const PAYMENT_PREPAYMENT_X390 = 'Предоплата X390 рублей. Оплата оставшейся суммы при получении товара.';
    const PAYMENT_PREPAYMENT_X290 = 'Предоплата X290 рублей. Оплата оставшейся суммы при получении товара.';

    //delivery service
    const DELIVERY_RM = 'Почта России';
    const DELIVERY_SDEK = 'СДЭК';
    const DELIVERY_SDEK2 = 'СДЭК';
    const DELIVERY_KRASNOYARSK = 'доставка по Красноярску';
    const DELIVERY_KRASNOYARSK2 = 'Самовывоз';


    //place of receipt
    const PLACE_MAIL = 'Почта России';
    const PLACE_SDEK = 'СДЭК (пункт выдачи)';
    const PLACE_SDEK2 = 'СДЭК (до двери)';
    const PLACE_KRASNOYARSK = 'доставка по Красноярску';
    const PLACE_KRASNOYARSK2 = 'пр. Красноярский Рабочий 199 оф 25';
    const PLACE_KRASNOYARSK3 = 'ул. Карамзина 25, второй этаж';
    const PLACE_KRASNOYARSK4 = 'ул. Копылова 42';

    //delivery cost
    const DCOST_BYDOC = 'по документу';
    const DCOST_NOT_AVAL = 'Недоступно';
    const DCOST_FREE = 'Бесплатно';
    const DCOST_DOC150 = 'по документу + 150р';
    const DCOST_DOC150L = 'по документу - 150р';

    const DCOST_DOC250 = 'по документу + 250р';
    const DCOST_DOC350 = 'доставка до 350р';
    const DCOST_DOC290 = 'по документу + 290р';
    const DCOST_DOC390 = 'по документу + 390р';
    const DCOST_DOC140 = 'по документу + 140р';

    const BAD_AVAIL = 'Труднодоступный';




    private $cost;
  private $way = false;
  /*
  'payment' - Оплата
  'delivery' - Служба доставки
  'place' - Место получения
  'dcost' - Стоимость доставки
  'available' - доступность 0 - true, 1 - false
  */
  private $schemes = array(
    0 => array(
      0 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),
      1 => array(
        'payment' => self::PAYMENT_PREPAYMENT_500,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),
      2 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_SDEK,
        'place' => self::PLACE_SDEK,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),

      3 => array(
        'payment' => self::PAYMENT_ON_DELIVERY,
        'delivery' => self::DELIVERY_SDEK,
        'place' => self::PLACE_SDEK,
        'dcost' => self::DCOST_NOT_AVAL,
        'available'  => 0
      ),
      4 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 1
      )
    ),


    1 => array(
      0 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_FREE,
        'available'  => 0
      ),
      1 => array(
        'payment' => self::PAYMENT_PREPAYMENT_X, // PAYMENT_PREPAYMENT_1000
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 1
      ),
      2 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_DOC150L,
        'available'  => 1
      ),

      3 => array(
        'payment' => self::PAYMENT_PREPAYMENT_X,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),
      4 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_SDEK,
        'place' => self::PLACE_SDEK,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),
      5 => array(
        'payment' => self::PAYMENT_PREPAYMENT_X150, // PAYMENT_PREPAYMENT_500
        'delivery' => self::DELIVERY_SDEK,
        'place' => self::PLACE_SDEK,
        'dcost' => self::DCOST_DOC150,
        'available'  => 0
      )




    ),
    2 => array(
      0 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_FREE,
        'available'  => 0
      ),
      1 => array(
        'payment' => self::PAYMENT_PREPAYMENT_X, // PAYMENT_PREPAYMENT_1000
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 1
      ),
      2 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 1
      ),
      3 => array(
        'payment' => self::PAYMENT_PREPAYMENT_1000,
        'delivery' => self::DELIVERY_RM,
        'place' => self::PLACE_MAIL,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),
      4 => array(
        'payment' => self::PAYMENT_100,
        'delivery' => self::DELIVERY_SDEK,
        'place' => self::PLACE_SDEK,
        'dcost' => self::DCOST_BYDOC,
        'available'  => 0
      ),
      5 => array(
        'payment' => self::PAYMENT_PREPAYMENT_X250, // PAYMENT_PREPAYMENT_1000
        'delivery' => self::DELIVERY_SDEK,
        'place' => self::PLACE_SDEK,
        'dcost' => self::DCOST_DOC250,
        'available'  => 0
      )

    ),
      3 => array(
          0 => array(
              'payment' => self::PAYMENT_ON_DELIVERY,
              'delivery' => self::DELIVERY_KRASNOYARSK,
              'place' => self::PLACE_KRASNOYARSK,
              'dcost' => self::DCOST_DOC350,
              'available'  => 0
          ),
          1 => array(
              'payment' => self::PAYMENT_ON_DELIVERY,
              'delivery' => self::DELIVERY_KRASNOYARSK2,
              'place' => self::PLACE_KRASNOYARSK2,
              'dcost' => self::DCOST_FREE,
              'available'  => 0
          ),
          2 => array(
              'payment' => self::PAYMENT_ON_DELIVERY,
              'delivery' => self::DELIVERY_KRASNOYARSK2,
              'place' => self::PLACE_KRASNOYARSK3,
              'dcost' => self::DCOST_FREE,
              'available'  => 0
          ),

          3 => array(
              'payment' => self::PAYMENT_ON_DELIVERY,
              'delivery' => self::DELIVERY_KRASNOYARSK2,
              'place' => self::PLACE_KRASNOYARSK4,
              'dcost' => self::DCOST_FREE,
              'available'  => 0
          )
      )
  );
  private $schema = array();
  private $city;
  private $city_uid;
  private $result = array();

  public function getDelivery($city, $region, $cost, $id){
    $this->cost = $cost;
    $this->chooseDeliveryWay($city, $region);
    $this->deliverySchema();
    $this->setCity($city, $region, $id);
    $this->deliveryArray();
    if($this->city === false){
      $this->result = array();
    }
    return $this->result;
  }

  public function getShippingMethod($city, $region, $cost, $id, $delivery_city_id){
    $this->getDelivery($city, $region, $cost, $delivery_city_id);
    $result = $this->result[$id]['delivery'].".".$this->result[$id]['payment'].".".$this->result[$id]['place'];
    return $result;
  }

  public function getShippingCode($city, $region, $cost, $id, $delivery_city_id){
    $this->getDelivery($city, $region, $cost, $delivery_city_id);
    if(($this->result[$id]['delivery'] == self::DELIVERY_RM)&&($this->result[$id]['payment'] == self::PAYMENT_100)){
      return 'ruspostfull.ruspostfull';
    }elseif(($this->result[$id]['delivery'] == self::DELIVERY_RM)&&($this->result[$id]['payment'] != self::PAYMENT_100)){
      return 'ruspostpart.ruspostpart';
    }elseif((($this->result[$id]['delivery'] == self::DELIVERY_SDEK) or ($this->result[$id]['delivery'] == self::DELIVERY_SDEK2))&&($this->result[$id]['payment'] == self::PAYMENT_100)){
      return 'cdekfull.cdekfull';
    }elseif((($this->result[$id]['delivery'] == self::DELIVERY_SDEK) or ($this->result[$id]['delivery'] == self::DELIVERY_SDEK2))&&($this->result[$id]['payment'] != self::PAYMENT_100)){
      return 'cdekpart.cdekpart';
    }
    else{
      return false;
    }
    //$result = .".".$this->result[$id]['payment'].".".$this->result[$id]['place'];
    //return $result;
  }

  public function getPaymentMethod($city, $region, $cost, $id, $delivery_city_id){
    $this->getDelivery($city, $region, $cost, $delivery_city_id);
    if(1){
      return 'Стандартная оплата';
    }elseif($this->result[$id]['delivery'] == self::DELIVERY_SDEK or $this->result[$id]['delivery'] == self::DELIVERY_SDEK2){
      return 'cod_cdek.cod_cdek';
    }
    else{
      return false;
    }
  }

  public function getPaymentCode($city, $region, $cost, $id, $delivery_city_id){
    //$this->getDelivery($city, $region, $cost, $delivery_city_id);
    //$this->result[$id]['delivery'] == self::DELIVERY_RM
    if(1){
      return 'cod';
    } // elseif($this->result[$id]['delivery'] == self::DELIVERY_SDEK){
    //   return 'cod_cdek.cod_cdek';
    // }
    else{
      return false;
    }
  }

  public function getPrepayment($city, $region, $cost, $id, $total, $delivery_city_id){
    $this->getDelivery($city, $region, $cost, $delivery_city_id);
    $schema = $this->result[$id];
    if($this->result[$id]['payment'] == self::PAYMENT_100){
      $result = $total;
    }elseif($this->result[$id]['payment'] == self::PAYMENT_ON_DELIVERY){
      $result = 0;
    }elseif($this->result[$id]['payment'] == self::PAYMENT_PREPAYMENT_500){
      $result = 500;
    }elseif($this->result[$id]['payment'] == self::PAYMENT_PREPAYMENT_1000){
      $result = 1000;
    }elseif($this->result[$id]['payment'] == str_replace('X',$this->result[$id]['dcost'], self::PAYMENT_PREPAYMENT_X)){
      $result = $this->result[$id]['dcost'];
    }elseif($this->result[$id]['payment'] == str_replace('X150',$this->result[$id]['dcost'] + 150, self::PAYMENT_PREPAYMENT_X150)){
      $result = $this->result[$id]['dcost']+150;
    }elseif($this->result[$id]['payment'] == str_replace('X390',$this->result[$id]['dcost'] + 390, self::PAYMENT_PREPAYMENT_X390)){
      $result = $this->result[$id]['dcost']+390;
    }elseif($this->result[$id]['payment'] == str_replace('X290',$this->result[$id]['dcost'] + 290, self::PAYMENT_PREPAYMENT_X290)){
      $result = $this->result[$id]['dcost']+290;
    }elseif($this->result[$id]['payment'] == str_replace('X250',$this->result[$id]['dcost'] + 250, self::PAYMENT_PREPAYMENT_X250)){
      $result = $this->result[$id]['dcost']+250;
    }
    return $result;
  }

  public function getTotal($city, $region, $cost, $id, $total){
    $this->getDelivery($city, $region, $cost);
    $result = $total+$this->result[$id]['dcost'];
    return $result;
  }

  public function getShipping($city, $region, $cost, $id){
    $this->getDelivery($city, $region, $cost);
    $result = $this->result[$id]['dcost'];
    return $result;
  }

  private function chooseDeliveryWay($city, $region){
    if($city == 'г. Красноярск' && $region == 'Красноярский Край'){
        $this->way = 3;
    } else{
      if($this->cost < 3000){
        $this->way = 0;
      }elseif(($this->cost >= 3000)&&($this->cost < 6000)){
        $this->way = 1;
      }elseif($this->cost >= 6000){
        $this->way = 2;
      }else{
        $this->way = false;
      }
    }
  }

  private function deliverySchema(){
    if ($this->way !== false) {
      $this->schema = $this->schemes[$this->way];
    }
  }

  private function deliveryArray(){
    if(!empty($this->schema)){
      foreach ($this->schema as $key => $schema) {
        foreach ($schema as $skey => $sval) {
          $fun = 'choose'.ucfirst($skey);
          $result = $this->$fun($schema, $skey, $key);
          if($result == 'del'){
            unset($this->result[$key]);
            break;
          }else{
            $this->result[$key][$skey] = $result;
          }
        }
        if($this->result[$key]){
          if(is_int($this->result[$key]['dcost'])){
            $this->result[$key]['fullcost'] = $this->result[$key]['dcost']+$this->cost;
          }else{
            $this->result[$key]['fullcost'] = $this->cost;
          }
        }
      }
    }
    sort($this->result);
  }

  private function choosePayment($schema, $field, $key){
    switch ($schema[$field]) {
      case self::PAYMENT_100:{
        return self::PAYMENT_100;
        break;
      }
      case self::PAYMENT_ON_DELIVERY:{
        return self::PAYMENT_ON_DELIVERY;
        break;
      }
      case self::PAYMENT_PREPAYMENT_500:{
        return self::PAYMENT_PREPAYMENT_500;
        break;
      }
      case self::PAYMENT_PREPAYMENT_1000:{
        return self::PAYMENT_PREPAYMENT_1000;
        break;
      }
      case self::PAYMENT_PREPAYMENT_X:{
        return str_replace('X',$this->chooseDcost($schema, 'dcost', $key),self::PAYMENT_PREPAYMENT_X);
        break;
      }
      case self::PAYMENT_PREPAYMENT_X150:{
        return str_replace('X150',$this->chooseDcost($schema, 'dcost', $key)+150,self::PAYMENT_PREPAYMENT_X150);
        break;
      }
      case self::PAYMENT_PREPAYMENT_X250:{
        return str_replace('X250',$this->chooseDcost($schema, 'dcost', $key)+250,self::PAYMENT_PREPAYMENT_X250);
        break;
      }
      case self::PAYMENT_PREPAYMENT_X390:{
        return str_replace('X390',$this->chooseDcost($schema, 'dcost', $key)+390,self::PAYMENT_PREPAYMENT_X390);
        break;
      }
      case self::PAYMENT_PREPAYMENT_X290:{
        return str_replace('X290',$this->chooseDcost($schema, 'dcost', $key)+290,self::PAYMENT_PREPAYMENT_X290);
        break;
      }
      default:
        return false;
        break;
    }
  }

  private function chooseAvailable($schema, $field, $key)
  {
    if($this->city['availability'] == self::BAD_AVAIL){
      if($schema['available'] == 0){
        return 'del';
      }
    }else{
      if($schema['available'] == 1){
        return 'del';
      }
    }
    return;
  }

  private function chooseDelivery($schema, $field, $key){
    if($this->city['availability'] == self::BAD_AVAIL){
      if($schema['available'] == 0){
        return 'del';
      }
    }else{
      if($schema['available'] == 1){
        return 'del';
      }
    }
    switch ($schema[$field]) {
      case self::DELIVERY_RM:{
        return self::DELIVERY_RM;
        break;
      }
      case self::DELIVERY_KRASNOYARSK:{
        return self::DELIVERY_KRASNOYARSK;
        break;
      }
      case self::DELIVERY_KRASNOYARSK2:{
        return self::DELIVERY_KRASNOYARSK2;
        break;
      }
      case self::DELIVERY_SDEK:{
        if($this->city['sdek_sklad'] == 0){
          return 'del';
        }
        return self::DELIVERY_SDEK;
        break;
      }
      case self::DELIVERY_SDEK2:{
        if($this->city['sdek_sklad'] == 0){
          return 'del';
        }
        return self::DELIVERY_SDEK2;
        break;
      }
      default:
        return false;
        break;
    }
  }

  private function choosePlace($schema, $field){
    switch ($schema[$field]) {
      case self::PLACE_MAIL:{
        return self::PLACE_MAIL;
        break;
      }
      case self::PLACE_SDEK:{
        return self::PLACE_SDEK;
        break;
      }
      case self::PLACE_SDEK2:{
        return self::PLACE_SDEK2;
        break;
      }
      case self::PLACE_KRASNOYARSK:{
        return self::PLACE_KRASNOYARSK;
        break;
      }
      case self::PLACE_KRASNOYARSK2:{
        return self::PLACE_KRASNOYARSK2;
        break;
      }
      case self::PLACE_KRASNOYARSK3:{
        return self::PLACE_KRASNOYARSK3;
        break;
      }
      case self::PLACE_KRASNOYARSK4:{
        return self::PLACE_KRASNOYARSK4;
        break;
      }
      default:
        return false;
        break;
    }
  }

  private function chooseDcost($schema, $field, $key){
    switch ($schema[$field]) {
      case self::DCOST_BYDOC:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery'];
        }elseif($schema['delivery'] == self::DELIVERY_SDEK){
          return (int)$this->city['sdek_sklad'];
        }elseif($schema['delivery'] == self::DELIVERY_SDEK2){
          return (int)$this->city['sdek_sklad'];
        }
        break;
      }
      case self::DCOST_NOT_AVAL:{
        return 'del';
        break;
      }
      case self::DCOST_FREE:{
        if($this->city['availability'] == self::BAD_AVAIL){
          if($schema['delivery'] == self::DELIVERY_RM){
            return (int)$this->city['post_delivery'];
          }elseif($schema['delivery'] == self::DELIVERY_SDEK){
            return (int)$this->city['sdek_sklad'];
          }elseif($schema['delivery'] == self::DELIVERY_SDEK2){
            return (int)$this->city['sdek_sklad'];
          }
        }
        return self::DCOST_FREE;
        break;
      }
      case self::DCOST_DOC150:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery']+150;
        }elseif($schema['delivery'] == self::DELIVERY_SDEK){
          return (int)$this->city['sdek_sklad']+150;
        }
        break;
      }
      case self::DCOST_DOC150L:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery']-150;
        }elseif($schema['delivery'] == self::DELIVERY_SDEK){
          return (int)$this->city['sdek_sklad']-150;
        }
        break;
      }
      case self::DCOST_DOC250:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery']+250;
        }elseif($schema['delivery'] == self::DELIVERY_SDEK){
          return (int)$this->city['sdek_sklad']+250;
        }
        break;
      }

      case self::DCOST_DOC350:{
        if($schema['delivery'] == self::PLACE_KRASNOYARSK){
          return (int)350;
        }
        break;
      }


      case self::DCOST_DOC140:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery']+140;
        }elseif($schema['delivery'] == self::DELIVERY_SDEK2){
          return (int)$this->city['sdek_sklad']+140;
        }
        break;
      }

      case self::DCOST_DOC390:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery']+390;
        }elseif($schema['delivery'] == self::DELIVERY_SDEK2){
          return (int)$this->city['sdek_sklad']+390;
        }
        break;
      }

      case self::DCOST_DOC290:{
        if($schema['delivery'] == self::DELIVERY_RM){
          return (int)$this->city['post_delivery']+290;
        }elseif($schema['delivery'] == self::DELIVERY_SDEK2){
          return (int)$this->city['sdek_sklad']+290;
        }
        break;
      }

      default:
        return false;
        break;
    }
  }

  private function setCity($city, $region, $id){
    //Reuse of same data
    if ($this->city_uid == md5($city . $region . $id) && $this->city) {
        return;
    }
    $this->city_uid = md5($city . $region . $id);
    // Если задан ID
    if ($id != 0) {
        $city = $this->db->query("SELECT * FROM `delivery_cities` WHERE `id` = " . (int) $id);
        if ($city->num_rows > 0) {
            $this->city = $city->row;
        } else {
            $this->city = false;
        }
        return;
    } else if (empty($city) && empty($region)) {
        $this->city = false;
        return;
    }
    $rq = $this->db->query("SELECT * FROM `delivery_regions` WHERE `name` = '{$region}' LIMIT 1");
    $region_data = $rq->rows;
    if(count($region_data) == 1){
        $region_id = $region_data[0]['id'];
        $query = $this->db->query("SELECT * FROM `delivery_cities` WHERE `name` LIKE '%{$city}%' AND `region_id` = '{$region_id}' LIMIT 1");
        $city_data = $query->rows;
        if (count($city_data) == 1) {
            foreach ($city_data as $key => $city_item) {
                $this->city = $city_item;
            }
        } else{
            $this->city = false;
        }
    } else {
        $this->city = false;
    }

    return;
  }

    public function getDeliveryCityId($shipping_address) {
        
        //Пробуем найти адрес по полному названию населенного пункта
        $querycity = $this->db->query("SELECT `id` FROM `delivery_cities`
                                       WHERE `np_name` = '{$shipping_address['city']}' LIMIT 1");
        if ($querycity->num_rows > 0) {
            return $querycity->row['id'];
        }

        if (!empty($shipping_address['zone_id'])) {
            $queryregion = $this->db->query("SELECT `id` FROM `delivery_regions` WHERE `zone_id` = " . (int) $shipping_address['zone_id']);
        }
        if ($queryregion->num_rows > 0) {
            $query = "SELECT `id` FROM `delivery_cities` WHERE `name` LIKE '%{$shipping_address['city']}%' AND `region_id` = {$queryregion->row['id']} LIMIT 1"; 
        } else {
            $query = "SELECT `id` FROM `delivery_cities` WHERE `name` LIKE '%{$shipping_address['city']}%' LIMIT 1"; 
        }

        unset($querycity);

        $querycity = $this->db->query($query);
        if ($querycity->num_rows > 0) {
            return $querycity->row['id'];
        }
        return 0;
        
    }
}
?>
