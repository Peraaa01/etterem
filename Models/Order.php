<?php

require_once dirname(__DIR__ ).'/helpers/init.php'; 
require_once(sprintf('%s/%s', HELPERS_DIR,'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR,'Model.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR,'User.php')); //relavív útvonal
class Order extends Model{

    protected string $tableName='orders';
    protected int $delivery_home_price = 200;

   

    protected array $fillables = [ 
    'food_id',
    'doses',
    'delivery_home',
    'delivery_time',
    'user_id', 
    'total_amount'
    ];

    public function getDeliveryHomePrice()
    {
        return $this->delivery_home_price;
    }

    public function create(array $data) :string |false
    {

        return parent::create( $data);
    }

    public function read(array $columns = ['*'], string $conditions = '')
    {
        return parent::read($columns, $conditions);
    }
    
    public function read_two_table( array $columns = ['*'], string $conditions = '', string $order = '', string $otherTableName, array $onColumns)
    {
        return parent::read_two_table($columns, $conditions, $order, $otherTableName, $onColumns);
    }


    public function update(array $ColumnsValues, array $conditions)
    { 

        return parent::update($ColumnsValues, $conditions);
    }

    public function delete(array $conditions) :bool
    {
       return parent::delete($conditions);
    }
    
}

?>