
<?php
require_once 'init.php';
require_once('helpers.php'); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'User.php')); //relavív útvonal
require_once('FormController.php'); //relavív útvonal

class UserFormController extends FormController
{

    private static bool $initialized = false;

    private static function initialize() {
        if (!self::$initialized) {
            //parent::setForm("user");
            self::$defName = "user";
            self::$initialized = true;
        }
    }

    public static function  getDefinition()
    {
        self::initialize();
        return parent::getDefinition();
    }

    public static function saveFormData(array $formData, array| null $definitions)
    {
        self::initialize();
        if (empty($formData)) {
            return [];
        }
        foreach ($formData as &$fieldData) {
            $fieldData = self::sanitizeData($fieldData);
        }
        self::$formData = $formData;
        if (!empty($errors = self::validateUserFormData(self::$formData, $definitions))) {
            return $errors;
        }

        //jelszó ismétlést kivesszük

        unset($formData['password_repeated']);

        //validálás után jelszótitkosítás: a user osztályban van a jelszótitkosítás


        $user = new User();
        $userId = $user->create($formData);


        $metaData = requestFilter([
            'phone_number',
            'lastname',
            'firstname',
            'street',
            'number'
        ], $formData);
        $user->createMeta('users', $metaData, $userId);

        redirect('/login');
    }

    private static function validateUserFormData(array $formData, array | null $definitions)
    {
        $errors = []; // asszociatv tömb lesz a hibaüzenetek tárolására
        self::initialize();
        try {
            if ($definitions === null) {
                throw new Exception('Nem lehetett a JSON fájlt dekódolni.');
            }
            if (!key_exists('user_conditions', $formData)) {
                $formData['user_conditions'] = false;
            }
            foreach ($formData as $key => $value) {

                foreach ($definitions as $definition) {

                    if ($definition['key'] === $key && array_key_exists('rules', $definition)) {

                        foreach ($definition['rules'] as $rule) {

                            $isError = false;
                            switch ($rule['type']) {
                                case 'pattern':
                                    if (! preg_match($rule['condition'], $value)) {
                                        $isError = true;
                                    }
                                    break;
                                case 'required_checked':
                                    if (! $value) {
                                        $isError = true;
                                    }
                                    break;
                                case 'min':
                                    if (strlen($value) < $rule['condition']) {
                                        $isError = true;
                                    }
                                    break;
                                case 'max':
                                    if (strlen($value) > $rule['condition']) {
                                        $isError = true;
                                    }
                                    break;
                                case 'compare_equal':
                                    if ($value !== $formData[$rule['condition']]) {
                                        $isError = true;
                                    }
                                    break;
                                case 'unique':
                                    $user = new User();
                                    if (!empty($user->read(['id'], 'email="' . $value . '"'))) {
                                        $isError = true;
                                    }
                                    break;
                            }
                            if ($isError) {
                                if (array_key_exists('error', $rule)) {
                                    if (array_key_exists($key, $errors)) {
                                        $errors[$key] .= $rule['error'] . "<br>";
                                    } else {
                                        $errors[$key] = $rule['error'] . "<br>";
                                    }
                                } else {
                                    if (array_key_exists($key, $errors)) {
                                        $errors[$key] .= "Helytelenül kitöltött mező.<br>";
                                    } else {
                                        $errors[$key] = "Helytelenül kitöltött mező.<br>";
                                    }
                                }
                            }
                        }
                    }
                }
                
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            echo 'Hiba történt az adatok ellenőrzése közben. Kérjük próbálja újra később. <a href="/">Vissza a főoldalra</a>';
            exit;
        }

        return $errors;
    }

    public static function update(array $data, array $definitions) {
        self::initialize();
        $errors = self::validateUserFormData($data, $definitions);
        
        if (!empty($errors)) {
            return $errors;
        }

        if ($errors === false) {
            return;
        }

        if (array_key_exists('password_repeat', $data)) {
            unset($data['password_repeat']);
        }

        $profileImage = requestFilter(['profile_img'], $data);
        $userData = requestFilter(['id', 'email', 'password'], $data);
        $user = new User();
        $result = $user->update($userData, ['id' => $userData['id']]);

        if (!$result) {
            return false;
        }

        $fileName = $userData['email'] . '.png';
        $filePathName = sprintf('%s/images/%s', PUBLIC_USERS_DIR, $fileName);
        $result = move_uploaded_file($profileImage['profile_img']['tmp_name'], $filePathName);

        if ($result) {
            $user->createMeta2(['profile_img' => '/public/users/images/' . $fileName], $userData['id']);
        }

        $updatedData = $user->getByEmail($userData['email'], ['*']);
        $_SESSION['logged_in'] = $updatedData;

        if (!empty($userMetaData = requestFilter(['phone_number', 'lastname', 'firstname', 'street', 'number'], $data))) {
            $user->createMeta2($userMetaData, $userData['id']);
        }
        
        redirect('/profile/update');
    }
}
?>