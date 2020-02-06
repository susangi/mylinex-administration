Notifications = {
    showSuccessMsg(msg) {
        $.toast({
            heading: 'Well done!',
            text: msg,
            position:  'bottom-right',
            loaderBg: '#53c63b',
            class: 'jq-toast-primary',
            hideAfter: 3500,
            stack: 6,
            showHideTransition: 'fade'
        });
    },

    showErrorMsg(msg){
        $.toast({
            heading: 'Oh snap!',
            text: msg,
            position: 'bottom-right',
            loaderBg: '#7a5449',
            class: 'jq-toast-danger',
            hideAfter: 3500,
            stack: 6,
            showHideTransition: 'fade'
        });
    },
};
