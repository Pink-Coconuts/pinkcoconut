<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersVerificationDocuments extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_verification_documents';

    /**
     * The attribute appended with result.
     *
     * @var string
     */
    protected $appends = ['src','download_src'];

    // Get image source URL of the document
    public function getSrcAttribute(){
        $site_settings_url = @SiteSettings::where('name' , 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        $photo_src=explode('.',$this->attributes['name']);
        if(strtolower(end($photo_src)) == 'pdf'){
            return $url.'/images/document.png';
        }
        $type = $this->attributes['type'];
        if($type == 'id_document')
            $type_loc = 'documents/';
        
        if(count($photo_src)>1)
        {            
            $name = $this->attributes['name'];
            return $url.'/images/users/'.$this->attributes['user_id'].'/'.$type_loc.$name;
        }
        else
        {
            $options['secure']=TRUE;
            /*$options['width']=450;
            $options['height']=250;*/
            $options["format"] = 'jpg';
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['name'],$options);
        }

    }

     // Get Download image or PDF source URL of the document
    public function getDownloadSrcAttribute(){
        $site_settings_url = @SiteSettings::where('name' , 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        $photo_src=explode('.',$this->attributes['name']);
        $type = $this->attributes['type'];
        if($type == 'id_document')
            $type_loc = 'documents/';
        if(count($photo_src)>1)
        {            
            $name = $this->attributes['name'];
            return $url.'/images/users/'.$this->attributes['user_id'].'/'.$type_loc.$name;
        }
        else
        {
            $options['secure']=TRUE;
            /*$options['width']=450;
            $options['height']=250;*/
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['name'],$options);
        }

    }    

    // Get Verification status of the user
    public function getVerificationStatusAttribute(){
        $id_documents = UsersVerificationDocuments::whereType('id_document')->where('user_id', $this->attributes['user_id'])->get()->count();
        if($id_documents > 0){
            $verification_status = $id_documents[0]->user_verification_status;
        }
        else{
            $verification_status = 'Connect';
        }
        return $verification_status;
    }

    // Get ID Verification status of the User
    public function getIdDocumentVerificationStatusAttribute(){
        $id_documents = @UsersVerificationDocuments::whereType('id_document')->where('user_id', $this->attributes['user_id'])->get();
        if(count($id_documents) == 0){
            $verification_status = 'No';
        }
        else if($id_documents[0]->status != 'No'){
            $verification_status = $id_documents[0]->status;
        }
        else{
            $verification_status = 'Pending';
        }

        return $verification_status;
    }

    // Get Verification status of the user
    public function getUserVerificationStatusAttribute(){

        $resubmit_status = UsersVerificationDocuments::where('user_id', $this->attributes['user_id'])->where('status','Resubmit')->get()->count();
        $pending_status = UsersVerificationDocuments::where('user_id', $this->attributes['user_id'])->where('status','Pending')->get()->count();
        $verified_status = UsersVerificationDocuments::where('user_id', $this->attributes['user_id'])->where('status','Verified')->get()->count();
        if($resubmit_status > 0){
            $verification_status = 'Resubmit';
        }
        else if($pending_status > 0){
            $verification_status = 'Pending';
        }
        elseif($verified_status > 0){
            $verification_status = 'Verified';
        }
        else{
            $verification_status = 'No';
        }

        return $verification_status;
    }
}
