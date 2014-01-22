var swfu_array =[];
var ckeditors = {};
// var BASEURL = '/index.php'; // 若项目处于二级目录，此处填写斜线加二级目录名称 ，如 '/subdir'
// var ADMIN_BASEURL = '/manage/index.php'; // 后台项目所在的二级目录
var swfu_array =[];
var last_open_dialog = null; // 记录最后一次打开的dialog
var jqgrid_scrollOffset = null; // 记录jqgrid的滚动条位置； // 触发更新事件时，自动滚动到先前滚动条所在的位置。
var form_submit_flag_for_swfupload = false;  // 表单提交标记，表单提交时，检测文件是否上传完。文件上传完时，自动提交表单
var form_submit_obj_for_swfupload = null; 
// 进行Digg操作,单一选项投票提交
function singleSubmitDigg(model,data_id,question_id,option_id,callback)
{	
	if(!sso.check_userlogin({"callback":singleSubmitDigg,"callback_args":arguments})){		
		return false;
	}
	var postdata = {model:model,data_id:data_id};
	
	postdata['options['+question_id+']['+option_id+']']=1; // question 2,option
															// 4
	
	$.ajax({
		type:'post',
		url:BASEURL+'/appraiseresults/singlesubmit',
		data: postdata,
		success:function(data){
			if(data.error){
				alert(data.error);
				return false;
			}
			if(typeof(callback)=='function'){
				var obj = callback(data);
			}
		},
		dataType:'json'		
	});
	return false;
}
function setDiggNum(data,total)
{
	var id = '#Digg-'+data.model+'-'+data.data_id+'-'+data.question_id+'-'+data.option_id;
	$(id).html('('+data.value+')');
	
	if(total){
		var bar = '#Diggbar-'+data.model+'-'+data.data_id+'-'+data.question_id+'-'+data.option_id;		
		var p = data.value / total * 100;
        $(bar).attr("width", p );
	}
	else{
		// 有bar且没有传入参数total的时候，更新bar的高度，用在singelsubmit提交之后。
		// 有总数是加载时，会根据总数设置bar的高度
		// 适用于多项的单选。如心情，好评差评等。多选时不适用此处理。
		var bar = '#Diggbar-'+data.model+'-'+data.data_id+'-'+data.question_id+'-'+data.option_id;
		if($(bar).size()>0)
		{
		
			var total = 0;
			$("[id^='Digg-"+data.model+"-"+data.data_id+"-']").each(function(){
				var num = $(this).html();
				num = num.replace(/\(|\)/g,'');
				total+=parseInt(num);
			});			
			$("[id^='Diggbar-"+data.model+"-"+data.data_id+"-']").each(function(){
				var numid = this.id.replace(/Diggbar-/,'Digg-');
				
				var num = $('#'+numid).html();
				num = num.replace(/\(|\)/g,'');
				
				var height = parseInt(num);
				var p = height / total * 100;				
		        $('span',this).css("width", p );
		        $(this).next().html(p.toFixed(2)+'%');
			});			
		}
	}
}


/**
 * 将CKEditor编辑器的内容，设置到textarea文本中，和表单提交时一起提交数据。
 * 
 * @return
 */

function setCKEditorVal(form)
{
	$(form).each(function(i){
		$(this).find('textarea').each(function(){ // form .wygiswys
			var oEditor = CKEDITOR.instances[this.id];	
			if(oEditor)	{				
				var content = oEditor.getData();
				$(this).val(content);
			}
		});	
	})
}

/* ajax 操作表单交互,开始 */

// ajaxAction操作成功后，返回调用的函数
var rs_callbacks = {	
	addtofavor:function(request){
		if(request.success){
			var num_selector = '.Stats-'+request.data.model+'-'+request.data.data_id+'-favor_nums';
			var num = $(num_selector).html();
			num = num.replace(/\(|\)/g,'');
			if(num=='') num = 0;				
			num = parseInt(num);num++;
			$(num_selector).html(num);
		}
	},
	loginSucess:function(request){
		if(request.success){
			publishController.close_dialog();
			if(sso.form){
				$(sso.form).trigger("submit");
				sso.form=null;
			}
			if(sso.callback){
				sso.callback.apply(sso.callback,sso.callback_args);
			}
			if(request.userinfo && request.userinfo.session_flash){
				showSuccessMessage(request.success+request.userinfo.session_flash);
			}
		}
	},
	addtoCart:function(request){
		showSuccessMessage(request.success);
	},
	deleteFromCart:function(request){
		// alert('success 123');
	},
	deleteGridRow:function(request,obj){
		$(obj).closest('tr.jqgrow').remove(); // 回调删除当前的行
	},
	reloadGrid:function(request,obj){
		var grid = $(obj).closest('table.jqgrid-list').remove(); // 回调删除当前的行
		var page = grid.jqGrid("getGridParam","page");
		grid.jqGrid("setGridParam",{page:page}).trigger("reloadGrid");
	}
};

// 操作的提交，
/**
 * url，为ajax提交的url。 要求返回为json数据 postdata 为要提交的数据， form 提交的表单
 * callback_func_name,回调函数名，要在rs_callbacks中定义，回调函数的第一个参数为ajax返回的结果 moreags
 * 传给回调函数的更多参数，参数格式可自定义，字符串、数组、对象等
 */
function ajaxAction(url,postdata,form,callback_func_name,moreags){
	if(url.search(/\?/)!=-1){
		url+='&inajax=1';
	}
	else{
		url+='?inajax=1';
	}
	if(form){
		var html = $(':submit',form).val();
		$(':submit',form).data('html',html).val('正在处理...').attr('disabled','disabled'); // 将按钮置为不可提交
	}
	$.ajax({
		// async:true,
		type:'post',
		url: url,
		data: postdata,
		complete:function (XMLHttpRequest, textStatus) {
			if(form){
				//var html = $(':submit',form).data('html');
				//$(':submit',form).val(html).removeAttr('disabled'); // 将按钮置为可提交
				$(':submit',form).val('已成功提交');
			}
		},
		success: function(request){
			if(typeof(callback_func_name)=='function'){
				callback_func_name();
				return;
			}
			else if(callback_func_name && rs_callbacks[callback_func_name]){
				var func = rs_callbacks[callback_func_name];
				if(moreags){
					func(request,moreags);
				}
				else{
					func(request);
				}
				return;
			}
		// tasks is a json object
		// tasks[i] is a json object that convert from a php array .
		// array('dotype','selector','content');
			
			// callback.apply(callback,callback_args);
			// //回调函数,callback_func_name为回调函数的函数名。如rs_callbacks.addtofavor()
					
			if(request.tasks){
				$(request.tasks).each(function(i){
					var task = request.tasks[i];
					
		                	if(task.dotype=="html"){
		                		$(task.selector).html(task.content).show();
		                	}
		                	else if(task.dotype=="value"){
		                		$(task.selector).val(task.content);
		                	}
		                	else if(task.dotype=="append"){
		                		$(task.content).appendTo(task.selector);
		                	}
		                	else if(task.dotype=="dialog"){
		                		$(task.content).appendTo(task.selector);
		                	}
		                	else if(task.dotype=="reload"){
		                		window.location.reload();
		                	}
		                	else if(task.dotype=="callback"){
		                		var callback = null,thisArg=null;
		                		eval( "callback= "+task.callback+";");
		                		eval( "thisArg= "+task.thisArg+";");
		                		var args = [];
		                		for(var i in task.callback_args){
		                			args[args.length]=task.callback_args[i];
		                		}
		                		if(callback){
		                			callback.apply(thisArg,args);
		                		}
		                	}
				});
				return;
			}
			else{
				if(request.success){
					showSuccessMessage(request.success);
				}
				else if(request.error){
					var errorinfo='';
					for(var i in request){
						errorinfo +="<span class='ui-state-error ui-corner-all'><span class='ui-icon ui-icon-alert'></span>"+request[i]+"</span>";
					}
					showErrorMessage(errorinfo);
				}
			}
			
		},
		dataType:"json"
	});
	return false;
}

// ajax 操作,获取html
function ajaxActionHtml(url,selector,callback){
	$.ajax({
		async:true, 
		type:'get',
		url: url,
		success: function (data){
			$(selector).html(data);
			if(typeof(callback)=='function'){
				callback(selector);
			}
			else if(callback){
				eval(callback);
			}
		},
		dataType:"html"
	});
}
// ajax 操作,提交Form
function ajaxeSubmitForm(form,callback_func_name)
{
	setCKEditorVal();
	ajaxAction(form.action,$(form).serialize(),form,callback_func_name);	// 发出请求
	return false;
}


/* ajax 操作表单交互,结束 */


var sso = {
	usercookie:	$.cookie('SAECMS[Auth][User]'),
	form : null, // 登录成功后触发sso.form的提交，提交后置form为null，防止多次调用。登录的各种回调均在users/login模板中
	// callback:'',
	callback:null,   // 在登录成功后，自动调用 sso.callback(callback_args);
	callback_args:null,	
	check_userlogin:function(params){
		this.usercookie = $.cookie('SAECMS[Auth][User]');		
		if(this.usercookie == null || this.usercookie =="" || typeof(this.usercookie) =='undefined'){
			if(params && params.callback){ this.callback = params.callback; }else{ this.callback = ''; }
			if(params && params.form){ this.form = params.form; }else{ this.form = null; }
			if(params && params.callback_args){ this.callback_args = params.callback_args; }else{ this.callback_args = '';}
			
			publishController.open_dialog(BASEURL+'/users/login',{'title':$.jslanguage.needlogin});  // 打开登录窗体
			return false;
		}
		return true;
	}
};


var publishController = {
		_crontroller:'questions',
		dialogid : null,
		overlays : {},
		open_dialog:function(url,options){ // 改成传入一个参数options数组。
											// {title:'title', width: 460}
			var $dialog = this;
			if($dialog.dialogid){
				$('#'+$dialog.dialogid).modal('hide')
			}
			$dialog.dialogid = url.replace(/\/|\.|:|,|\?|=|&/g,'_')+'-ajax—action';	
			
			if($('#'+$dialog.dialogid).size()<1){
				$('<div  class="modal fade" id="'+$dialog.dialogid+'"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3 id="myModalLabel">'+options.title+'</h3></div><div class="modal-body"></div></div></div></div>').appendTo('body');
				
				var obj = $('#'+$dialog.dialogid).find('.modal-body').load(url,{},function(){
					$('.nav-tabs a','#'+$dialog.dialogid).click(function (e) {
				        e.preventDefault();
				        $(this).blur();
				        $(this).tab('show');
				    });  //初始化tab
					$('.nav-tabs a:first','#'+$dialog.dialogid).tab('show');//显示第一个tab
				    $('.dropdown-toggle','#'+$dialog.dialogid).dropdown();
				    $('button','#'+$dialog.dialogid).addClass('btn');
				});
				
			}
			//else{
				$('#'+$dialog.dialogid).modal('show');
			//}
			// modal的left，top距离都是50%，通过设置margin-left,margin-topd的值为modal宽高一半的负数，
			// 左右居中
			if(options.width){
				$('#'+$dialog.dialogid).width(options.width);
				$('#'+$dialog.dialogid).css('margin-left',-options.width/2);
			}
			
			return false;
		},
		load_url:function(url){
			var $dialog = this;
			var re = /^#/;			
			if(re.test(url) || url.substr(0,10).toLowerCase()=='javascript'){
				return false; // 当为锚点或javascript时，忽略动作
			}
			$('#'+$dialog.dialogid).load(url,function(){
				$('#'+$dialog.dialogid).find('a').click(function(){
					$dialog.load_url($(this).attr('href'));
					return false;
				});
				page_loaded();
			});			
		},
		close_dialog:function(){
			if(this.dialogid){
				$('#'+this.dialogid).modal('hide');
			}
		},
		open_html_dialog:function(dialogid){
			this.dialogid = dialogid;
			$('#'+dialogid).modal('show');
		},		
		invite_tabs:{}
};

/**
 * 将模块的数据添加到收藏夹
 * 
 * @param model
 *            模块名
 * @param id
 *            模块数据编号
 * @return
 */
function addtofavor(model,id)
{
	
	var url=BASEURL+'/favorites/add/';	
	var postdata = {'data[Favorite][model]':model,'data[Favorite][data_id]':id};
	if(!sso.check_userlogin({"callback":addtofavor,"callback_args":arguments}))
		return false;
	
	ajaxAction(url,postdata,null,'addtofavor');
	return false;
}
/**
 * 添加到购物车
 * 
 * @param id
 *            产品编号
 * @param num
 *            产品数量
 * @return
 */
function addtoCart(id,num)
{
	var url=BASEURL+'/carts/add';	
	var postdata = {'data[Cart][num]':num,'data[Cart][product_id]':id};
	if(!sso.check_userlogin({"callback":addtoCart,"callback_args":arguments}))
		return false;
	ajaxAction(url,postdata,null,'addtoCart');
	return false;
}
// $(function(){
// sso.check_userlogin(); // test
// });
// 加载页面Digg操作的数据，页面加载完后执行
function loadDiggData()
{
	var models = [];
	var ids = {};
	// 找出出现的所有model和data_id; 模板中形如 <div
	// id="Digg-model-data_id-question_id-option_id" class="ui-dig-num">0</div>
	$('.ui-dig-num').each(function(i){
		// alert(this.id);
		var info = this.id.split('-');		
		if($.inArray(info[1],models) < 0){
			models[models.length] = info[1];
		}
		if(!ids[info[1]]){
			ids[info[1]] = [];
		}
		if($.inArray(info[2],ids[info[1]]) < 0)	{
			var id_length = ids[info[1]].length;
			ids[info[1]][id_length] = info[2];
		}
	});
	// 按不同的model，每个model进行一次ajax操作，取出所有的这个模块的data_id;按model与data_id加载对应的所有dig数据。
	// 有些没有出现的question_id和option_id的记录数据也会返回，在setDiggNum不出现对应元素不影响页面。
	// 省去了在参数中传question_id和option_id，减少ajax请求次数，简化传参是问题处理简单
	for(var i in models){
		var model = models[i];
		var data_id  = ids[models];
		loadModelDataDigg(model,data_id);
	}

	// alert(models);
	// alert(ids[models[0]]);
}
// 加载页面某一数据的Digg操作的数据
function loadModelDataDigg(model,data_id)
{
	$.ajax({
		type:'get',
		url:BASEURL+'/appraiseresults/getdigdata',
		data: {'model':model,'data_id':data_id},
		success:function(data){
			for(var i in data){				
				setDiggNum(data[i]['Appraiseresult']);
			}
		},
		dataType:'json'
		
	});
}

// 加载页面收藏数，评论数，查看数等数据，页面加载完后执行
function loadStatsData()
{
	var models = [];
	var ids = {};
	// 找出出现的所有model和data_id; 模板中形如<span
	// id="Stats-Question-{{$item['id']}}-comment_nums" class="ui-stats-num">
	$('.ui-stats-num').each(function(i){
		// alert(this.id);
		var s = $(this).attr('class');
		s = s.replace(/ui-stats-num/g,'').replace(/ /g,'');
		// alert(s);
		var info = s.split('-');
		if($.inArray(info[1],models) < 0){
			models[models.length] = info[1];
		}
		if(!ids[info[1]]){
			ids[info[1]] = [];
		}
		
		if($.inArray(info[2],ids[info[1]]) < 0)	{
			
			var id_length = ids[info[1]].length;
			ids[info[1]][id_length] = info[2];
		}
	});
	
	// 按不同的model，每个model进行一次ajax操作，取出所有的这个模块的data_id;按model与data_id加载对应的所有dig数据。
	// 有些没有出现的question_id和option_id的记录数据也会返回，在setDiggNum不出现对应元素不影响页面。
	// 省去了在参数中传question_id和option_id，减少ajax请求次数，简化传参是问题处理简单
	for(var i in models){
		var model = models[i];
		var data_id  = ids[model];
		loadModelDataStats(model,data_id);
	}

	// alert(models);
	// alert(ids[models[0]]);
}
// 加载页面某一数据的Digg操作的数据
function loadModelDataStats(model,data_id)
{
	$.ajax({
		type:'get',
		url:BASEURL+'/stats_days/getdata',
		data: {'model':model,'data_id':data_id},
		success:function(data){
			for(var i in data){
				setStatsNum(data[i]['StatsDay']);
			}
		},
		dataType:'json'
		
	});
}
// 设置统计数
function setStatsNum(data)
{
	if(parseInt(data.favor_nums)>0)	{
		var id = '.Stats-'+data.model+'-'+data.data_id+'-favor_nums';
		
		// $(id).html('('+data.favor_nums+')');
		setQuoteNum(id,data.favor_nums);
	}
	if(parseInt(data.comment_nums)>0){
		var id = '.Stats-'+data.model+'-'+data.data_id+'-comment_nums';
		// $(id).html('('+data.comment_nums+')');
		setQuoteNum(id,data.comment_nums);
	}
	
	if(parseInt(data.view_nums)>0){
		var id = '.Stats-'+data.model+'-'+data.data_id+'-view_nums';
		// $(id).html('('+data.comment_nums+')');
		setQuoteNum(id,data.view_nums);
	}
}
// 设置统计的数值
function setQuoteNum(select,value){
	if($(select).size()>0){ // 项在页面上有时，才设置值
		var num = $(select).eq(0).html();
		num = num.replace(/&nbsp;| /g,'');
		num = num.replace(/\(|\)/g,'');
		if(num=='') num = 0;
		num = parseInt(num);
		num = num+parseInt(value);
		// alert(num);
		$(select).html(num);
	}
}
// 心情操作，读这篇文章的心情。设置心情的投票次数及每种心情的百分数
function loadModelDataMood(model,data_id)
{
	$.ajax({
		type:'get',
		url:BASEURL+'/appraiseresults/getdigdata',
		data: {'model':model,'data_id':data_id},
		success:function(data){
			var total= 0;
			for(var i in data){
				// alert(data[i]);
				// alert(data[i]['Appraiseresult']);
				if(data[i]['Appraiseresult'].question_id==3){
					total += parseInt(data[i]['Appraiseresult'].value);
				}
			}
			for(var i in data){
				// alert(data[i]);
				// alert(data[i]['Appraiseresult']);
				setDiggNum(data[i]['Appraiseresult'],total);
				
			}
		},
		dataType:'json'
		
	});
}
/**
 * 加载评论信息
 */
function loadComments(model,id)
{
	$.get(
			BASEURL+'/comments/get_comments_data/'+model+'/'+id,
			{},
			function (comments){
				var current = null;				
				var commentstarget = '.comments-'+model+'-'+id;
				$(commentstarget).html('');
				for(var i=0;i<comments.length;i++){
					current = comments[i].Comment;
					
					var comment_html = '<li><span class="t">'+current.name+'  '+current.created+'</span><p>'+current.body+'</p><li>';
					$(commentstarget).append(comment_html);
				}
			},
			"json"
		);
	
}
/*
 * 加载心情操作
 */
function loadMoodDigg(model,id){
	
	$.get(
			BASEURL+'/appraises/load/3/'+id+'/'+model+'?inajax=1',
			{},
			function (MoodData){
				// alert(MoodData);
				$('#mood-'+model+'-'+id).html(MoodData);
			},
			"html"
		);
}
/*
 * page url hash listen
 */
var page_hash = {
	storedHash: '',
	currentTabHash: '', // The hash that's only stored on a tab switch
	cache: '',
	interval: null,
	listen: true, // listen to hash changes?
	
	 // start listening again
	startListening: function() {
		setTimeout(function() {
			page_hash.listen = true;
		}, 600);
	},
	
	 // stop listening to hash changes
	stopListening: function() {
		page_hash.listen = false;
	},
	
	// check if hash has changed
	checkHashChange: function() {
		
		var locStr = page_hash.currHash();
		if(page_hash.storedHash != locStr) {
			if(page_hash.listen == true) page_hash.refreshToHash(); // //update
																	// was made
																	// by back
																	// button
			page_hash.storedHash = locStr;
		}
		
		if(!page_hash.interval) page_hash.interval = setInterval(page_hash.checkHashChange, 500);
		
	},
	
	// refresh to a certain hash
	refreshToHash: function(locStr) {
		
		if(locStr) var newHash = true;
		locStr = locStr || page_hash.currHash();
		
		var hash_array = locStr.split('&');
		for(var i in hash_array ){
			var pageinfo = hash_array[i].split('=');
			if(pageinfo[0] && pageinfo[1] && pageinfo[0].substr(0,5)=='page_'){
				var portletid = pageinfo[0].replace('page_','');
				var page = pageinfo[1];
				$('.page_'+page,'#'+portletid).trigger('click');
			}
		}
		// if the hash is passed
		if(newHash){ page_hash.updateHash(locStr, true); }

	},
	
	updateHash: function(locStr, ignore) {
		
		if(ignore == true){ page_hash.stopListening(); }
		window.location.hash = locStr;
// if(bookmarklet){ window.parent.location.hash = locStr; }
		if(ignore == true){ 
			page_hash.storedHash = locStr; 
			page_hash.startListening();
		}
		
	},
	
	clean: function(locStr){
		return locStr.replace(/%23/g, "").replace(/[\?#]+/g, "");
	},
	
	currHash: function() {
		return page_hash.clean(window.location.hash);
		// return page_hash.clean(encodeURIComponent(window.location.hash));
	},
	
	currSearch: function() {
		return page_hash.clean(window.location.search);
		// return page_hash.clean(encodeURIComponent(window.location.search));
	},
	
	init: function(){
		page_hash.storedHash = '';
		page_hash.checkHashChange();
	}	
};



$(function(){
	$('.nav li.dropdown,.navbar-nav li.dropdown').hover( function(e){
        $(this).addClass('open');
    },function(){
    	$(this).removeClass('open');
    }).click(function(e){
    	e.stopPropagation();// 阻止事件冒泡，点击链接后，页面直接跳转，屏蔽click的dropdown事件。
    });
	
	// ajax操作时，显示一个进行时状态图标
	$("div#ajaxestatus").ajaxStart(function () {
        $(this).show();
    }).ajaxStop(function () {
        $(this).hide();
    });
	
	//loadDiggData();
	//loadStatsData();	
	//page_hash.init();
});

var stack_custom = {"dir1": "right", "dir2": "down"};
// 显示表单提交成功的信息
function showSuccessMessage(text)
{
	alert(text);
//	$.jGrowl(text, { 
//		theme: 'success'
//	});
	return true;
}
// 显示错误信息

function showErrorMessage(text)
{
	alert(text);
//	$.jGrowl(text, { 
//		theme: 'error' // danger
//	});
	return true;
}
/* ================form validate====================== */
/* jquery tools validate , and bootstrap css */
function find_container(input) {
    return input.parent().parent();
}
function remove_validation_markup(input) {
    var cont = find_container(input);
    cont.removeClass('error success warning');
    $('.help-inline.error, .help-inline.success, .help-inline.warning',cont).remove();
}
function add_validation_markup(input, cls, caption) {
    var cont = find_container(input);
    cont.addClass(cls);
    input.addClass(cls);        
    if (caption) {
        var msg = $('<span class="help-inline"/>');
        msg.addClass(cls);
        msg.text(caption);
        input.after(msg);
    }
}
function remove_all_validation_markup(form) {
    $('.help-inline.error, .help-inline.success, .help-inline.warning',form).remove(); 
    $('.error, .success, .warning', form).removeClass('error success warning');
}

/* ================form validate====================== */