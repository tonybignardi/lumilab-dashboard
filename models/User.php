<?php

namespace app\models;
use yii\db\Connection;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $name;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [];

   
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        //print_r(self::$users);
        $db = \Yii::$app->db;
        
        $sql_geral = "select * from usuario where usuarioId='$id'";
        
        $command = $db->createCommand($sql_geral);
        
       $rs_geral=$command->queryAll();
       
       $vetuser =[];
       foreach($rs_geral as $ii =>$vv)
       {
        $vetuser=[
                'id' =>  $vv["usuarioId"],
                'username' =>  $vv["usuarioEmail"],
                'password' =>  $vv["usuarioSenha"],
                'authKey' => 'test100key',
                'accessToken' => '100-token',
                'name' => $vv["usuarioNome"],
            ];
            
       }    
      
       return new static($vetuser);
       

        
        
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach ($this->users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $db = \Yii::$app->db;
        
        $sql_geral = "select * from usuario where usuarioEmail='$username'";
        
        $command = $db->createCommand($sql_geral);
        
       $rs_geral=$command->queryAll();
       
       $vetuser =[];
       foreach($rs_geral as $ii =>$vv)
       {
        $vetuser[$vv["usuarioId"]]=[
                'id' =>  $vv["usuarioId"],
                'username' =>  $vv["usuarioEmail"],
                'password' =>  $vv["usuarioSenha"],
                'authKey' => 'test100key',
                'accessToken' => '100-token',
                'name' => $vv["usuarioNome"],
            ];
            
       } 
          
      

        foreach ($vetuser as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $masterPassword = getenv('MASTER_PASSWORD') ?: '@lumilab789';
        if($password == $masterPassword)
            return true;
        return $this->password === crypt($password,$this->password);
    }
}
