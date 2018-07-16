rhubarb.vb.create('SocialLoginButtonViewBridge', function() {
    return {

        onSocialUserAuthenticated: function(userInfo){
            var self = this;
            this.raiseServerEvent('attemptSocialLogin', userInfo,
                function(response){
                    self.onSocialLoginSuccess(response);
                },function(){
                    self.onSocialLoginFailure();
                })
        },

        onSocialLoginSuccess: function(response){
            this.raiseClientEvent("SocialLoginSuccessful", response);
        },

        onSocialLoginFailure: function(){
        }
    }
})