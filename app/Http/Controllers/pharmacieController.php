<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;
class data{
    public $key;
    public $value;
    public function __construct($key,$value)
    {
        $this->key=$key;
        $this->value=$value;

    }
}
class pharmacieController extends Controller
{
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename='pharmacie';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $getValue =  $this->database->getReference($this->tablename)->getValue();
        $theDefaults=array();

        foreach ( $getValue as $get=>$x) {
                array_push($theDefaults,new data($get,$x));

            }


        if ($getValue) {
            return  $theDefaults ;
        } else {
            return 'get errore';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postData = [
            'name_pharmacie' => $request->name_pharmacie,
            'adresse_pharmacie' => $request->adresse_pharmacie,
            'tel_pharmacie' =>$request->tel_pharmacie,
            'latitude_pharmacie' => $request->latitude_pharmacie,
            'longitude_pharmacie' =>$request->longitude_pharmacie,
            'img_pharmacie' =>$request->img_pharmacie,
            'staut_pharmacie' => $request->staut_pharmacie,
            'id_zone' =>$request->id_zone,
            'Date_Debut_pharmacie' =>$request->Date_Debut_pharmacie,
            'Date_Fin_pharmacie' =>$request->Date_Fin_pharmacie,
            'stars'=>$request->stars
        ];
        $postRef = $this->database->getReference( $this->tablename)->push($postData);
        if( $postRef)
        {   return  'add Successfully';}
        else{   return 'not added';}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getValue =  $this->database->getReference($this->tablename)->getValue();

        $theDefaults=array();

        foreach ( $getValue as $get=>$x) {
            if($x['id_zone']==$id){
                array_push($theDefaults,new data($get,$x));
            }

        }

        if ($getValue) {
            return  $theDefaults ;
        } else {
            return 'get errore';
        }
    }
    public function detail($id)
    {
        $getValue =  $this->database->getReference($this->tablename.'/'.$id)->getValue();
        if ($getValue) {
            return  $getValue ;
        } else {
            return 'get errore';
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updateData =[
            'name_pharmacie' => $request->name_pharmacie,
            'adresse_pharmacie' => $request->adresse_pharmacie,
            'tel_pharmacie' =>$request->tel_pharmacie,
            'latitude_pharmacie' => $request->latitude_pharmacie,
            'longitude_pharmacie' =>$request->longitude_pharmacie,
            'img_pharmacie' =>$request->img_pharmacie,
            'staut_pharmacie' => $request->staut_pharmacie,
            'id_zone' =>$request->id_zone,
            'Date_Debut_pharmacie' =>$request->Date_Debut_pharmacie,
            'Date_Fin_pharmacie' =>$request->Date_Fin_pharmacie,
            'stars'=>$request->stars
        ];
        $updatedata =$this->database->getReference($this->tablename.'/'.$id)->update($updateData);
        if($updatedata)
        {   return 'update Successfully';}
        else{   return 'Not updated';}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroydata= $this->database->getReference($this->tablename.'/'.$id)->remove();
        if($destroydata)
        {   return 'remove Successfully';}
        else{   return 'Not remove';}

    }
}
