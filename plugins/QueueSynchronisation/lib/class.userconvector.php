<?php

/**
 * @author Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class UserConvector {

   protected $dataConverion = array(
      'username' => 'Name',
      'email_address' => 'Email',
      'password' => 'Password',
      'algorithm' => 'HashMethod',
//       'salt' => 'Password',
   );

   protected $data = array();

   public function __construct(array $data)
   {
      $this->data = $data;
   }

   public function __call($name, $arguments)
   {
      if (substr($name, 0, 3) != 'get') {
         throw Exception("Method '{$name}' does not find");
      }
      return $this->data[substr($name, 3)];
   }

   protected function collect($destKey, $destVal)
   {
      $return = array();
      foreach ($this->dataConverion as $key => $val) {
          $method = 'get' . $$destVal;
          $return[$$destKey] = $this->$method($$destKey);
      }
      return $return;
   }

   public function symfony()
   {
      return $this->collect('key', 'val');
   }

   public function vanilla()
   {
      return $this->collect('val', 'key');
   }

   protected function getalgorithm($destKey)
   {
      return 'Django';
   }

   protected function getpassword($destKey)
   {
      return 'sha1$' . $this->data['salt'] . '$' . $this->data['password'];
   }
}
