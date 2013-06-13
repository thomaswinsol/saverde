<?php

/**
 * Order helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_OrderHelper extends Zend_View_Helper_Abstract
{
	
	public $view;
	
	
	/**
	 * setView
	 * @see Zend_View_Helper_Abstract::setView()
	 * 
	 * If a helper class has a setView() method, it will be called when the helper class
	 * is first instantiated, and passed to the current view object. 
	 */
	public function setView(Zend_View_Interface $view){
		$this->view = $view;		
	}
	
	
	/**
 	* Workaround to call multiple methods 
 	* @param string $method
 	* @param array $args
 	*/	
	public function OrderHelper($method,$args=NULL)
 	{
	 $thisClass    = get_class();
     $classMethods = get_class_methods($thisClass);
     // case the method exists into this class  //
     if(in_array($method,$classMethods))
     {
        $arrCaller = array($thisClass,$method);
        return call_user_func_array($arrCaller, $args );
     }
	 //method not found in this class
     	throw new Exception("Method ".$method." doesn't exist in class " .$thisClass."." ); 	
 }
	
 
 public function typeName($data){
 	//var_dump($data);
 	if ($data['ID_Type']==1){
 		return 'Offerte';
 	}
 	else if ($data['ID_Type']==2){
 		return 'Bestelbon';
 	}
        else if ($data['ID_Type']==3){
 		return 'Factuur';
 	}
 	return;
 	
 }


 
 
 	public function choiceField($data){
 		
 		//Zend_Debug::dump($data);
 		$fieldName = 'vars['.$data['variableId'].']';
 		//var_dump($data);
 		//echo '<br />input type = ' . $data['inputType'];
 	 	if (in_array($data['inputType'], array('T','I','D'))){
 			return $this->view->formText($fieldName,null,array('style' => 'width:40px;'));
 		}else if ($data['inputType'] == 'B'){
 	 		// B = Boolean = checkbox, only 1 checkbox
 	 		//$buildSelectOptions['emptyRow'] = FALSE;
 	 		//$options = $keuzelijstdetailsModel->buildSelectFromArray($choiceData,$buildSelectOptions);
 			//return $this->view->formMultiCheckbox($fieldName,null,null,$options);
 			return $this->view->formCheckbox($fieldName,null,null) . ' Ja';
 		}else if ($data['inputType'] == 'K'){
 			$keuzelijstdetailsModel = new Share_Models_Keuzelijstdetails();
 			$choiceData = $keuzelijstdetailsModel->getAll('ID_Keuzelijst = ' . (int)$data['choiceId'].' AND Waarde != " "');
 			//var_dump($choiceData);
 			if (empty($choiceData)){
 				return;
 			}
 			$buildSelectOptions = array( 		
    			'key'      => 'Waarde',
    			'value'    => 'Omschrijving',
		    	//	'emptyRow' => FALSE,
    		);
 			//var_dump($options);
 	 		$options = $keuzelijstdetailsModel->buildSelectFromArray($choiceData,$buildSelectOptions);
 			return $this->view->formSelect($fieldName,null,null,$options);
 		}
 		return;
 	}


        public function getFormatSize($filesize){
            $type = array('bytes', 'KB', 'MB', 'GB');
            for ($i = 0; $filesize > 1024; $i++) {
                $filesize /= 1024;
            }
            if ($i>0){
                return number_format($filesize, 2).' '.$type[$i];
            } else {
                return $filesize.' '.$type[$i];
            }
        }

         public function attachements($Files, $totalFiles){ ?>

              <br clear="all" />
         <fieldset id="orderWinsol">
            <legend style="font-weight:bold;"><?php echo 'Bijlagen';?></legend>
            <br />

            <fieldset style="width:99%;float:left;min-height:175px;border:0px;">
                <div class="bold"></div>
                <p style="font-size:75%;margin-left:20px;">
                    <?php echo 'Opmerking' . ': '.' max. 10 bestanden, max. 2 MB / bestand';?>
                </p>
                <table class="bList" id="tableOrderFiles" style="margin-left:20px;">
                    <thead>
                        <tr>
                            <th>
                                <span id="totalFiles"><?php echo $totalFiles;?></span>/10
                            </th>
                            <th><?php echo 'Bestandsnaam';?></th>
                            <th><?php echo 'Datum upload';?></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                        if (!empty($Files)){
                            $filePath = APPLICATION_PATH.'/../public/uploads/klanten/';
                            //debug_array($files);

                            foreach($Files as $k=>$v){
                               // die($filePath.$v['fileName']);
                                if (!file_exists($filePath.$v['fileName'])){
                                    continue;
                                }
                                $fileId = $v['ID']; ?>
                                <tr>
                                    <td nowrap="nowrap" style="width:1%;">
<?php                                   //if ($this->order['ID_Status'] <=3){?>
                                            <input type="checkbox" name="cbx_fileId[]" value="<?php echo $fileId;?>" />
<?php                                   //} ?>
                                        <a href="<?php echo $this->view->url(
                                            array('controller' => 'order','action' => 'download-file','fileId' => $fileId));?>"
                                            title="Download bestand"><img src="/base/images/icons/download.gif" style="border:1px solid gray;" />
                                        </a>
                                    </td>
                                    <td style="width:58%;"><?php echo $v['fileNameOrig'];?>
<?php                                   if (!empty($v['fileSize']) && file_exists($filePath.$v['fileName'])){ ?>
                                            <span style="font-size:85%;">[<?php echo $this->getFormatSize($v['fileSize']);?>]</span>
<?php                                   } ?>
                                    </td>
                                    <td style="width:1%;" nowrap="nowrap">
<?php                                   echo $v['creationDate']; ?>
                                    </td>
                                    <td style="width:40%;" nowrap="nowrap">

                                    </td>
                                </tr>
<?php
                            } //end foreach
                        } else{ ?>
                            <tr><td colspan="4"><?php echo 'Geen bestanden gevonden'; ?></td></tr>
<?php                   } ?>
                    </tbody>
<?php               //if ($this->order['ID_Status'] <=3) { ?>
                        <tfoot>
                            <tr>
                                <td style="border-right:0;border-top:1px solid black;">
                                    <input type="image" style="display:<?php echo !empty($order['Files']) ? 'block': 'none';?>.'" src="/base/images/icons/btn_delete.gif" id="btn_deleteFiles" name="btn_deleteFiles" alt="Delete" title="Delete" onclick="return confirm('<?php echo 'Geselecteerde bestanden verwijderen';?>')" class="" />
                                </td>
                                <td style="text-align:left;border-left:0;border-top:1px solid black;" colspan="2">&nbsp;
                                    <div id="filesButtons" style="margin:0;padding:5;">
<?php                                   if ($totalFiles<10){ ?>
                                            <input id="orderFiles" name="orderFiles" type="file" style="margin:0;padding:0;" />
<?php                                   } ?>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
<?php               //} ?>
                </table>
            </fieldset>
        </fieldset>

        <br clear="all" />
<?php }


}

