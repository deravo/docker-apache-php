define(['jquery', 'alertify', 'vtypes'], function($, alertify, vtypes){
	var hexcase=0;
	function add(x, y) { return ((x&0x7FFFFFFF) + (y&0x7FFFFFFF)) ^ (x&0x80000000) ^ (y&0x80000000); }
	function SHA1hex(num)
	{
		var sHEXChars="0123456789abcdef";
		var str = "";
		for(var j=7;j>=0;j--)
		{
			str += sHEXChars.charAt((num>>(j*4))&0x0F);
		}
		return str;
	}
	function AlignSHA1(sIn)
	{
		var nblk=((sIn.length+8)>>6)+1, blks=new Array(nblk*16);
		for(var i=0;i<nblk*16;i++) blks[i]=0;
		for(i=0;i<sIn.length;i++)
		{
			blks[i>>2]|=sIn.charCodeAt(i)<<(24-(i&3)*8);
		}
		blks[i>>2]|=0x80<<(24-(i&3)*8);
		blks[nblk*16-1]=sIn.length*8;
		return blks;
	}
	function rol(num,cnt)
	{
		return(num<<cnt)|(num>>>(32-cnt));
	}
	function ft(t,b,c,d)
	{
		if(t<20)return(b&c)|((~b)&d);
		if(t<40)return b^c^d;
		if(t<60)return(b&c)|(b&d)|(c&d);
		return b^c^d;
	}
	function kt(t)
	{
		return(t<20)?1518500249:(t<40)?1859775393:
			(t<60)?-1894007588:-899497514;
	}
	function hex_hmac_md5(a,b){return rstr2hex(rstr_hmac_md5(str2rstr_utf8(a),str2rstr_utf8(b)))}
	function rstr_md5(a){return binl2rstr(binl_md5(rstr2binl(a),a.length*8))}
	function rstr_hmac_md5(c,f){var e=rstr2binl(c);if(e.length>16){e=binl_md5(e,c.length*8)}var a=Array(16),d=Array(16);for(var b=0;b<16;b++){a[b]=e[b]^909522486;d[b]=e[b]^1549556828}var g=binl_md5(a.concat(rstr2binl(f)),512+f.length*8);return binl2rstr(binl_md5(d.concat(g),512+128))}
	function rstr2hex(c){try{hexcase}catch(g){hexcase=0}var f=hexcase?"0123456789ABCDEF":"0123456789abcdef";var b="";var a;for(var d=0;d<c.length;d++){a=c.charCodeAt(d);b+=f.charAt((a>>>4)&15)+f.charAt(a&15)}return b}
	function str2rstr_utf8(c){var b="";var d=-1;var a,e;while(++d<c.length){a=c.charCodeAt(d);e=d+1<c.length?c.charCodeAt(d+1):0;if(55296<=a&&a<=56319&&56320<=e&&e<=57343){a=65536+((a&1023)<<10)+(e&1023);d++}if(a<=127){b+=String.fromCharCode(a)}else{if(a<=2047){b+=String.fromCharCode(192|((a>>>6)&31),128|(a&63))}else{if(a<=65535){b+=String.fromCharCode(224|((a>>>12)&15),128|((a>>>6)&63),128|(a&63))}else{if(a<=2097151){b+=String.fromCharCode(240|((a>>>18)&7),128|((a>>>12)&63),128|((a>>>6)&63),128|(a&63))}}}}}return b}
	function rstr2binl(b){var a=Array(b.length>>2);for(var c=0;c<a.length;c++){a[c]=0}for(var c=0;c<b.length*8;c+=8){a[c>>5]|=(b.charCodeAt(c/8)&255)<<(c%32)}return a}
	function binl2rstr(b){var a="";for(var c=0;c<b.length*32;c+=8){a+=String.fromCharCode((b[c>>5]>>>(c%32))&255)}return a}
	function binl_md5(p,k){p[k>>5]|=128<<((k)%32);p[(((k+64)>>>9)<<4)+14]=k;var o=1732584193;var n=-271733879;var m=-1732584194;var l=271733878;for(var g=0;g<p.length;g+=16){var j=o;var h=n;var f=m;var e=l;o=md5_ff(o,n,m,l,p[g+0],7,-680876936);l=md5_ff(l,o,n,m,p[g+1],12,-389564586);m=md5_ff(m,l,o,n,p[g+2],17,606105819);n=md5_ff(n,m,l,o,p[g+3],22,-1044525330);o=md5_ff(o,n,m,l,p[g+4],7,-176418897);l=md5_ff(l,o,n,m,p[g+5],12,1200080426);m=md5_ff(m,l,o,n,p[g+6],17,-1473231341);n=md5_ff(n,m,l,o,p[g+7],22,-45705983);o=md5_ff(o,n,m,l,p[g+8],7,1770035416);l=md5_ff(l,o,n,m,p[g+9],12,-1958414417);m=md5_ff(m,l,o,n,p[g+10],17,-42063);n=md5_ff(n,m,l,o,p[g+11],22,-1990404162);o=md5_ff(o,n,m,l,p[g+12],7,1804603682);l=md5_ff(l,o,n,m,p[g+13],12,-40341101);m=md5_ff(m,l,o,n,p[g+14],17,-1502002290);n=md5_ff(n,m,l,o,p[g+15],22,1236535329);o=md5_gg(o,n,m,l,p[g+1],5,-165796510);l=md5_gg(l,o,n,m,p[g+6],9,-1069501632);m=md5_gg(m,l,o,n,p[g+11],14,643717713);n=md5_gg(n,m,l,o,p[g+0],20,-373897302);o=md5_gg(o,n,m,l,p[g+5],5,-701558691);l=md5_gg(l,o,n,m,p[g+10],9,38016083);m=md5_gg(m,l,o,n,p[g+15],14,-660478335);n=md5_gg(n,m,l,o,p[g+4],20,-405537848);o=md5_gg(o,n,m,l,p[g+9],5,568446438);l=md5_gg(l,o,n,m,p[g+14],9,-1019803690);m=md5_gg(m,l,o,n,p[g+3],14,-187363961);n=md5_gg(n,m,l,o,p[g+8],20,1163531501);o=md5_gg(o,n,m,l,p[g+13],5,-1444681467);l=md5_gg(l,o,n,m,p[g+2],9,-51403784);m=md5_gg(m,l,o,n,p[g+7],14,1735328473);n=md5_gg(n,m,l,o,p[g+12],20,-1926607734);o=md5_hh(o,n,m,l,p[g+5],4,-378558);l=md5_hh(l,o,n,m,p[g+8],11,-2022574463);m=md5_hh(m,l,o,n,p[g+11],16,1839030562);n=md5_hh(n,m,l,o,p[g+14],23,-35309556);o=md5_hh(o,n,m,l,p[g+1],4,-1530992060);l=md5_hh(l,o,n,m,p[g+4],11,1272893353);m=md5_hh(m,l,o,n,p[g+7],16,-155497632);n=md5_hh(n,m,l,o,p[g+10],23,-1094730640);o=md5_hh(o,n,m,l,p[g+13],4,681279174);l=md5_hh(l,o,n,m,p[g+0],11,-358537222);m=md5_hh(m,l,o,n,p[g+3],16,-722521979);n=md5_hh(n,m,l,o,p[g+6],23,76029189);o=md5_hh(o,n,m,l,p[g+9],4,-640364487);l=md5_hh(l,o,n,m,p[g+12],11,-421815835);m=md5_hh(m,l,o,n,p[g+15],16,530742520);n=md5_hh(n,m,l,o,p[g+2],23,-995338651);o=md5_ii(o,n,m,l,p[g+0],6,-198630844);l=md5_ii(l,o,n,m,p[g+7],10,1126891415);m=md5_ii(m,l,o,n,p[g+14],15,-1416354905);n=md5_ii(n,m,l,o,p[g+5],21,-57434055);o=md5_ii(o,n,m,l,p[g+12],6,1700485571);l=md5_ii(l,o,n,m,p[g+3],10,-1894986606);m=md5_ii(m,l,o,n,p[g+10],15,-1051523);n=md5_ii(n,m,l,o,p[g+1],21,-2054922799);o=md5_ii(o,n,m,l,p[g+8],6,1873313359);l=md5_ii(l,o,n,m,p[g+15],10,-30611744);m=md5_ii(m,l,o,n,p[g+6],15,-1560198380);n=md5_ii(n,m,l,o,p[g+13],21,1309151649);o=md5_ii(o,n,m,l,p[g+4],6,-145523070);l=md5_ii(l,o,n,m,p[g+11],10,-1120210379);m=md5_ii(m,l,o,n,p[g+2],15,718787259);n=md5_ii(n,m,l,o,p[g+9],21,-343485551);o=safe_add(o,j);n=safe_add(n,h);m=safe_add(m,f);l=safe_add(l,e)}return Array(o,n,m,l)}
	function md5_cmn(h,e,d,c,g,f){return safe_add(bit_rol(safe_add(safe_add(e,h),safe_add(c,f)),g),d)}
	function md5_ff(g,f,k,j,e,i,h){return md5_cmn((f&k)|((~f)&j),g,f,e,i,h)}
	function md5_gg(g,f,k,j,e,i,h){return md5_cmn((f&j)|(k&(~j)),g,f,e,i,h)}
	function md5_hh(g,f,k,j,e,i,h){return md5_cmn(f^k^j,g,f,e,i,h)}
	function md5_ii(g,f,k,j,e,i,h){return md5_cmn(k^(f|(~j)),g,f,e,i,h)}
	function safe_add(a,d){var c=(a&65535)+(d&65535);var b=(a>>16)+(d>>16)+(c>>16);return(b<<16)|(c&65535)}
	function bit_rol(a,b){return(a<<b)|(a>>>(32-b))}
	/*ba-resize*/
	(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k="setTimeout",j="resize",d=j+"-special-event",b="delay",f="throttleWindow";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);
	/*$.serializeObject*/
	$.fn.serializeObject = function()
	{
		var hasOwnProperty = Object.prototype.hasOwnProperty;
		return this.serializeArray().reduce(function(data, pair) {
			if (!hasOwnProperty.call(data,pair.name))
			{
				data[pair.name]=pair.value;
			}
			return data;
		}, {});
	}

	/*Date format*/
	Date.prototype.Format = function(fmt)
	{
	  var o = {
		"M+" : this.getMonth()+1,                 //月份
		"d+" : this.getDate(),                    //日
		"h+" : this.getHours(),                   //小时
		"m+" : this.getMinutes(),                 //分
		"s+" : this.getSeconds(),                 //秒
		"q+" : Math.floor((this.getMonth()+3)/3), //季度
		"S"  : this.getMilliseconds()             //毫秒
	  };
	  if(/(y+)/.test(fmt))
		fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
	  for(var k in o)
		if(new RegExp("("+ k +")").test(fmt))
	  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
	  return fmt;
	}

// JSON Extension
var useHasOwn = !!{}.hasOwnProperty,
	isDefined = function(v){
		return typeof v !== 'undefined';
	},
	isArray = function(v) {
		return toString.apply(v) === '[object Array]';
	},
	isDate = function(v){
		return toString.apply(v) === '[object Date]';
	},
	isObject = function(v){
		return !!v && Object.prototype.toString.call(v) === '[object Object]';
	},
	isString = function(v){
		return typeof v === 'string';
	},
	isBoolean = function(v){
		return typeof v === 'boolean';
	},
	isNative = function() {
		var useNative = null;
		return function() {
			if (useNative === null) {
				useNative = window.JSON && JSON.toString() == '[object JSON]';
			}
			return useNative;
		};
	}(),
	pad = function(n) {
		return n < 10 ? "0" + n : n;
	},
	doDecode = function(json){
		return eval("(" + json + ")");
	},
	doEncode = function(o){
		if(!isDefined(o) || o === null){
			return "null";
            }else if(isArray(o)){
                return encodeArray(o);
            }else if(isDate(o)){
                return common.JSON.encodeDate(o);
            }else if(isString(o)){
                return encodeString(o);
            }else if(typeof o == "number"){
                return isFinite(o) ? String(o) : "null";
            }else if(isBoolean(o)){
                return String(o);
            }else {
                var a = ["{"], b, i, v;
                for (i in o) {
                    if(!o.getElementsByTagName){
                        if(!useHasOwn || o.hasOwnProperty(i)) {
                            v = o[i];
                            switch (typeof v) {
                            case "undefined":
                            case "function":
                            case "unknown":
                                break;
                            default:
                                if(b){
                                    a.push(',');
                                }
                                a.push(doEncode(i), ":",
                                        v === null ? "null" : doEncode(v));
                                b = true;
                            }
                        }
                    }
                }
                a.push("}");
                return a.join("");
            }
        },
        m = {
            "\b": '\\b',
            "\t": '\\t',
            "\n": '\\n',
            "\f": '\\f',
            "\r": '\\r',
            '"' : '\\"',
            "\\": '\\\\'
        },
        encodeString = function(s){
            if (/["\\\x00-\x1f]/.test(s)) {
                return '"' + s.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                    var c = m[b];
                    if(c){
                        return c;
                    }
                    c = b.charCodeAt();
                    return "\\u00" +
                        Math.floor(c / 16).toString(16) +
                        (c % 16).toString(16);
                }) + '"';
            }
            return '"' + s + '"';
        },
        encodeArray = function(o){
            var a = ["["], b, i, l = o.length, v;
                for (i = 0; i < l; i += 1) {
                    v = o[i];
                    switch (typeof v) {
                        case "undefined":
                        case "function":
                        case "unknown":
                            break;
                        default:
                            if (b) {
                                a.push(',');
                            }
                            a.push(v === null ? "null" : common.JSON.encode(v));
                            b = true;
                    }
                }
                a.push("]");
                return a.join("");
        };

	return {
		JSON : {
			decode : function(json) {
				var dc = isNative() ? JSON.parse : doDecode;
				return dc(json);
			},
			encode : function(o) {
				var ec = isNative() ? JSON.stringify : doEncode;
				return ec(o);
			}
		},
		store : {
			set : function(key, val, expires)
			{
				if ( $.AMUI.store && $.AMUI.store.enabled )
				{
					$.AMUI.store.set(key, val);
				}
				else
				{
					$.AMUI.utils.cookie.set(key, val);
				}
			},
			get : function(key) {
				if ( $.AMUI.store && $.AMUI.store.enabled )
				{
					return $.AMUI.store.get(key);
				}
				else
				{
					return $.AMUI.utils.cookie.get(key);
				}
			}
		},
		parseValidator : function(string)
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
		},

		alertReset : function () {
			try
			{
				alertify.set({
					labels : {
						ok     : "确认",
						cancel : "取消"
					},
					delay : 2000,
					buttonReverse : true,
					buttonFocus   : 'ok'
				});
			}
			catch(e)
			{
				console && console.error('alertify not initialized');
			}
		},

		MD5 : function(a) {if(a=="") return a;return rstr2hex(rstr_md5(str2rstr_utf8(a)))},
		SHA1 : function(sIn, oCase) {
			var x=AlignSHA1(sIn);
			var w=new Array(80);
			var a=1732584193;
			var b=-271733879;
			var c=-1732584194;
			var d=271733878;
			var e=-1009589776;
			for(var i=0;i<x.length;i+=16){
				var olda=a;
				var oldb=b;
				var oldc=c;
				var oldd=d;
				var olde=e;
				for(var j=0;j<80;j++){
					if(j<16)w[j]=x[i+j];
					else w[j]=rol(w[j-3]^w[j-8]^w[j-14]^w[j-16],1);
					t=add(add(rol(a,5),ft(j,b,c,d)),add(add(e,w[j]),kt(j)));
					e=d;
					d=c;
					c=rol(b,30);
					b=a;
					a=t;
				}
				a=add(a,olda);
				b=add(b,oldb);
				c=add(c,oldc);
				d=add(d,oldd);
				e=add(e,olde);
			}
			SHA1Value=SHA1hex(a)+SHA1hex(b)+SHA1hex(c)+SHA1hex(d)+SHA1hex(e);
			if ( oCase )
				return SHA1Value.toUpperCase();
			return SHA1Value;
		},
		loading: {
			show:function(loading_text){
				loading_text = loading_text || "载入中......";
				var _html = [
						'<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">',
						'<div class="am-modal-dialog">',
						'<div class="am-modal-hd">' + loading_text + '</div>',
						'<div class="am-modal-bd">',
						'<span class="am-icon-spinner am-icon-spin"></span>',
						'</div></div></div>'
				].join("");
				$(document.body).append(_html);
				$("#my-modal-loading").modal({
					closeViaDimmer:0
				});
				$.AMUI.progress.start();
			},
			close:function(){
				$("#my-modal-loading").modal('close');
				//~ $("#my-modal-loading").remove();
				$.AMUI.progress.done();
			}
		},
		validField: function(field, retVal) {
			/* 表单域验证 */
			var err;
			if ( field.attr('type') == 'file' || field.attr('type') == 'hidden' ) {return true;}
			var nowVal = field.val();
			var _id = field.attr('id') || field.attr('name');
			/*设置错误信息标题*/
			var _label = field.data('label') || _id;
			/*开始根据规则验证*/
			if ( field.data('validation') )
			{
				/*序列化规则*/
				var _validator = this.parseValidator(field.data('validation'));
				if ( _validator.repetition )
				{
					var _t = $("#" + _validator.repetition );
					var _tVal = _t.val();
					var _tPar = this.parseValidator(_t.data('validation'));
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
						if ( _validator.minValue && nowVal < _validator.minValue ) {err = _label + "不能小于" + _validator.minValue;}
						if ( _validator.maxValue && nowVal > _validator.maxValue )
						{
							err = ( !err ? _label : (err + '且') ) + '不能大于于' + _validator.maxValue;
						}
						if ( !err )
						{
							if ( _validator.pattern )
							{
								_reg = new RegExp(_validator.pattern);
								if ( !_reg.test(nowVal) )
								{
									err = "格式错误：" + ( _validator.patternText || _label );
								}
							}
							else
							{
								if ( _validator.vtype && !vtypes[_validator.vtype](nowVal) )
								{
									if ( !vtypes[_validator.vtype](nowVal) ) { err = '格式错误：' + vtypes[_validator.vtype + 'Text']; }
								}
							}
						}
					}
				}
				if ( retVal == true )
				{
					if ( !err ) { return true; }
					return err;
				} else {
					if ( !!err )
					{
						alertify.error(err)
						return false;
					}
					return true;
				}
			}
			return true;
		},
		URL : {
			getParam: function (m) {
				var sValue = location.search.match(new RegExp("[\?\&]" + m + "=([^\&]*)(\&?)", "i"));
				return sValue ? sValue[1] : sValue;
			},
			setParam: function (url, name, value) {
				var r = url;
				if (r != null && r != 'undefined' && r != "")
				{
					if ( typeof name == 'object' )
					{
						for (p in name )
						{
							var val = encodeURIComponent(name[p]);
							var reg = new RegExp("(^|)" + p + "=([^&]*)(|$)");
							var tmp = p + "=" + val;
							if (url.match(reg) != null)
							{
								r = r.replace(eval(reg), tmp);
							}
							else
							{
								if (r.match("[\?]"))
								{
									r += "&" + tmp;
								}
								else
								{
									r += "?" + tmp;
								}
							}
						}
					}
					else
					{
						value = encodeURIComponent(value);
						var reg = new RegExp("(^|)" + name + "=([^&]*)(|$)");
						var tmp = name + "=" + value;
						if (url.match(reg) != null)
						{
							r = url.replace(eval(reg), tmp);
						}
						else
						{
							if (url.match("[\?]"))
							{
								r = url + "&" + tmp;
							}
							else
							{
								r = url + "?" + tmp;
							}
						}
					}
				}
				return r;
			}
		}
    }
});
