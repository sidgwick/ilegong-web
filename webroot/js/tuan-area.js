/**
 * Created by shichaopeng on 4/18/15.
 */
var tuanAreas = [
    {"id":"110108","name":"海淀区"},
    {"id":"110105","name":"朝阳区"},
    {"id":"900003","name":"昌平县城"},
    {"id":"900002","name":"西三旗"},
    {"id":"900000","name":"天通苑"},
    {"id":"900001","name":"回龙观"},
    {"id":"110114","name":"昌平区"},
    {"id":"110113","name":"顺义区"},
    {"id":"110101","name":"东城区"},
    {"id":"110102","name":"西城区"},
    {"id":"110106","name":"丰台区"},
    {"id":"110107","name":"石景山区"},
    {"id":"110109","name":"门头沟区"},
    {"id":"110111","name":"房山区"},
    {"id":"110112","name":"通州区"},
    {"id":"110115","name":"大兴区"},
    {"id":"110116","name":"怀柔区"},
    {"id":"110117","name":"平谷区"},
    {"id":"110228","name":"密云区"},
    {"id":"110229","name":"延庆区"},
];
var beijingArea= {
    110101:{
        'name':"东城区"
    },
    110108:{
        'name':"海淀区"
    },
    110102:{
        'name':"西城区"
    },
    110105:{
        'name':"朝阳区"
    },
    110106:{
        'name':"丰台区"
    },
    110114:{
        'name':"昌平区",
        'children_area':{
            900001:{'name':'昌平县城'},
            900002:{'name':'天通苑'},
            900003:{'name':'回龙观'},
            900004:{'name':'北七家镇'},
            900005:{'name':'沙河镇'},
            900006:{'name':'立水桥'},
            900007:{'name':'霍营'}
        }
    },
    110113:{
        'name':"顺义区"
    },
    110115:{
        'name':"大兴区"
    },
    110112:{
        'name':"通州区"
    },
    110107:{
        'name':"石景山区"
    }
};