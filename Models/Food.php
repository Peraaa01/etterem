<?php

require_once dirname(__DIR__ ).'/helpers/init.php'; 
require_once(sprintf('%s/%s', HELPERS_DIR,'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR,'Model.php')); //relavív útvonal
require_once(sprintf('%s/%s', TRAITS_DIR,'metaData.php')); //relavív útvonal

class Food extends Model{

    protected string $tableName='foods';

    public string $name;
    public int $price;
    public string $description;
    public string $type_id;
    public string $image_path;
 
    use metaData;
    protected array $fillables = [ 
    'name',
    'price',
    'description',
    'type_id',
    'image_path'
    ];
    protected $meta=[];


    public function create(array $data) :string |false
    {
        //$data=parent::castValues($data);

        return parent::create( $data);
    }

    public function read(array $columns = ['*'], string $conditions = '')
    {
        return parent::read($columns, $conditions);
    }
    
    public function update(array $ColumnsValues, array $conditions)
    { 

        //$ColumnsValues=parent::castValues($ColumnsValues);
        

        return parent::update($ColumnsValues, $conditions);
    }

    public function delete(array $conditions) :bool
    {
       return parent::delete($conditions);
    }
    
    public function getFoods()
    {
        return $this->read(['name', 'id', 'price', 'description', 'image_path', 'type_id']);
    }

    public function getImage(int|null $foodId) {
        if (empty($foodId)) {
            return '';
        }


        $result = $this->readMeta2(['*'], ['food_id' => $foodId, 'meta_key' => 'image_path'], 'foods_meta');
    
        if (empty($result) || !array_key_exists('meta_value', $result)) {
            return '';
        }

        return $result['meta_value'];
    }
}

?>