<?php

require_once dirname(__DIR__ ).'/helpers/init.php'; 
require_once(sprintf('%s/%s', HELPERS_DIR,'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR,'Model.php')); //relavív útvonal

class FoodType extends Model{

    protected string $tableName='type_of_food';

    public string $name;


    protected array $fillables = [ 
    'name'
    ];

    public function create(array $data) :string |false
    {
;

        return parent::create( $data);
    }

    public function read(array $columns = ['*'], string $conditions = '')
    {
        return parent::read($columns, $conditions);
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