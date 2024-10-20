<?php

require_once dirname(__DIR__ ).'/helpers/init.php'; 
require_once(sprintf('%s/%s', HELPERS_DIR,'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR,'Model.php')); //relavív útvonal

class Article extends Model{

    protected string $tableName='articles';

    public string $title;
    public string $textbody;

    protected array $fillables = [ 
    'title',
    'textbody',
    'link',
    'user_id'
    ];
    protected array $casts = [
        'link' => 'slugify'];

    public function create(array $data) :string |false
    {
        $data=parent::castValues($data);

        return parent::create( $data);
    }

    public function read(array $columns = ['*'], string $conditions = '')
    {
        return parent::read($columns, $conditions);
    }
    
    public function update(array $ColumnsValues, array $conditions)
    {   $ColumnsValues['updated_at'] = date ("Y-m-d H:i:s", time());

        $ColumnsValues=parent::castValues($ColumnsValues);
        

        return parent::update($ColumnsValues, $conditions);
    }

    public function delete(array $conditions)
    {
       return parent::delete($conditions);
    }
    
}

?>