<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    });

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}
%>

 <div style='padding-bottom:10px;'><?php echo $this->Flash->render(); ?></div>
<script type="text/javascript">
$(document).ready(function(){
    var error1 = $('.alert-danger');
    $('#<%= Inflector::humanize($action) %><%= $singularHumanName %>Form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
       
        <%
            foreach($fields as $field){
                if (strpos($action, 'add') !== false && $field == $primaryKey) {
                    continue;
                } elseif (!in_array($field, array('created', 'modified', 'updated'))) {
                    echo '"'.$field.'" : {required : true},'."\n";
                }
            }
          %>  
       
        },
        messages:{
        
        <% 
        foreach($fields as $field){
            if (strpos($action, 'add') !== false && $field == $primaryKey) {
                continue;
            } elseif (!in_array($field, array('created', 'modified', 'updated'))) {
            //if($schema[$field]['type'] == 'boolean')
               // echo '"data['.$singularHumanName.']['.$field.']" : {required :""},'."\n";
           // else
                echo '"'.$field.'" : {required :"Please enter '.$field.'."},'."\n";
            }
        }
         
       %> 
        },

        invalidHandler: function (event, validator) { //display error alert on form submit              
            //success1.hide();
            error1.show();
            //App.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
            label
                .closest('.form-group').removeClass('error');
        },
    });
});

</script>


<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_0">
                 <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-reorder"></i><?= __('<%= Inflector::humanize($action) %> <%= $singularHumanName %>') ?>   </div>                            
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                <div class="portlet-body form locations">
                    <div class="alert alert-danger display-hide">
                        <button data-close="alert" class="close"></button>
                        You have some form errors. Please check below.
                    </div>
                    <?= $this->Form->create($<%= $singularVar %>,array('class' => 'form-horizontal','id'=>'Add<%= $singularHumanName %>Form')) ?>
            <div class="form-body">  

<?php
<%
        foreach ($fields as $field) {
            if (in_array($field, $primaryKey)) {
                continue;
            }

            if (isset($keyFields[$field])) {
                $fieldData = $schema->column($field);


                if (!empty($fieldData['null'])) {
        %>
                    echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true]);
        <%
                        } else {
        %>
                    echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>]);
        <%
                        }
                        continue;
                           
                    }

            if (!in_array($field, ['created', 'modified', 'updated'])) {
                $fieldData = $schema->column($field);
                if (in_array($fieldData['type'], ['date', 'datetime', 'time']) && (!empty($fieldData['null']))) {
%>
            echo $this->Form->input('<%= $field %>', ['empty' => true]);
<%
                } else {
%>

            ?>
            <div class="form-group">
            <?php echo $this->Form->label('<%= $field %>' ,null, array('class' => 'col-md-3 control-label'));
            ?>
            <div class="col-md-4">
            <?php echo $this->Form->input('<%= $field %>',['class' => 'form-control', 'label' => false]);?>
       </div>
   </div>
            <?php
<%
                }
            }
        }

        if (!empty($associations['BelongsToMany'])) {
            foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
%>          
            echo $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]);
<%
            }
        }
%>
        ?>


                                              
    <div class="form-group">
         <label for="PropertyMainImage<spanClass=required>*</span>" class="col-md-3 control-label">Main Image<span class="required"> * </span></label>                                  <div class="col-md-4">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div>
                            <span class="btn btn-file btn default"><span class="fileupload-new">Select image</span>
                            <span class="fileupload-exists">Change</span>
                            <input id="imgUploadSec" name="" class="default" type="file"></span>

                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 30px; max-height: 20px; line-height: 20px;"></div>
                        </div>Image dimension : 776 x 463 or similar ratio
                    </div> 
                    <span class="imageErrCont"></span>
                </div>
            </div>

        <div class="form-group">
            <label class="col-md-3 control-label" style="padding-top: 0px;" for="">Checkbox<span class="required">  </span></label>

            <div class="col-md-4">
                <input name=""  value="0" type="hidden"><div class="checker" ><span class=""><input name="" class="chkchild" value="1" type="checkbox"></span></div>                           </div>
        </div>
 <div class="form-group">
  <label class="col-md-3 control-label">Membership</label>
  <div class="radio-list">
         <label class="radio-inline">
                        <div class="radio" id="uniform-optionsRadios1"><span class=""><input name="optionsRadios" id="optionsRadios1" value="option1" checked="" type="radio"></span></div> Option 1 </label>
                        <label class="radio-inline">
                        <div class="radio" id="uniform-optionsRadios2"><span class="checked"><input name="optionsRadios" id="optionsRadios2" value="option2" type="radio"></span></div> Option 2 </label>
                    </div>
                </div>
     <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn blue">Submit</button>
             <button type="button" class="btn default" onclick="window.location='<?php echo $this->request->webroot.'<%= $pluralHumanName %>'; ?>'">Cancel</button>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<!-- END FORM-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
