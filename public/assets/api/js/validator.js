//~ 表单验证规则
vtypes = {
    telephone: function(v)
    {
        //~ return /^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$/.test(v);
        return /(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/.test(v);
    },
    telephoneText: '无效的电话号码',
    mobile: function(v) { return /^(1[3-9])[0-9]{9}$/.test(v); },
    mobileText: '无效的手机号码',
    IPAddress:  function(v) { return /^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/.test(v); },
    IPAddressText: '无效的IPv4地址',
    normal: function(v) { return /^[a-zA-Z0-9\u4E00-\uFA29_\-\,\.]+$/.test(v); },
    normalText: '除文字和以下四个英文标点符号_-,.外，不允许包含其他特殊字符',
    idcard: function(v) {
        //~ Simple validator, it won't upgrade the old id number (15 length) to new id number, also, it doesn't check the birthday and the last check bit
        //~ var aCity={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"}
        return (/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/.test(v) || /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x)$/ig.test(v));
    },
    idcardText: '无效的身份证号',
    alphanum: function(v) { return /^[a-zA-Z0-9_]+$/.test(v); },
    alphanumText: '仅允许填写英文字母、数字和下划线',
    accountName: function(v) {
        if ( ! /^[a-zA-Z][a-zA-Z0-9_]{5,19}$/.test(v) )
        {
            return false
        } else {
            if ( v.split('_').length > 2 || (v.length - 1) == v.indexOf('_') ) { return false; }
        }
        return true;
    },
    accountNameText: '用户名是以字母开头的由６～２０字母和数字组成的字符串',
    editID: function(v) { return /^[1-9][0-9]+?$/.test(v); },
    editIDText : '信息ID不正确',
    email: function(v) { return /^(\w+)([\-+.][\w]+)*@(\w[\-\w]*\.){1,5}([A-Za-z]){2,6}$/.test(v); },
    emailText: '无效的电子邮件',
    url: function(v) { return /(((^https?)|(^ftp)):\/\/([\-\w]+\.)+\w{2,3}(\/[%\-\w]+(\.\w{2,})?)*(([\w\-\.\?\\\/+@&#;`~=%!]*)(\.\w{2,})?)*\/?)/i.test(v); },
    urlText: '无效的网页地址',
    ascii: function(v) { return !/[\u4E00-\uFA29]+/.test(v); },
    asciiText: '仅允许半角字符',
    areacode: function(v) { return /^[1-9][\d]{5}$/.test(v); },
    areacodeText: '无效的区域代码',
    realName: function(v) {return /^[\u4E00-\uFA29]{2,6}$/.test(v);},
    realNameText: '无效的真实姓名',
    cnWord : function(v) {return /^[\u4E00-\uFA29]$/.test(v);},
    cnWordText : '无效的汉字输入',
    eLicence : function(v) {return /^[1-9]\d{14}$/.test(v);},
    eLicenceText : '无效的经营许可证编号',
    vLicence : function(v) {return /^[1-9]\d{11}$/.test(v);},
    vLicenceText : '无效的道路运输经营许可证号',
    eOrganization : function(v) {return /^[1-9\w][\d\w]{7}-[\d\w]$/.test(v);},
    eOrganizationText : '无效的组织机构代码证编号',
    num : function(v) {return /^[1-9][\d]+$/.test(v);},
    numText: '无效的数字格式',
    decimal: function(v) {return /^[\d]+(\.[\d]+)?$/.test(v);},
    decimalText: '无效的数字格式',
    plateNumber: function(v) {return /^[\u4e00-\u9fa5]{1}[A-Z]{1}[A-Z_0-9]{5}$/.test(v);},
    plateNumberText: '无效的车牌号格式',
    fulldate: function(v) {return Date.parseDate(v, 'Y-m-d', true);},
    fulldateText: '无效的日期'
}

//~ 表单验证
var validForm = function(callback) {
    if (event && event.preventDefault)
    {
        event.preventDefault();
    } else {
        window.event.returnValue = false;
    }
    $(this).unbind('submit');
    var err = [], i = 0, params = {};

    $(this).find("input, textarea, select").each(function(n) {
        //~ 过滤文件上传组件
        if ($(this).attr('type') == 'file') { return; }
        //~ 获取当前组件值
        var nowVal = $(this).val();
        //~ 获取提交参数名
        var _id = $(this).attr('id') || $(this).attr('name');
        //~ 设置错误信息标题
        var _label = $(this).data('label') || _id;

        //~ 隐藏域直接赋值后返回
        if ( $(this).attr('type') == 'hidden' ) { params[_id] = nowVal; return; }

        //~ 开始根据规则验证
        if ( $(this).data('validation') )
        {
            //~ 序列化规则
            var _validator = parseValidator($(this).data('validation'));
            if ( _validator.repetition )
            {
                var _t = $("#" + _validator.repetition );
                var _tVal = _t.val();
                var _tPar = parseValidator(_t.data('validation'));
                if ( !_tPar.allowBlank || !!_tVal || !!nowVal)
                {
                    if ( nowVal != _tVal )
                    {
                        err[i] = ( !!_validator.text ) ? _validator.text : '与对比目标值不一致';
                    }
                }
            } else {
                if ( ! nowVal )
                {
                    if ( ! _validator.allowBlank )
                    {
                        err[i] = _label + '必须填写';
                    }
                }
                else
                {
                    if ( _validator.minLength && nowVal.length < _validator.minLength ) { err[i] = _label + '不能少于' + _validator.minLength + '字符'; }
                    if ( _validator.maxLength && nowVal.length > _validator.maxLength )
                    {
                        err[i] = ( !err[i] ? _label : (err[i] + '且') ) + '不能多于' + _validator.maxLength + '字符';
                    }
                    if ( !err[i] )
                    {
                        if ( _validator.vtype )
                        {
                            if ( ! vtypes[_validator.vtype](nowVal) ) { err[i] = '格式错误：' + vtypes[_validator.vtype + 'Text']; }
                        }
                    }
                }
            }
        }
        if ( !!err[i] )
        {
            $(this).parent().addClass('has-error').removeClass('has-success');
            i++;
        } else {
            params[_id] = nowVal;
            $(this).parent().removeClass('has-error').addClass('has-success');
        }
    });

    if (err.length > 0)
    {
        msg.alert({title:'出错啦', text:"<ul><li class='text-lg'>" + err.join("</li><li class='text-lg'>") + "</li></ol>"});
        //~ window.modalTrigger.show({icon:'info-sign', title:'出错啦', position:'center', custom: "<ul><li class='text-lg'>" + err.join("</li><li class='text-lg'>") + "</li></ol>", keyboard:false});
    }
    else
    {
        var _post_url = this.action;
        var _return_url = false;
        var _frm_obj = $(this);
        $.post(_post_url, $(this).serialize(), function(result)
        {
            try {
                if ( typeof result.message == 'object' )
                {
                    result.message = result.message.join("<br />");
                } else {
                    result.message = result.message.replace(/\n/ig,"<br />");
                }
                if (result.code == 2000)
                {
                    alert(result.message);
                    window.location.href = result.data;
                } else {
                    alert(result.message);
                }
            } catch(e) {
                //~ console.log(result);
            }
        }, 'json');
    }
    $(this).bind('submit', validForm);
};

//~ 表单域验证
var validField = function(field, showError) {
    var err;
    if ( field.attr('type') == 'file' || field.attr('type') == 'hidden' ) {return true;}
    var nowVal = field.val();
    var _id = field.attr('id') || field.attr('name');
    //~ 设置错误信息标题
    var _label = field.data('label') || _id;
    //~ 开始根据规则验证
    if ( field.data('validation') )
    {
        //~ 序列化规则
        var _validator = parseValidator(field.data('validation'));
        if ( _validator.repetition )
        {
            var _t = $("#" + _validator.repetition );
            var _tVal = _t.val();
            var _tPar = parseValidator(_t.data('validation'));
            if ( !_tPar.allowBlank || !!_tVal || !!nowVal)
            {
                if ( nowVal != _tVal )
                {
                    err = ( _validator.text ) ? _validator.text : '与对比目标值不一致';
                }
            }
        } else {
            if ( ! nowVal )
            {
                if ( ! _validator.allowBlank )
                {
                    err = _label + '必须填写';
                }
            }
            else
            {
                if ( _validator.minLength && nowVal.length < _validator.minLength ) { err = _label + '不能少于' + _validator.minLength + '字符'; }
                if ( _validator.maxLength && nowVal.length > _validator.maxLength )
                {
                    err = ( !err ? _label : (err + '且') ) + '不能多于' + _validator.maxLength + '字符';
                }
                if ( !err )
                {
                    if ( _validator.vtype )
                    {
                        if ( !vtypes[_validator.vtype](nowVal) ) { err = '格式错误：' + vtypes[_validator.vtype + 'Text']; }
                    }
                }
            }
        }
        if ( showError == true )
        {
            var _p = $(this).parent().parent();
            if ( !err )
            {
                _p.find('span').first().remove();
                _p.removeClass('am-form-error').addClass('am-form-success').append($('<span class="am-icon-check"></span>'));
            } else {
                $(this).parent().addClass('has-error');
                //~ _p.find('span').first().remove();
                _p.removeClass('am-form-success').addClass('am-form-error').append($('<span class="am-icon-times"></span>'));
            }
        } else {
            if ( !err ) { return true; }
            return err;
        }
    }
};


var parseValidator = function(string)
{
    if ($.isPlainObject(string)) {
        return string;
    }
    var start = (string ? string.indexOf('{') : -1);
    var options = {};
    if (start != -1) {
        try {
            options = (new Function('',
                'var json = ' + string.substr(start) +
                '; return JSON.parse(JSON.stringify(json));'))();
        } catch (e) {}
    }
    return options;
};