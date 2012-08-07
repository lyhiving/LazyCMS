/*!
 * 系统公共文件
 *
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-24 23:32
 */
var scripts = document.getElementsByTagName('script'), script = scripts[ scripts.length - 1 ]; eval(script.innerHTML);
// 取得 static的路径
script.src.replace(/(.+?)\/res\/common(?:\.src)?\.js\??(.*)/i, function(all, path, query) {
    window.ROOT = '/' + path.split('/').slice(3).join('/'); window.VERSION = query ? '?' + query : ''; return all;
});
// URI info
var URI  = window.URI = {};
URI.Host = (('https:' == self.location.protocol) ? 'https://' + self.location.hostname : 'http://' + self.location.hostname);
URI.Path = self.location.href.replace(/\?(.*)/, '').replace(URI.Host, '');
URI.File = URI.Path.split('/').pop();
URI.Path = URI.Path.substr(0, URI.Path.lastIndexOf('/') + 1);
URI.URL  = URI.Host + URI.Path + URI.File;

/**
 * 翻译
 *
 * @param msgid
 * @param context
 */
function __(msgid, context) {
    if (context) {
        context = '__' + context + '__';
        return window.Language && window.Language[context] && window.Language[context][msgid] || msgid;
    } else {
        return window.Language && window.Language[msgid] || msgid;
    }
}
/**
 * js 跳转
 *
 * @param location
 */
function redirect(location, args, anchor) {
    if (location) {
        // 处理url
        location = url(location, args);
        // 锚点
        if (anchor) {
            location += '#' + anchor;
        }
        // 跳转
        (top || window).location.href = location.replace('&amp;', '&');
    }
}
/**
 * url
 *
 * @param url
 * @param args
 */
function url(url, args) {
    // 参数
    if (args && !$.isEmptyObject(args)) {
        if (url.indexOf('?') != -1) {
            url += '&' + $.param(args);
        } else {
            url += '?' + $.param(args);
        }
    }
    return url;
}
/**
 *
 * 判断允许的域名
 *
 * @param domain
 */
function allowDomain(domain) {
    document.crossdomain = document.crossdomain || '*.' + document.location.host;
    var crossdomain = '('+document.crossdomain.replace(/\./g, '\\.').replace(/\*/g, '.*')+')$';
    return new RegExp(crossdomain, 'i').test(domain);
}

/*
 * jQuery extend function
 */
jQuery && (function ($) {
    // 设置全局 AJAX 默认选项
    $.ajaxSetup({
        beforeSend: function(xhr, s){
            // 接管 complete
            if (s.complete) s.oriComplete = s.complete;
            // 被接管的 complete
            s.complete = function(xhr, status) {
                if (s.oriComplete) s.oriComplete.call(this, xhr, status);
            }
            // 接管 success
            if (s.success) {
                s.oriSuccess = s.success;
                // 自定义success
                s.success = function(data, status, xhr) {
                    var success = function(r, status, xhr) {
                        var data;
                        try {
                            data = ($.isArray(r) || $.isPlainObject(r)) ? r : $.parseJSON(r);
                        } catch (e) {
                            data = r;
                        }

                        if (data && data.status) {
                            var code = data.status;
                            switch (code) {
                                // 跳转
                                case 301: case 302:
                                    if (data.location) redirect(data.location);
                                    data = null;
                                    break;
                                // from validate error
                                case 'validate':
                                    $.each(data.errors, function(i) {
                                        $('[name=' + this.id + ']', s.form).attr('data-original-title', this.text).tooltip({trigger: 'focus', placement:'bottom'}).parents('.control-group').addClass('error');
                                    });
                                    data = null;
                                    break;
                                // 返回结果
                                default: data = r; break;
                            }
                        }
                        return data;
                    }
                    var result = success.apply(this, arguments);
                    if (null !== result && s.oriSuccess) {
                        s.oriSuccess.call(this, result, status, xhr);
                    }
                }
            }
        },
        error:function(xhr,status,error) {

        }
    });
    // 取得最大的zIndex
    $.fn.maxIndex = function(){
        var max = 0, index;
        this.each(function(){
            index = $(this).css('z-index');
            index = isNaN(parseInt(index)) ? 0 : index;
            max = Math.max(max, index);
        });
        return max;
    };
})(jQuery);



jQuery && (function ($, window, undefined) {

    var jsc = jQuery.now(),
	jsre = /(\=)\?(&|$)|\?\?/i;

    // Default jsonp settings
    jQuery.ajaxSetup({
        iframe: 'callback',
        iframeCallback: function() {
            return jQuery.expando + "_" + ( jsc++ );
        }
    });

    // Detect, normalize options and install callbacks for jsonp requests
    jQuery.ajaxPrefilter(function( s, originalSettings, jqXHR ) {

        if (s.form && s.form.is('form') && s.iframe !== false) {

            var responseContainer,
                iframeCallback = s.iframeCallback =
                    jQuery.isFunction(s.iframeCallback) ? s.iframeCallback() : s.iframeCallback,
                previous = window[ iframeCallback ],
                url = s.url,
                replace = "$1" + iframeCallback + "$2",
                parse_str = function(str) {
                    var pairs = str.split('&'),params = {}, urldecode = function(s){
                        return decodeURIComponent(s.replace(/\+/g, '%20'));
                    };
                    jQuery.each(pairs,function(i,pair){
                        if ((pair = pair.split('='))[0]) {
                            var key  = urldecode(pair.shift());
                            var value = pair.length > 1 ? pair.join('=') : pair[0];
                            if (value != undefined) value = urldecode(value);

                            if (key in params) {
                                if (!jQuery.isArray(params[key])) {
                                    params[key] = [params[key]];
                                }
                                params[key].push(value);
                            } else {
                                params[key] = value;
                            }
                        }
                    });
                    return params;
                };

                url = url.replace(new RegExp('(' + s.iframe + '=)[^&]+', 'i'), "$1?");
                url = url.replace( jsre, replace );
                if ( s.url === url ) {
                    // Add callback manually
                    url += (/\?/.test( url ) ? "&" : "?") + s.iframe + "=" + iframeCallback;
                }

            s.cache = true;
            s.url = url;

            if (!$('input[name=X-Requested-With]', s.form).is('input')) s.form.append('<input type="hidden" name="X-Requested-With" value="XMLHttpRequest" />');
            //if (!$('input[name=X-Http-Accept]', s.form).is('input')) s.form.append('<input type="hidden" name="X-Http-Accept" value="application/json,text/javascript" />');

            var extra = parse_str(s.data);
            jQuery.each(extra, function(k, v){
                if (!$('input[name=' + k + ']', s.form).is('input')) s.form.append('<input type="hidden" name="' + k + '" value="' + v + '" />');
            });

            // Install callback
            window[ iframeCallback ] = function( response ) {
                responseContainer = [ response ];
            };

            // Clean-up function
            jqXHR.always(function() {
                // Set callback back to previous value
                window[ iframeCallback ] = previous;
                // Call if it was a function and we have a response
                if ( responseContainer && jQuery.isFunction( previous ) ) {
                    window[ iframeCallback ]( responseContainer[ 0 ] );
                }
            });

            // Use data converter to retrieve json after script execution
            s.converters["iframe json"] = function() {
                if ( !responseContainer ) {
                    jQuery.error( iframeCallback + " was not called" );
                }
                return responseContainer[ 0 ];
            };

            // force json dataType
            s.dataTypes[ 0 ] = "json";

            // Delegate to script
            return "iframe";
        }
    });

    // Bind script tag hack transport
    jQuery.ajaxTransport( "iframe", function(s) {

        s.global = false;

        var iframe, browser = jQuery.browser, body = document.body || document.getElementsByTagName( "body" )[0] || document.documentElement;

        return {

            send: function( _, callback ) {
                iframe = $('<iframe async="async" src="' + (browser.msie ? ':' : '') + '//iframe:loaded" style="display: none" name="' + s.iframeCallback + '"></iframe>').appendTo(body);
                iframe.load(function(){
                    // remove iframe
                    iframe.remove();
                    // Dereference the script
                    iframe = undefined;
                    // callback
                    callback( 200, "success" );
                });
                // change action and target
                s.form.attr({action:s.url, target:s.iframeCallback});

            },

            abort: function() {
                if ( iframe ) {
                    // remove iframe
                    iframe.remove();
                    // Dereference the script
                    iframe = undefined;
                }
            }
        };
    });
    /**
     * ajax submit
     *
     * @param callback
     */
    $.fn.ajaxSubmit = function(callback){
        var init, before;
        if(callback && $.isPlainObject(callback)) {
            init = callback.init || function () { };
            before = callback.before || function () { };
            callback = callback.callback || function () { };
        }

        return this.each(function(){
            var self = $(this), button;

            $('button[type=submit]', self).click(function(){
                button = $(this);
            });

            // 初始化函数
            if ($.isFunction(init)) init.call(self, button);

            self.submit(function(){
                // 执行前置函数
                if ($.isFunction(before)) before.call(self, button);

                var url = self.attr('action'); if (!url) url = self.location.href;
                // 禁用按钮
                button.addClass('disabled').attr('disabled',true);
                // 兼容按钮
                var frm_data = [], btn_name = button.attr('name'), btn_val = button.val();
                if (btn_name) {
                    frm_data.push({name:btn_name, value:btn_val});
                }
                // ajax submit
                var submit = function(){
                    $.ajax(url, {
                        form: self, data: frm_data,
                        type: self.attr('method') && self.attr('method').toUpperCase() || 'POST',
                        success: function(data,status,xhr){
                            if ($.isFunction(callback)) callback.call(self, data, status, xhr);
                        },
                        complete: function(xhr, status){
                            button.removeClass('disabled').attr('disabled', false);
                        }
                    });
                };
                // delete confirm
                if (btn_val == 'delete') {
                    if (confirm(__('确定要删除吗？'))) {
                        submit();
                    } else {
                        button.removeClass('disabled').attr('disabled', false);
                        return false;
                    }
                } else {
                    submit();
                }
                return true;
            });
        });
    }
})(jQuery, window);

// preLoadImages
jQuery && (function ($) {
    var cache = [];
    // Arguments are image paths relative to the current page.
    $.preLoadImages = function () {
        var args_len = arguments.length;
        for (var i = args_len; i--;) {
            var cacheImage = document.createElement('img');
            cacheImage.src = arguments[i];
            cache.push(cacheImage);
        }
    };
})(jQuery);

/*
 * 位置任意对齐
 *
 * @author  Lukin <my@lukin.cn>
 */
jQuery && (function ($) {
    /**
     * 对齐常量
     */
    $.align = {
        TL: 'tl',   // 左上
        TC: 'tc',   // 中上
        TR: 'tr',   // 右上
        CL: 'cl',   // 左中
        CC: 'cc',   // 中对齐
        CR: 'cr',   // 右中
        BL: 'bl',   // 左下
        BC: 'bc',   // 中下
        BR: 'br'    // 右下
    };
    /**
     * 位置对齐
     *
     * @example
     *      $(selector).align(points, offset, parent);
     *      $(selector).center(offset, parent);
     * @param points 对齐方式
     *      第一个字符取值 t,b,c ，第二个字符取值 l,r,c，可以表示 9 种取值范围
     *      分别表示 top,bottom,center 与 left,right,center 的两两组合
     * @param offset
     */
    $.fn.align = function(points, offset, parent) {
        parent = parent || window;
        var self = this, wrap, inner, diff, xy = this.offset();
        if (!$.isArray(points)) {
            points = [points, points];
        }
        offset = offset || [0,0];

        var getOffset = function(node, align) {
            var V = align.charAt(0),
                H = align.charAt(1),
                offset, w, h, x, y;

            if (node) {
                offset = node.offset();
                w = node.outerWidth();
                h = node.outerHeight();
            } else {
                offset = {left:$(parent).scrollLeft(), top:$(parent).scrollTop()};
                w = $(parent).width();
                h = $(parent).height();
            }
            x = offset.left;
            y = offset.top;
            if (V === 'c') {
                y += h / 2;
            } else if (V === 'b') {
                y += h;
            }

            if (H === 'c') {
                x += w / 2;
            } else if (H === 'r') {
                x += w;
            }
            return { left: x, top: y };
        }

        wrap  = getOffset(null, points[0]);
        inner = getOffset(self, points[1]);
        diff  = [inner.left - wrap.left, inner.top - wrap.top];

        xy = {
            left: Math.max(xy.left - diff[0] + (+offset[0]), 0),
            top: Math.max(xy.top - diff[1] + (+offset[1]), 0),
            position: $.inArray(this.css('position'), ['absolute','relative','fixed']) == -1 ? 'absolute' : this.css('position')
        };
        return this.css(xy);
    }
    /**
     * 居中
     */
    $.fn.center = function(offset, parent) {
        return this.align('cc', offset, parent);
    }

})(jQuery);

/*
 * JSON  - JSON for jQuery
 *
 * @example
 *      $.toJSON(Object);
 * @author  Lukin <my@lukin.cn>
 */
jQuery && (function ($) {
    $.toJSON = function(o){
        var i, v, s = $.toJSON, t;
        if (o == null) return 'null';
        t = typeof o;
        if (t == 'string') {
            v = '\bb\tt\nn\ff\rr\""\'\'\\\\';
            return '"' + o.replace(/([\u0080-\uFFFF\x00-\x1f\"])/g, function(a, b) {
                i = v.indexOf(b);
                if (i + 1) return '\\' + v.charAt(i + 1);
                a = b.charCodeAt().toString(16);
                return '\\u' + '0000'.substring(a.length) + a;
            }) + '"';
        }
        if (t == 'object') {
            if (o instanceof Array) {
                for (i=0, v = '['; i<o.length; i++) v += (i > 0 ? ',' : '') + s(o[i]);
                return v + ']';
            }
            v = '{';
            for (i in o) v += typeof o[i] != 'function' ? (v.length > 1 ? ',"' : '"') + i + '":' + s(o[i]) : '';
            return v + '}';
        }
        return '' + o;
    };
})(jQuery);

/*
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery && (function ($) {
    /**
     * Create a cookie with the given name and value and other optional parameters.
     *
     * @example $.cookie('the_cookie', 'the_value');
     * @desc Set the value of a cookie.
     * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
     * @desc Create a cookie with all available options.
     * @example $.cookie('the_cookie', 'the_value');
     * @desc Create a session cookie.
     * @example $.cookie('the_cookie', null);
     * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
     *       used when the cookie was set.
     *
     * @param String name The name of the cookie.
     * @param String value The value of the cookie.
     * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
     * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
     *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
     *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
     *                             when the the browser exits.
     * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
     * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
     * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
     *                        require a secure protocol (like HTTPS).
     * @type undefined
     *
     * @name $.cookie
     * @cat Plugins/Cookie
     * @author Klaus Hartl/klaus.hartl@stilbuero.de
     */
    $.cookie = function(name, value, options) {
        if (typeof value != 'undefined') { // name and value given, set cookie
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            // CAUTION: Needed to parenthesize options.path and options.domain
            // in the following expressions, otherwise they evaluate to undefined
            // in the packed version for some reason...
            var path = options.path ? '; path=' + (options.path) : '';
            var domain = options.domain ? '; domain=' + (options.domain) : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        } else { // only name given, get cookie
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
    };
})(jQuery);