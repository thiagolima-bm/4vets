<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Newproduct_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $element = $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('newproduct_form', array('legend'=>Mage::helper('wholesaler')->__('Product Information')));
        
        $product = Mage::registry('wholesaler_data');
        $is_new = $this->getRequest()->getParam('id') == '';

        $zones = str_replace("|",",",Bm_Cmon::getZipcodezone());    
       
        $zoneCollection = Mage::getModel('zipcodezone/zipcodezone')->getCollection();
       
        $zoneCollection->getSelect()->where("zipcodezone_id IN ($zones)");
       
        $values = array();

        foreach($zoneCollection as $zone)
        {
            array_push($values,array('value'=>$zone->getId(),'label'=>$zone->getZonename()));

            
        }

        $fieldset->addField('zone', 'select', array(
            'label'     => Mage::helper('zipcodezone')->__('Zone'),
            'name'      => 'zone',
            'required'  => true,
            'note'      => Mage::helper('zipcodezone')->__('Product Zone'),
            'values'    => $values,
            'value'     => $product->getZone(),
        ));

        $eanfield = $fieldset->addField('psku', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Sku'),
            'class'     => ($is_new ? 'appender' : 'disabled'),
            'required'  => true,
            'name'      => 'psku',
            'note'      => Mage::helper('wholesaler')->__('Digite o sku e clique em buscar'),
            'after_element_html'    => ($is_new ? '<a href="#" id="search-product" class="append search">buscar</a>' : ''),
        ));

        if (!$is_new)
            $eanfield->setReadonly('readonly');
        
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Nome'),
            'disabled'  => true,
            'name'      => 'name',
            'note'      => Mage::helper('wholesaler')->__('Nome do produto no catálogo Bom Mercatto'),
        ));
        

         $fieldset->addField('weight_sug', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Peso sugerido'),
            'disabled'  => true,
            'name'      => 'weight_sug',
            'note'      => Mage::helper('wholesaler')->__('Peso sugerido do produto.'),
        ));

         $fieldset->addField('weight', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Peso Lojista'),
            'required'  => true,
            'disabled'  => $is_new ? true : false,
            'name'      => 'weight',
            'note'      => Mage::helper('wholesaler')->__('Peso do produto. Usado para cálculo do frete.<br/>Para 300g (gramas), digite 0.3<br/>Para 3kg (quilos), digite 3.00 apenas 3'),
        ));

        $fieldset->addField('qty', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Estoque'),
            'required'  => true,
            'disabled'  => $is_new ? true : false,
            'name'      => 'qty',
            'note'      => Mage::helper('wholesaler')->__('Quantidade de produtos disponíveis para venda')
        ));
        
        $fieldset->addField('price', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Preço'),
            'required'  => true,
            'disabled'  => $is_new ? true : false,
            'name'      => 'price',
            'default'   => 1,
            'note'      => Mage::helper('wholesaler')->__('Preço de venda do produto <br/> Para R$3,00, digite 3.00 ou 3<br/> Para R$ 1000,50, digite 1000.50'),
        ));

        $fieldset->addField('is_in_stock', 'select', array(
            'label'     => Mage::helper('wholesaler')->__('Produto ativo?'),
            'name'      => 'is_in_stock',
            'required'  => true,
            'disabled'  => $is_new ? true : false,
            'note'      => Mage::helper('wholesaler')->__('Exibe o produto para venda'),
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('wholesaler')->__('Yes'),
                ),
 
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('wholesaler')->__('No'),
                ),
            ),
        ));

        

        $preview = '';

        if (!$is_new) {
            $preview = $this->loadPreview();
        }

        $fieldset->addField('extra', 'note', array(
            'text' => "
            <div id=\"product-preview\">$preview</div>
            <div id=\"loading-mask\" style=\"display: none;\">
                <div class=\"loader\" id=\"loading-mask-loader\"><img src=\"" . $this->getSkinUrl('images/ajax-loader-tr.gif') . "\" />" . $this->__('Loading...')."</div>
            </div>
            <script type=\"text/javascript\">
            //<![CDATA[
                var searching = false;

                jq('#newproduct_form .form-list').after(jq('#product-preview'));

                jq('#qty').val(parseFloat(jq('#qty').val()));
                jq('#weight').val(parseFloat(jq('#weight').val())); 
                jq('#price').val(parseFloat(jq('#price').val()));  

                jq('#sku').keypress(function(e){
                    if (e.keyCode == 13) {
                        jq('#search-product').focus().trigger('click');
                    }
                });

                jq('#search-product').click(function(){
                    var zone = jq('#zone').val();
                    var ean_check = jq('#psku').val();
                    var ean_check_size = jq('#psku').val().length;

                    if (searching == true || ean_check_size < 5)
                        return;

                    searching = true;

                    //print loading msg;
                    jq('#loading-mask').show();

                    jq.ajax({
                        type: \"json\",
                        url: \"".$this->getUrl('*/*/ajaxFindProduct')."psku\/\"+ean_check,
                        data: { psku: ean_check, zone: zone }
                    }).done(function(msg) {
                        console.log(msg);

                        jq('#ean_info').html('');
                        msg = jq.parseJSON(msg);

                        jq('#loading-mask').hide();
                        searching = false;


                        if (msg.success == true){
                            jq('#name').val(msg.product_name);
                            jq('#weight_sug').val(msg.product_weight);

                            jq('#weight').val(msg.product_weight).removeAttr('disabled');
                            jq('#qty').val(0).removeAttr('disabled');
                            jq('#price').val(0).removeAttr('disabled');
                            jq('#is_in_stock').val(1).removeAttr('disabled');

                            jq('#product-preview').removeClass('hidden');
                            jq('#product-preview').html(msg.product_preview);
                            previewAccordionJs = new varienAccordion('product-preview', false);
                        }else{
                            jq('#name').val('');
                            jq('#weight_sug').val('');

                            jq('#weight').val(0).attr('disabled', 'disabled');
                            jq('#qty').val(0).attr('disabled', 'disabled');
                            jq('#price').val(0).attr('disabled', 'disabled');
                            jq('#is_in_stock').val(1).attr('disabled', 'disabled');

                            jq('#product-preview').addClass('hidden');
                            jq('#product-preview').html('');
                            alert('Nenhum registro foi encontrado.');
                        }
                         
                    });
                            
                });
            
            //]]>
    
            previewAccordionJs = new varienAccordion('product-preview', false);
        </script>",
            'disabled' => true
        ));

       
        $fieldset = $form->addFieldset('tiered_price', array('legend'=>Mage::helper('wholesaler')->__('Prices')));


        // $fieldset->addField('tier_price', 'text', array(
        //         'name'=>'tier_price',
        //         'class'=>'requried-entry',
        //         'value'=>$product->getData('tier_price')
        // ));

        // $form->getElement('tier_price')->setRenderer(
        //     $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_price_tier')
        // );

        $groupCollection =  Mage::getModel('customer/group')->getCollection();
        $options = "";

        $regex = '/(^|.|\r|\n)(\{{\s*(\w+)\s*}})/';
        foreach($groupCollection as $group){
                $options.="+' <option value={$group->getId()}>{$group->getCustomerGroupCode()}</option>' \r\n";

        }
        $loadPrice = "";
        
        foreach ($this->getValues() as $_item){
          $loadPrice .= "groupPriceControl.addItem('".$_item['website_id']."', '".$_item['cust_group']."', '".sprintf('%.2f', $_item['price'])."', ".(int)!empty($_item['readonly'])."); \n";
        }

        

  $fieldset->addField('extra_group_price_table', 'note', array(
            'class' => 'grid',
            'text' => '

           <table cellspacing="0" class="data border" id="group_prices_table">
                <colgroup><col width="120">
        <col>
        <col width="1">
        </colgroup><thead>
            <tr class="headings">
                <th style="display: none;">Website</th>
                <th>Customer Group</th>
                <th>Price</th>
                <th class="last">Action</th>
            </tr>
        </thead>
        <tbody id="group_price_container"></tbody>
        <tfoot>
            <tr>
                <td style="display: none;"></td>
                <td colspan="4" class="a-right"><button id="id_05aa9d4edb8f847aacf3d086d7bc7a79" title="Add Group Price" type="button" class="scalable add" onclick="return groupPriceControl.addItem()" style=""><span><span><span>Add Group Price</span></span></span></button></td>
            </tr>
        </tfoot>
    </table>
            <script type="text/javascript">
            //<![CDATA[
                jq("#extra_group_price_table").parent().addClass("grid");


            //]]>
            </script>
            ',
             'disabled' => false
                     ));


 // $loadPrice = "";
        $fieldset->addField('extra_group_price', 'note', array(
            'label'     => Mage::helper('wholesaler')->__('Prices'),
            'text' => "

            <script type=\"text/javascript\">
            //<![CDATA[
               
var groupPriceRowTemplate = '<tr>'
    + '<td style=\"display:none\">'
    + '<select class=\" input-text required-entry\" name=\"product[group_price][{{ index }}][website_id]\" id=\"group_price_row_{{ index }}_website\">'
        + '<option value=\"0\">All Websites [BRL]</option>'
        + '</select></td>'
    + '<td><select class=\" input-text custgroup required-entry\" name=\"product[group_price][{{ index }}][cust_group]\" id=\"group_price_row_{{ index }}_cust_group\">'
     ".$options."
        + '</select></td>'
    + '<td><input class=\" input-text required-entry validate-zero-or-greater\" type=\"text\" name=\"product[group_price][{{ index }}][price]\" value=\"{{price}}\" id=\"group_price_row_{{ index }}_price\" /></td>'
    + '<td class=\"last\"><input type=\"hidden\" name=\"product[group_price][{{ index }}][delete]\" class=\"delete\" value=\"\" id=\"group_price_row_{{ index }}_delete\" />'
    + '<button title=\"Delete Group Price\" type=\"button\" class=\"scalable delete icon-btn delete-product-option\" id=\"group_price_row_{{ index }}_delete_button\" onclick=\"return groupPriceControl.deleteItem(event);\">'
    + '<span>Delete</span></button></td>'
    + '</tr>';
var syntax = ".$regex.";
var groupPriceControl = {

    template: new Template(groupPriceRowTemplate, syntax),
    itemsCount: 0,
    addItem : function () {
                var data = {
            website_id: '0',
            group: '32000',
            price: '',
            readOnly: false,
            index: this.itemsCount++

        };



        if(arguments.length >= 3) {
            data.website_id = arguments[0];
            data.group = arguments[1];
            data.price = arguments[2];
        }
        if (arguments.length == 4) {
            data.readOnly = arguments[3];
        }
        
        Element.insert($('group_price_container'), {
            bottom : this.template.evaluate(data)
        });

        $('group_price_row_' + data.index + '_cust_group').value = data.group;
        $('group_price_row_' + data.index + '_website').value    = data.website_id;

        
        if (data.readOnly == '1') {
            ['website', 'cust_group', 'price', 'delete'].each(function(element_suffix) {
                $('group_price_row_' + data.index + '_' + element_suffix).disabled = true;
            });
            $('group_price_row_' + data.index + '_delete_button').hide();
        }

                $('group_price_container').select('input', 'select').each(function(element) {
            Event.observe(element, 'change', element.setHasChanges.bind(element));
        });
            },
    disableElement: function(element) {
        element.disabled = true;
        element.addClassName('disabled');
    },
    deleteItem: function(event) {
        var tr = Event.findElement(event, 'tr');
        if (tr) {
            Element.select(tr, '.delete').each(function(element) {
                element.value='1';
            });
            Element.select(tr, ['input', 'select']).each(function(element) {
                element.hide();
            });
            Element.hide(tr);
            Element.addClassName(tr, 'no-display template');
        }
        return false;
    }
};


".$loadPrice."
//]]>
</script>

            ",
             'disabled' => true
        ));


        if ( Mage::getSingleton('adminhtml/session')->getWholesalerData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getWholesalerData());
            Mage::getSingleton('adminhtml/session')->setWholesalerData(null);
        } elseif ( Mage::registry('wholesaler_data') ) {
            $form->setValues(Mage::registry('wholesaler_data')->getData());
        }
        return parent::_prepareForm();
    }

    protected function loadPreview() {
        $data = Mage::registry('wholesaler_data');

        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('storecode', array('=' => Bm_Cmon::getCatalogCode()));
        $collection->addFieldToFilter('sku', array('=' => $data->getPsku()));

        $previewBlock = Mage::getSingleton('core/layout')->createBlock('catalog/product');
        $previewBlock->setProduct($collection->getFirstItem())->setTemplate('wholesaler/product-preview.phtml');
        
        return $previewBlock->toHtml();
    }

    /* ---- */

    /**
     * Prepare group price values
     *
     * @return array
     */
    public function getValues()
    {
        $product = Mage::registry('wholesaler_data');
        if($product->getId()){

            $product = Mage::getModel('catalog/product')->load($product->getId());
            $groupPrices = $product->getData('group_price');
            return $groupPrices;

        }else
            return array();
     
        
    }
}