<?php

require_once dirname(__DIR__ ).'/helpers/init.php'; 
require_once(sprintf('%s/%s', HELPERS_DIR,'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR,'Model.php')); //relavív útvonal
require_once(sprintf('%s/%s', TRAITS_DIR,'metaData.php')); //relavív útvonal

class User extends Model{

    use metaData;
    protected string $tableName='users';

    protected array $fillables = [ 
    'email', 
    'password',
    'role'
    ];

    protected $meta=  [
        'phone_number',
        'lastname', 
        'firstname',
        'street', 
        'number'
    ];
    protected array $casts = [
    'password' => 'encrypt'];


 
    public function create(array $data) :string |false
    {
        $data=parent::castValues($data);
        $userData = requestFilter($this->fillables, $data);
         
        return parent::create( $userData);
    }

    public function read(array $columns = ['*'], string $conditions = '')
    {
        return parent::read($columns, $conditions);
    }
    /* public function delete(int $id) :bool
    {
        $dm= new DatabaseManager();
        return $dm->delete($this->tableName, [$id]);
    } */
    

    public function update(array $data, array $conditions): string | false {
        $data = parent::castValues($data);
        return parent::update($data, $conditions);
    }

    public function getByEmail(string $email, array $columns = ['id']) 
    {
        return $this->read($columns, "email='$email'");
       
    }

    public function isAdministrator(int $id) :bool
    {
        $result=$this->read(['*'], "id= $id");
        $user= reset($result);
        if ($user['role'] =='admin') {
               return true;
        }
        else return false;
    }

    
    public function logout() {
        unset($_SESSION["logged_in"]);
        session_unset();
        header('Location: /');
    }

    public function getProfileImage(int|null $userId) {
        if (empty($userId)) {
            return '';
        }


        $result = $this->readMeta2(['*'], ['user_id' => $userId, 'meta_key' => 'profile_img'], 'users_meta');
    
        if (empty($result) || !array_key_exists('meta_value', $result)) {
            return '';
        }

        return $result['meta_value'];
    }

    public function filterFillables(array $data) {
        foreach($data as $key => $value) {
            if (!in_array($key, $this->fillables)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

        
}

?>