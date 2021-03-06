$(function(){
    var $tags = $('input[name^="tags"]');
    var $specs = $('select[name^="spec"]');
    var $spec_table = $('#spec_table');
    var $spec_table_data = $('#spec_table_data');
    var $batch_setting = $('#batch_setting');
    var $ProductPrice = $('#ProductPrice');
    var $batch_set_price = $('#batch_set_price');
    var $batch_set_price_val=$('#batch_set_price_val');
    var $batch_set_stcok=$('#batch_set_stock');
    var $batch_set_stock_val=$('#batch_set_stock_val');
    var $editForm = $('form');
    var hasSelectAttr = [];
    var specData = {};
    var tableData = [];
    var tableHeaders=[];
    var columns=[];
    var tempTableData = [];
    var colWidths = [];
    var specPriceTable;
    var product_before_price = $ProductPrice.val()||0;
    //overwrite test tag exist
    //一个商品下面规格名称不能重复
    $.fn.tagExist = function(val){
        var allTags = [];
        $.each($tags,function(index,item){
            allTags = allTags.concat(getTagsByElement($(item)));
        });
        return ($.inArray(val,allTags)>=0);
    }
    Array.prototype.min = function() {
        return Math.min.apply(null, this);
    };
    function initData(){
        $.each(product_specs,function(index,item){
            var attr_id = item['attr_id'];
            var spec_name = item['name'];
            if(!specData[attr_id]){
                specData[attr_id]=[];
            }
            specData[attr_id].push(spec_name);
        });
    }
    function initView(){
        initData();
        var i=0;
        $.each(specData,function(index,item){
            //console.log(index);
            //console.log(item);
            hasSelectAttr.push(index);
            $($specs[i]).val(index);
            $($tags[i]).val(item.join(','));
            i++;
        });
        $tags.tagsInput({
            'height':'100px',
            'width':'230px',
            'interactive':true,
            'defaultText':'添加规格',
            'onChange':onChangeTag,
            'minChars':2
        });
        //TODO init table
        //table data
        genTable();
        should_init=false;
    }
    //before gen table
    function initTable(){
        tableData = [];
        tableHeaders=[];
        columns=[];
        tempTableData = [];
        $.each($specs,function(index,item){
            var me = $(item);
            var val = me.val();
            var tagElement= me.parent('div').siblings('input');
            var selectedTags = getTagsByElement(tagElement);
            if(val!=0&&selectedTags.length>0){
                var attrName = $('option:selected',me).text();
                tableHeaders.push(attrName);
                columns.push({data:val,readOnly:true});
                tempTableData.push({'key':val,'tags':selectedTags});
            }
        });
        //tableHeaders.concat(['价格','库存']);
        if(tableHeaders.length>0){
            tableHeaders.push('价格');
            tableHeaders.push('库存');
        }
        if(columns.length>0){
            columns.push({data:'price',type:'numeric',format: '0,0.00'});
            columns.push({data:'stock',type:'numeric',format: '0'});
        }
        var tempDataLen = tempTableData.length;
        if(tempDataLen>0){
            if(tempDataLen==1){
                var attr1Id = tempTableData[0]['key'];
                var attr1Tags = tempTableData[0]['tags'];
                $.each(attr1Tags,function(index,tag1){
                    var itemData = {};
                    var key = tag1;
                    var price=product_before_price;
                    var stock=0;
                    if(edit_spec_groups[key]){
                        price = edit_spec_groups[key]['price'];
                        stock = edit_spec_groups[key]['stock'];
                    }
                    itemData[attr1Id]=tag1;
                    itemData['price']=price;
                    itemData['stock']=stock;
                    tableData.push(itemData);
                });
            }else if(tempDataLen==2){
                var attr1Id = tempTableData[0]['key'];
                var attr1Tags = tempTableData[0]['tags'];
                var attr2Id = tempTableData[1]['key'];
                var attr2Tags = tempTableData[1]['tags'];
                $.each(attr1Tags,function(i,tag1){
                    $.each(attr2Tags,function(j,tag2){
                        var itemData = {};
                        var key = tag1+','+tag2;
                        var price=product_before_price;
                        var stock=0;
                        if(edit_spec_groups[key]){
                            price = edit_spec_groups[key]['price'];
                            stock = edit_spec_groups[key]['stock'];
                        }
                        itemData[attr1Id]=tag1;
                        itemData[attr2Id]=tag2;
                        itemData['price']=price;
                        itemData['stock']=stock;
                        tableData.push(itemData);
                    });

                });
            }else{
                var attr1Id = tempTableData[0]['key'];
                var attr1Tags = tempTableData[0]['tags'];
                var attr2Id = tempTableData[1]['key'];
                var attr2Tags = tempTableData[1]['tags'];
                var attr3Id = tempTableData[2]['key'];
                var attr3Tags = tempTableData[2]['tags'];
                $.each(attr1Tags,function(i,tag1){
                    $.each(attr2Tags,function(j,tag2){
                        $.each(attr3Tags,function(k,tag3){
                            var itemData = {};
                            var key = tag1+','+tag2+','+tag3;
                            var price=product_before_price;
                            var stock=0;
                            if(edit_spec_groups[key]){
                                price = edit_spec_groups[key]['price'];
                                stock = edit_spec_groups[key]['stock'];
                            }
                            itemData[attr1Id]=tag1;
                            itemData[attr2Id]=tag2;
                            itemData[attr3Id]=tag3;
                            itemData['price']=price;
                            itemData['stock']=stock;
                            tableData.push(itemData);
                        });

                    });

                });
            }
        }
    }

    function setMinPrice(){
        var data = $spec_table.data('handsontable').getData();
        var priceArray = [];
        $.each(data,function(_,item){
            priceArray.push(parseFloat(item['price']));
        });
        var min_price = priceArray.min();
        if(!isNaN(min_price)){
            if(min_price>0){
                $ProductPrice.val(priceArray.min());
            }
        }
    }
    Handsontable.hooks.add('afterChange', function() {
        setMinPrice();
    });
    $batch_set_price.on('click',function(){
        var price = $batch_set_price_val.val();
        if(!isNaN(price)&&price!=''){
            batch_set_val(price,'price');
            $ProductPrice.val(price);
        }else{
            alert("请输入数字");
        }
    });
    $batch_set_stcok.on('click',function(){
        var stock = $batch_set_stock_val.val();
        if(!isNaN(stock)&&stock!=''){
            batch_set_val(stock,'stock');
        }else{
            alert("请输入数字");
        }
    });
    function batch_set_val(value,filedName){
        $.each(tableData,function(_,item){
            item[filedName]=value;
        });
        specPriceTable = $spec_table.handsontable({
            data: tableData,
            colHeaders: true,
            contextMenu: true,
            mergeCells:true,
            colHeaders:tableHeaders,
            manualColumnResize: true,
            manualRowResize: true,
            colWidths:colWidths,
            columns:columns
        });
    }
    function genTable(){
        initTable();
        if(tableData.length>0){
            $batch_setting.show();
            $ProductPrice.attr('readonly','readonly');
        }else{
            $batch_setting.hide();
            $ProductPrice.removeAttr('readonly');
        }
        //console.log(tableData);
        //var container = document.getElementById('hot');
        colWidths = Array.apply(null,new Array(columns.length)).map(Number.prototype.valueOf,120);
        specPriceTable = $spec_table.handsontable({
            data: tableData,
            colHeaders: true,
            contextMenu: true,
            mergeCells:true,
            colHeaders:tableHeaders,
            manualColumnResize: true,
            manualRowResize: true,
            colWidths:colWidths,
            columns:columns
        });
        setMinPrice();
    }
    var $options = '<option value="0">请选择规格名称</option>';
    $.each(all_product_attrs,function(index,item){
        $options+='<option value="'+item['id']+'">'+item['name']+'</option>';
    });
    $specs.html($options);
    $specs.on('change',function(){
        var me = $(this);
        var before = me.attr('before-value');
        var currVal = me.val();
        if($.inArray(currVal,hasSelectAttr)<0){
            if(currVal!=0){
                hasSelectAttr = removeA(hasSelectAttr,before);
                me.attr('before-value',currVal);
                hasSelectAttr.push(currVal);
                //clean tags
                me.parent('div').siblings('input').importTags('');
                genTable();
            }
        }else{
            var before = me.attr('before-value');
            me.val(before);
            alert("请选择不同的名称");
        }
    }).on('focus',function(){
        var me = $(this);
        me.attr('before-value',me.val());
    });

    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax= arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }

    function getTagsByElement($element){
        var $keywords = $element.siblings(".tagsinput").children(".tag");
        var tags = [];
        for (var i = $keywords.length; i--;) {
            tags.push($($keywords[i]).text().substring(0, $($keywords[i]).text().length -  1).trim());
        }
        /*Then if you only want the unique tags entered:*/
        var uniqueTags = $.unique(tags);
        $element.val(uniqueTags.join(','));
        return uniqueTags;
    }

    $editForm.on('submit',function(){
        //set spec price data
        var tablePostData = $spec_table.data('handsontable').getData();
        $spec_table_data.val(JSON.stringify(tablePostData));
        //set tag val
        $.each($tags,function(index,item){
            var $keywords = $(item).siblings(".tagsinput").children(".tag");
            var tags = [];
            for (var i = $keywords.length; i--;) {
                tags.push($($keywords[i]).text().substring(0, $($keywords[i]).text().length -  1).trim());
            }
            /*Then if you only want the unique tags entered:*/
            var uniqueTags = $.unique(tags);
            $(item).val(uniqueTags.join(','));
        });
        //todo
        return true;
    });

    initView();
    function onChangeTag(input,tag){
        //TODO reset table
        if(!should_init){
            genTable();
        }
    }
});