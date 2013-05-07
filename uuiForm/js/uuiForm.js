/**
 * 组件

 * @module uuiForm
 * @authoer zhengxin@sogou ufo team
 **/
(function($) {
    /**
     * 表单校验组件
     * 需要在表单元素上手工制定校验类型
     * 可以使用内置或自定义类型，在表单上声明 uui-type="{type}"既可以
     * 内置type包括:
     * * require  必填项
     * * num 数字
     * * cellphone 手机号码
     * * email 邮箱
     * * min(10) 至少输入字数，括号内为最少的值
     * * max(10) 最多输入字数，括号内为最多的值
     * * range(5,10) 输入字数范围
     * 自定义类型也可以有传入值，依序附加在参数里
     * 也可以在表单上制定自定义正则表达式 uui-reg="\d+"
     * @class $.fn.uuiForm
     * @constructor
     * @param {jQuery} $this 需要校验的表单元素
     * @param {Object} options 配置
     * @param {String} options.type 表单校验的时机，取值为submit|blur，默认submit
     * @param {Function} options.onsinglefail 单个表单项表单校验不通过时触发，接受两个参数，$el为当前出错的表单项,name为出错对应的错误类型
     * @param {Function} options.onsinglesuccess 单个表单项校验通过时触发，接受一个参数，$el为当前通过表单项
     * @param {Function} options.onformfail 表单提交校验失败时触发，如需阻止默认提交行为，需要return false
     * @param {Function} options.onformsuccess 表单提交校验成功时触发，如需阻止默认提交行为，需要return false
     * @example $('.uuiForm').uuiForm({onformsuccess:function(){alert(1)}})
     * */
    // default setting
    var _options = {
        type:'submit',
        onsinglefail:function(){},
        onsinglesuccess:function(){},
        onformfail:function(){
            return false;
        },
        onformsuccess:function(){
            return false;
        }
    };
    function uuiForm($this, options) {
        var me = this;
        me._dom = $this;
        // extend default setting
        var opt = me.options = $.extend({}, _options);
        me.guid = $.UUIBase.guid();
        me.update(options || {});

        me._bindEvent();
    };
    // 如果不需要，可以删除
    uuiForm.prototype = {
        /**
         * 更新实例实现，请通过$('.uuiForm').uuiForm({xxxx})调用

         * @method update
         * @param {Object} options 参数配置
         * @example $('.uuiForm').uuiForm().excUUICMD('update', {enable:1 }) = $('.uuiForm').uuiForm({enable: 1});
         * */
        update: function(options) {
            this.options = $.extend(this.options, options);
        },
        /*
        \/**
         * 实例内部自我销毁的接口，可以不实现，如未实现，destroy操作会被定为到继承的medestroy上，但是不能销毁和dom的绑定，不建议调用，请使用$('.uuiForm').uuiForm({destroy: 1})

         * @method destroy
         * @param {Object} options 参数配置
         * @example $('.uuiForm').uuiForm().excUUICMD('destroy');
         * *\/
        destroy: function(options) {
            // 组件自有的特殊的自我销毁逻辑
        }
        */
        /**
         * 默认类型
         */
        _types: {
            num: function(value){
                return !value.length || /^\d+$/.test(value);
            },
            cellphone: function(value){
                return !value.length || /^1\d{10}$/.test(value);
            },
            require: function(value){
                return value.length;
            },
            email: function(value){
                return !value.length || /^(\w)+(\.\w+)*@([\w_\-])+((\.\w+)+)$/.test(value);
            },
            min:function(value , min){
                return !value.length|| value.length >= min;
            },
            max: function(value , max){
                return !value.length || value.length <= max;
            },
            range: function(value , min , max){
                return !value.length || value.length <= max && value.length >= min;
            }
        },

        _bindEvent: function(){
            var me = this;
            var $el = me._dom;
            $el.on('submit' , function(){
                var form = $(this);
                var result = true;

                var els = form.find(':input');

                els.each(function(idx,el){
                    result = me._validate($(el)) && result;
                });

                return (result? me.options.onformsuccess() : me.options.onformfail());
            });

            if( me.options.type == 'blur' ){
                $el.on('blur' , ':input' , function(){
                    return me._validate($(this));
                });
            }
        },

        _validate: function($el){
            var uuitype = $el.attr('uui-type'),
                uuireg = $el.attr('uui-reg'),
                me = this;
            if( !uuitype && !uuireg )
                return true;
            
            var validFuncs = [] ;
            
            if( uuireg ){
                validFuncs.push([function(value){
                    return new RegExp(uuireg , 'g').test(value);
                } , []]);
            }
            if( uuitype ){
                uuitype = uuitype.split(' ');
                for( var i=0,l=uuitype.length; i<l; i++ ){
                    var stype = uuitype[i];
                    var validFunc , params = [];
                    var pattern = /^(\w+)\((.*)\)$/g ,
                        result;
                    result=pattern.exec(stype);
                    if( result ){
                        validFunc = me._types[result[1]];
                        params = result[2].split(',');
                    }else{
                        validFunc = me._types[stype];
                    }
                    validFuncs.push([ validFunc , params , stype ]);
                }
            }
            
            for( var i=0,l=validFuncs.length; i<l ;i++ ){
                var validFunc = validFuncs[i][0],
                    params = validFuncs[i][1],
                    name = validFuncs[i][2];
                params.unshift($el.val());
                if( !validFunc )
                    continue;
                var validresult = validFunc.apply(null, params);
                validresult ? me.options.onsinglesuccess($el , name) : me.options.onsinglefail($el , name);
                if( !validresult )
                    break;
            }

            return validresult;
        }
    };

    /**
     * 添加校验规则
     * @param {String} name 添加的规则的名字
     * @param {Function} func 添加的规则的校验方法，方法第一个参数为表单的值，其余为校验方法声明的变量，返回值true表示校验通过
     * @example $.uuiForm.addType('custom' , function(){});
     */
    uuiForm.addType = function(name,func){
        uuiForm.prototype._types[name] = func;
    };

    $.UUIBase.create('uuiForm', uuiForm);
    // 创建css
    $($.UUIBase.init);
})(jQuery);
