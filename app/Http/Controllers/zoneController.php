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
class zoneController extends Controller
{

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename='zone';
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
        $postData =[
            'name_zone_fr' => $request->name_zone_fr,
            'name_zone_ar' => $request->name_zone_ar,
            'id_ville' => $request->id_ville,
            'img_zone' => $request->img_zone
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
            if($x['id_ville']==$id){
                array_push($theDefaults,new data($get,$x));
            }
        }

        if ($getValue) {
            return  $theDefaults ;
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
            'name_zone_fr' => $request->name_zone_fr,
            'name_zone_ar' => $request->name_zone_ar,
            'img_zone' => $request->img_zone
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
