(function(jQuery){
    jQuery.showPopup = function(options)
    {
        options.windowName = options.windowName ||  'YiiTwitterWithOAuth'; // should not include space for IE
        options.windowOptions = options.windowOptions || 'location=0,status=0,width=800,height=400';
        options.callback = options.callback || function(){ window.location.reload(); };
        var that = this;

        that.popupWindow = window.open(options.path, options.windowName, options.windowOptions);
        that.popupInterval = window.setInterval(function(){
            if (that.popupWindow.closed) {
                window.clearInterval(that.popupInterval);
                options.callback();
            }
        }, 1000);
    };

    $(document).ready(function(){
        $('.twconnect').click(function(){
            $.showPopup({
                path: '/twconnect',
                callback: function(){
                    window.location.reload();
                }
            });
        });
    });

})(jQuery);

