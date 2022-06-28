<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Exception\Auth\RevokedIdToken;
use Kreait\Firebase\Database;

class data
{
    public $key;
    public $value;
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
class FirebaseController extends Controller
{
    protected $auth, $database;

    public function __construct(Database $database, Auth $auth)
    {
        $this->auth = $auth;
        $this->database = $database;
        $this->tablename = 'user';
    }

    public function signUp(Request $request)
    {
        $email = $request->email;
        $pass = $request->pass;

        try {
            $createUser =  $this->auth->createUserWithEmailAndPassword($email, $pass);
            $postData = [
                'firebaseUserId' => $createUser->uid,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'admin' => '0'
            ];
            $this->database->getReference($this->tablename)->push($postData);
            return $createUser;
        } catch (\Throwable $e) {
            switch ($e->getMessage()) {
                case 'The email address is already in use by another account.':
                    return "Email sudah digunakan.";
                    break;
                case 'A password must be a string with at least 6 characters.':
                    return "Kata sandi minimal 6 karakter.";
                    break;
                default:
                    return $e->getMessage();
                    break;
            }
        }
    }
    public function signIn(Request $request)
    {
        $email = $request->email;
        $pass = $request->pass;

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $pass);
            $getValue =  $this->database->getReference($this->tablename)->getValue();
            $theDefaults = [
                'firebaseUserId' => $signInResult->firebaseUserId(),
                'idToken' => $signInResult->idToken()
            ];
            foreach ($getValue as $get => $x) {
                if ($x['admin'] == '0' && $x['email'] == $email && $x['firebaseUserId'] == $signInResult->firebaseUserId()) {
                    array_push($theDefaults, new data($get, $x));
                }
            }


            return $theDefaults;
        } catch (\Throwable $e) {
            switch ($e->getMessage()) {
                case 'INVALID_PASSWORD':
                    return "INVALID_PASSWORD.";
                    break;
                case 'EMAIL_NOT_FOUND':
                    return "EMAIL_NOT_FOUND.";
                    break;
                default:
                    return $e->getMessage();
                    break;
            }
        }
    }
    public function users()
    {
        try {
            $getValue =  $this->database->getReference($this->tablename)->getValue();
            $theDefaults = [];
            foreach ($getValue as $get => $x) {

                array_push($theDefaults, new data($get, $x));
            }
            return $theDefaults;
        } catch (\Throwable $e) {
            return 'error';
        }
    }
    public function signOut($firebaseUserId)
    {
        try {
            $this->auth->revokeRefreshTokens($firebaseUserId);
            return "User berhasil logout.";
        } catch (\Throwable $e) {
            return "User belum login.";
        }
    }

    public function Check($idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken, $checkIfRevoked = true);
            if ($verifiedIdToken) {
                return 'true';
            }
        } catch (\Throwable $e) {
            return   'false';
        }
    }
    public function signUpadmin(Request $request)
    {
        $email = $request->email;
        $pass = $request->pass;

        try {
            $createUser =  $this->auth->createUserWithEmailAndPassword($email, $pass);
            $postData = [
                'firebaseUserId' => $createUser->firebaseUserId,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'admin' => '1'
            ];
            $this->database->getReference($this->tablename)->push($postData);
            return $createUser;
        } catch (\Throwable $e) {
            switch ($e->getMessage()) {
                case 'The email address is already in use by another account.':
                    return "Email sudah digunakan.";
                    break;
                case 'A password must be a string with at least 6 characters.':
                    return "Kata sandi minimal 6 karakter.";
                    break;
                default:
                    return $e->getMessage();
                    break;
            }
        }
    }
    public function signIndmin(Request $request)
    {
        $email = $request->email;
        $pass = $request->pass;

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $pass);
            $getValue =  $this->database->getReference($this->tablename)->getValue();
            $theDefaults = [
                'firebaseUserId' => $signInResult->firebaseUserId(),
                'idToken' => $signInResult->idToken()
            ];
            foreach ($getValue as $get => $x) {
                if ($x['admin'] == '1' && $x['email'] == $email && $x['firebaseUserId'] == $signInResult->firebaseUserId()) {
                    array_push($theDefaults, new data($get, $x));
                }
            }


            return $theDefaults;
        } catch (\Throwable $e) {
            switch ($e->getMessage()) {
                case 'INVALID_PASSWORD':
                    return "Kata sandi salah!.";
                    break;
                case 'EMAIL_NOT_FOUND':
                    return "Email tidak ditemukan.";
                    break;
                default:
                    return $e->getMessage();
                    break;
            }
        }
    }





















    public function userCheck()
    {
        // $idToken = "";

        // $this->auth->revokeRefreshTokens("");

        // if (Session::has('firebaseUserId') && Session::has('idToken')) {
        //     dd("User masih login.");
        // } else {
        //     dd("User sudah logout.");
        // }

        // try {
        //     $verifiedIdToken = $this->auth->verifyIdToken($idToken, $checkIfRevoked = true);
        //     dump($verifiedIdToken);
        //     dump($verifiedIdToken->claims()->get('sub')); // uid
        //     dump($this->auth->getUser($verifiedIdToken->claims()->get('sub')));
        // } catch (\Throwable $e) {
        //     dd($e->getMessage());
        // }

        // try {
        //     $verifiedIdToken = $this->auth->verifyIdToken(Session::get('idToken'), $checkIfRevoked = true);
        //     $response = "valid";
        //     // dd("Valid");
        //     // $uid = $verifiedIdToken->getClaim('sub');
        //     // $user = $auth->getUser($uid);
        //     // dump($uid);
        //     // dump($user);
        // } catch (\InvalidArgumentException $e) {
        //     // dd('The token could not be parsed: '.$e->getMessage());
        //     $response = "The token could not be parsed: " . $e->getMessage();
        // } catch (InvalidToken $e) {
        //     // dd('The token is invalid: '.$e->getMessage());
        //     $response = "The token is invalid: " . $e->getMessage();
        // } catch (RevokedIdToken $e) {
        //     $response = "revoked";
        // } catch (\Throwable $e) {
        //     if (substr(
        //         $e->getMessage(),
        //         0,
        //         21
        //     ) == "This token is expired") {
        //         $response = "expired";
        //     } else {
        //         $response = "something_wrong";
        //     }
        // }
        // return $response;
    }

    public function read()
    {
        $ref = $this->database->getReference('hewan/herbivora/domba')->getSnapshot();
        dump($ref);
        $ref = $this->database->getReference('hewan/herbivora')->getValue();
        dump($ref);
        $ref = $this->database->getReference('hewan/karnivora')->getValue();
        dump($ref);
        $ref = $this->database->getReference('hewan/omnivora')->getSnapshot()->exists();
        dump($ref);
    }

    public function update()
    {
        // before
        $ref = $this->database->getReference('tumbuhan/dikotil')->getValue();
        dump($ref);

        // update data
        $ref = $this->database->getReference('tumbuhan')
            ->update(["dikotil" => "mangga"]);

        // after
        $ref = $this->database->getReference('tumbuhan/dikotil')->getValue();
        dump($ref);
    }

    public function set()
    {
        // before
        $ref = $this->database->getReference('hewan')->getValue();
        dump($ref);

        // set data
        $ref = $this->database->getReference('hewan/karnivora')
            ->set([
                "harimau" => [
                    "benggala" => "galak",
                    "sumatera" => "jinak"
                ]
            ]);

        // after
        $ref = $this->database->getReference('hewan')->getValue();
        dump($ref);
    }

    public function delete()
    {
        // before
        $ref = $this->database->getReference('hewan/karnivora/harimau')->getValue();
        dump($ref);

        /**
         * 1. remove()
         * 2. set(null)
         * 3. update(["key" => null])
         */

        // remove()
        $ref = $this->database->getReference('hewan/karnivora/harimau/benggala')->remove();

        // set(null)
        $ref = $this->database->getReference('hewan/karnivora/harimau/benggala')
            ->set(null);

        // update(["key" => null])
        $ref = $this->database->getReference('hewan/karnivora/harimau')
            ->update(["benggala" => null]);

        // after
        $ref = $this->database->getReference('hewan/karnivora/harimau')->getValue();
        dump($ref);
    }
}
