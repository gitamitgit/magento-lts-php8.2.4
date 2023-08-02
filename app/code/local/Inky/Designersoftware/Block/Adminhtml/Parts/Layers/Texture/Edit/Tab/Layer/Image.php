<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Texture_Edit_Tab_Layer_Image extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      
     
      $fieldset = $form->addFieldset('parts_layers_texture_form', array('legend'=>Mage::helper('designersoftware')->__('Image information'))); 
         
          
      $fromData = Mage::registry('parts_layers_texture_data')->getData();      
      //echo '<pre>';print_r($fromData);exit;
      
      if($this->getRequest()->getParam('id')):
		$disabled = true;
	  else:
		$disabled = false;
      endif;              
       
      $anglesCollection  = Mage::getModel('designersoftware/angles')->getCollection()->addFieldToFilter('status',1);	
      $partsCollection = Mage::getModel('designersoftware/parts')->getCollection()->addFieldToFilter('parts_id', $fromData['parts_id'])->addFieldToFilter('status',1)->getFirstItem()->getData();
      $partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollection()->addFieldToFilter('parts_layers_id', $fromData['parts_layers_id'])->addFieldToFilter('status',1)->getFirstItem();
	  $textureCollection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('texture_id', $fromData['texture_id'])->addFieldToFilter('status',1)->getFirstItem();
	  
	  $midPath = $partsCollection['code'] . DS  .$partsLayersCollection->getLayerCode() . DS . $textureCollection->getTextureCode();
      foreach($anglesCollection as $angle):
		  
		  $fileDirPath = Mage::helper('designersoftware/parts_layers')->getColorableDirPath($midPath, $angle->getTitle(),'parts_layers');
		  $fileWebPath = Mage::helper('designersoftware/parts_layers')->getColorableWebPath($midPath, $angle->getTitle(),'parts_layers');
		  
		  $fieldset->addField($angle->getTitle(), 'file', array(
			  'label'     => Mage::helper('designersoftware')->__('Layer '.$angle->getTitle().' Angle'),
			  'required'  => false,
			  'name'      => 'filename['.$angle->getTitle().']',
			  'after_element_html' => file_exists($fileDirPath)?'<p><img src="' . $fileWebPath . '" width="150"/></p><br>Remove Image: <input type="checkbox" name="delImage['. $angle->getTitle() .']" id="delImage['. $angle->getTitle() .']" />':'',
		  ));
		  
	  endforeach;      
     
      if ( Mage::getSingleton('adminhtml/session')->getPartsLayersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsLayersData());
          Mage::getSingleton('adminhtml/session')->setPartsLayersData(null);
      } elseif ( Mage::registry('parts_layers_data') ) {          
          $formData = Mage::registry('parts_layers_data')->getData();
          
          // Update coming Data array accordingly          
          //$formData = $this->updateFormData($formData);
          
          $form->setValues($formData);
      }
      return parent::_prepareForm();
  }
  
   public function updateFormData($formData){
     
      $style_design_ids = unserialize($formData['style_design_ids']);
      $partsTypeId = $formData['parts_type_id'];
      $partsId = $formData['parts_id'];
      
      $formData['parts_type_id']=array($partsId=>$partsTypeId);
      $formData['style_design_ids'] = $style_design_ids;
      
      //echo '<pre>';print_r($formData);exit;
	  	
      return $formData;      
  }
}
