<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

trait UserTrait{

    //inserting ids into a many to many relationship table,
    //form input (eg checkbox) name for groupids should have same string+integer (eg index as in "cluster3") < count of group
    public static function insertIdIntoMultipleGroups(Request $request,$id,$relationship_table,$group_count,$group_form_name_prefix,$ids_Column_name,$groups_column_name,$third_column_name,$third_column_value){
        $insert_values_array = [];
        for($i=0; $i<$group_count; $i++){
            $form_name = $group_form_name_prefix.$i;
            if($request->filled($form_name)){
                $group_id_value = $request->input($form_name);
                $insert_values_array[] = array($ids_Column_name => $id,$groups_column_name=>$group_id_value, $third_column_name=>$third_column_value);
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
            if($request->filled($form_name)){
                $group_id_value = $request->input($form_name);
                $remove_values_array[] = array($ids_Column_name => $id,$groups_column_name=>$group_id_value, $third_column_name=>$third_column_value);
            }
        }

        if(!empty($remove_values_array)){
            DB::table($relationship_table)->where([$remove_values_array])->delete();
            return true;
        }
        return false;
    }


    /*
        function wastedCodes(){


            $focalarea_array = [];
            for($i=0; $i<$focalarea_count; $i++){
                if($request->filled('focalarea'.$i)){
                    $focalarea = $request->('focalarea'.$i);
                    $focalarea_array[] = array("objecttypeid"=>$userid,"objectid"=>$this->ObjectId,"focalareaid"=>$focalarea);
                }
            }
            if(!empty($focalarea_array)){
                DB::table('focalareaobjecttype')->insert($focalarea_array);
            }
        }*/

}