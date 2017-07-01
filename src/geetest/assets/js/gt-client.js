window.geetest = {
    option: {},
    onReady: function(func){
        this.option.onReady = func;
        return this;
    },
    onSuccess: function(func){
        this.option.onSuccess = func;
        return this;
    },
    onError: function(func){
        this.option.onError = func;
        return this;
    },
    onClose: function(func){
        this.option.onClose = func;
        return this;
    },
    init: function(){
        var _this = this, option = this.option;
        var handler = function (captchaObj) {
            if (option.type != 'bind') {
                captchaObj.appendTo("#" + option.appendTo);
            }
            captchaObj.onReady(function () {
                if (typeof(option.onReady) === "function") {
                    option.onReady();
                }
            }).onSuccess(function () {
                var result = captchaObj.getValidate();
                if (!result) {
                    return alert('请完成验证');
                }
                document.getElementById(option.inputTo).value = JSON.stringify({
                    challenge: result.geetest_challenge,
                    validate: result.geetest_validate,
                    seccode: result.geetest_seccode
                });
                if (typeof(option.onSuccess) === "function") {
                    option.onSuccess(result);
                }
            }).onError(function() {
                document.getElementById(option.inputTo).value = '';
                captchaObj.reset();
                if (typeof(option.onError) === "function") {
                    option.onError();
                }
            }).onClose(function() {
                if (typeof(option.onClose) === "function") {
                    option.onClose();
                }
            });
            if (option.type == 'bind' && option.bindTo) {
                document.getElementById(option.bindTo).addEventListener('click', function(e) {
                    captchaObj.verify();
                });
            }
            _this.sdk = captchaObj;
        };
        initGeetest(option.options, handler);
        return this;
    },
    verify: function(){
        if (this.sdk) {
            this.sdk.verify();
        }
        return this;
    },
    reset: function(){
        if (this.sdk) {
            this.sdk.reset();
        }
        return this;
    }
};
