var FuxSwalUtility = {
    success: function (message) {
        return swal({type: 'success', html: message});
    },
    info: function (message, title) {
        return swal({type: 'info', title: title, html: message, showConfirmButton: false, showCloseButton: true});
    },
    error: function (message) {
        return swal({type: 'error', html: message});
    },
    loading: function (message, disableClose) {
        var s = swal({type: 'info', html: message, allowOutsideClick: !disableClose});
        swal.showLoading();
        return s;
    },
    confirm: function (message, confirmText, cancelText, resolveOnly) {
        return new Promise(function (resolve, reject) {
            swal({
                type: 'question',
                html: message,
                showConfirmButton: true, showCancelButton: true, reverseButtons: true,
                confirmButtonText: confirmText ? confirmText : 'Procedi',
                cancelButtonText: cancelText ? cancelText : 'Annulla'
            })
                .then(function (r) {
                    if (r.value) {
                        resolve(true);
                    } else if (r.dismiss === Swal.DismissReason.backdrop || r.dismiss === Swal.DismissReason.close || r.dismiss === Swal.DismissReason.esc) {
                        reject(r.dismiss);
                    } else {
                        resolveOnly ? resolve(false) : reject(false);
                    }
                })
                .catch(_ => reject(false));
        });
    },
    input: function (message, input, confirmText, cancelText, title, defaultValue) {
        return new Promise(function (resolve, reject) {
            swal({
                title: title || '',
                type: 'question',
                input: input,
                inputValue: defaultValue || '',
                text: message,
                showConfirmButton: true, showCancelButton: true, reverseButtons: true,
                confirmButtonText: confirmText ? confirmText : 'Procedi',
                cancelButtonText: cancelText ? cancelText : 'Annulla'
            })
                .then(function (r) {
                    if (r.value) {
                        resolve(r.value);
                    }
                    reject();
                })
                .catch(reject);
        });
    },
    select: function (message, options, confirmText, cancelText, title, defaultValue) {
        return new Promise(function (resolve, reject) {
            swal({
                title: title || '',
                type: 'question',
                input: 'select',
                inputOptions: options,
                inputValue: defaultValue || '',
                text: message,
                showConfirmButton: true, showCancelButton: true, reverseButtons: true,
                confirmButtonText: confirmText ? confirmText : 'Procedi',
                cancelButtonText: cancelText ? cancelText : 'Annulla'
            })
                .then(function (r) {
                    if (r.value) {
                        resolve(r.value);
                    }
                    reject();
                })
                .catch(reject);
        });
    }
};
