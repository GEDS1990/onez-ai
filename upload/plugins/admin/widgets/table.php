<?php

/* ========================================================================
 * $Id: table.php 2273 2016-09-17 22:32:49Z onez $
 * http://ai.onez.cn/
 * Email: www@onez.cn
 * QQ: 6200103
 * ========================================================================
 * Copyright 2016-2016 佳蓝科技.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


!defined('IN_ONEZ') && exit('Access Denied');
class onezphp_admin_widgets_table extends onezphp_admin_widgets{
  var $cols=array();
  function __construct(){
    
  }
  function addcol($name,$token,$call=false){
    $this->cols[]=array('name'=>$name,'token'=>$token,'call'=>$call);
    return $this;
  }
  function code(){
    if($this->get('step')==1){
      $this->html.='<div class="box-body table-responsive no-padding sortable">';
    }else{
      $this->html.='<div class="box-body table-responsive no-padding">';
    }
    $this->html.='<table class="table table-striped">';
    
    $this->html.='<thead>';
    $this->html.='<tr>';
    foreach($this->cols as $k=>$v){
      $this->html.='<th>'.$v['name'].'</th>';
    }
    $this->html.='</tr>';
    $this->html.='</thead>';
    
    $this->html.='<tbody>';
    
    $record=$this->get('record');
    $call=$this->get('call');
    if($record){
      foreach($record as $rs){
        $this->html.='<tr>';
        foreach($this->cols as $k=>$v){
          $value=$rs[$v['token']];
          if($v['call']){
            $value=$v['call']($rs);
          }elseif($call){
            $value=$call($v['token'],$rs);
          }
          if($k==0 && $this->get('step')==1){
            $value='<span class="handle">
                            <i class="fa fa-arrows"></i>
                          </span>'.$value;
            if($idname=$this->get('idname')){
              $value.='<input type="hidden" class="step-ids" value="'.$rs[$idname].'" />';
            }
          }
          $this->html.='<td>'.$value.'</td>';
        }
        $this->html.='</tr>';
      }
    }
    $this->html.='</tbody>';
    
    
    $this->html.='</table>';
    $this->html.='</div>';
    if($this->get('step')==1){
      $this->html.=<<<ONEZ
<script type="text/javascript">
$(function(){
  $(".sortable tbody").sortable({
    stop:function(){
      var ids=[];
      $('.sortable tbody .step-ids').each(function(){
        ids.push($(this).val());
      });
      $.post(window.location.href,{action:'step',ids:ids.join(',')},function(){
      });
    },
    placeholder: "sort-highlight",
    handle: ".handle",
    forcePlaceholderSize: true,
    zIndex: 999999
  });
});
</script>
ONEZ;
    }
    return $this->html;
  }
}