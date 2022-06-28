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
class paysController extends Controller
{

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename='payes';
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
            'name_paye_fr' => $request->name_paye_fr,
            'name_paye_ar' => $request->name_paye_ar,
            'img_paye' =>$request->img_paye
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
        $showdata = $this->database->getReference($this->tablename)->getChild($id)->getvalue();
        if( $showdata)
        {   return  $showdata;}
        else{   return 'Not found errore';}
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
            'name_paye_fr' => $request->name_paye_fr,
            'name_paye_ar' => $request->name_paye_ar,
            'img_paye' =>$request->img_paye
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
