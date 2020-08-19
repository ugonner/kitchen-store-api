<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ManyToManyHandler{

    //inserting ids into a many to many relationship table,
    //form input (eg checkbox) name for groupids should have same string+integer (eg index as in "cluster3") < count of group
    public static function insertIdIntoMultipleGroups(Request $request,$id,$relationship_table,$group_count,$group_form_name_prefix,$ids_Column_name,$groups_column_name,$third_column_name,$third_column_value){
        $insert_values_array = [];
        for($i=0; $i<$group_count; $i++){
            $form_name = $group_form_name_prefix.$i;
            //echo $request->input($form_name) .' '.$form_name;
            //echo($form_name);
            if($request->filled($form_name)){
                $group_id_value = $request->input($form_name);
                //echo $group_id_value .' '.$form_name;
                $insert_values_array[] = array($ids_Column_name => $id,$groups_column_name=>$group_id_value, $third_column_name=>$third_column_value);
            }
        }

        if(!empty($insert_values_array)){
            DB::table($relationship_table)->insert($insert_values_array);
            return true;
        }
        return false;
    }

    //inserting ids into a many to many relationship table,
    //form input (eg checkbox) name for groupids should have same string+integer (eg index as in "cluster3") < count of group
    public static function insertIdIntoMultipleGroups_TwoCols(Request $request,$id,$relationship_table,$group_count,$group_form_name_prefix,$ids_Column_name,$groups_column_name){
        $insert_values_array = [];
        for($i=0; $i<$group_count; $i++){
            $form_name = $group_form_name_prefix.$i;
            //echo $request->input($form_name) .' '.$form_name;
            if($request->filled($form_name)){
                $group_id_value = $request->input($form_name);
                //echo $group_id_value .' '.$form_name;
                $insert_values_array[] = array($ids_Column_name => $id,$groups_column_name=>$group_id_value);
            }
        }

        if(!empty($insert_values_array)){
            DB::table($relationship_table)->insert($insert_values_array);
            return true;
        }
        return false;
    }


    //removing ids into a many to many relationship table,
    //form input (eg checkbox) name for groupids should have same string+integer (eg index as in "cluster3") < count of group
    public static function removeIdFromMultipleGroups(Request $request,$id,$relationship_table,$group_count,$group_form_name_prefix,$ids_Column_name,$groups_column_name,$third_column_name,$third_column_value){
        $insert_values_array = [];
        for($i=0; $i<$group_count; $i++){
            $form_name = $group_form_name_prefix.$i;
            //echo($form_name.' value='.$request->input($form_name));
            if($request->filled($form_name)){
                $group_id_value = $request->input($form_name);
                $remove_values_array[] = array("relationshiptable"=>$relationship_table,"whereArray"=>[$ids_Column_name => $id,$groups_column_name=>$group_id_value, $third_column_name=>$third_column_value]);
            }
        }

        if(!empty($remove_values_array)){
            /*$remove_values_array->map(function($key){
                return DB::table($relationship_table)->where($key)->orders();
            });*/
            //foreach($remove_values_array as #rma){}
            /*function deleteEach($arr){
                return DB::table($arr["relationshiptable"])->where($arr["whereArray"])->orders();
            }*/
            array_map(function($arr){
                return DB::table($arr["relationshiptable"])->where($arr["whereArray"])->delete();
            },$remove_values_array);
            //DB::table($relationship_table)->where([$remove_values_array])->delete();
            return true;
        }
        return false;
    }

    //removing ids into a many to many relationship table,
    //form input (eg checkbox) name for groupids should have same string+integer (eg index as in "cluster3") < count of group
    public static function removeIdFromMultipleGroups_TwoCols(Request $request,$id,$relationship_table,$group_count,$group_form_name_prefix,$ids_Column_name,$groups_column_name){
        $insert_values_array = [];
        for($i=0; $i<$group_count; $i++){
            $form_name = $group_form_name_prefix.$i;
            if($request->filled($form_name)){
                $group_id_value = $request->input($form_name);
                $remove_values_array[] = array("relationshiptable"=>$relationship_table, "whereArray"=>[$ids_Column_name => $id,$groups_column_name=>$group_id_value]);
            }
        }

        if(!empty($remove_values_array)){
            array_map(function($arr){
                return DB::table($arr["relationshiptable"])->where($arr["whereArray"])->delete();
            },$remove_values_array);
            return true;
        }
        return false;
    }



}