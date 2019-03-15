
var index = 0;

function FileManager(path) {
    this.path = path;
    this.preActions = [];
    this.postActions = [];
    this.managerWindow = null;
    this.closeAfterSelect = true;
    this.callbackName = 'FileManagerCallback_' + (++index);
}

FileManager.prototype.registerPreAction = function (callback) {
    if (typeof callback === 'function') {
        this.preActions.push(callback);
    }
    return this;
};

FileManager.prototype.registerPostAction = function (callback) {
    if (typeof callback === 'function') {
        this.postActions.push(callback);
    }
    return this;
};

FileManager.prototype.openFor = function (el, closeAfterSelect, customCallback) {

    var self = this;
    this.el = el;
    this.closeAfterSelect = closeAfterSelect || true;

    for (var i = 0; i < this.preActions.length; i++) {
        if (false === this.preActions[i]()) {
            return;
        }
    }

    window[this.callbackName] = function(file) {
        if (self.el) {
            self.el.value = file.url;
        }
        for (var i = 0; i < self.postActions.length; i++) {
            self.postActions[i](self.el, file);
        }

        if (typeof customCallback === 'function') {
            customCallback(file);
        }

        if (self.closeAfterSelect) {
            self.close();
        }
    };

    this.managerWindow = window.open(
        this.path + (-1 < this.path.indexOf('?') ? '&' : '?') + 'callback=' + this.callbackName,
        'FileManagerPopupWindow' + index,
        'height=450, width=900'
    );

    var popupTick = setInterval(function() {
        if (!self.managerWindow || self.managerWindow.closed) {
            clearInterval(popupTick);
            self.clear();
        }
    }, 1000);

    return this;
};

FileManager.prototype.open = function (callback) {
    return this.openFor(null, true, callback);
};

FileManager.prototype.close = function () {
    if (this.managerWindow) {
        this.managerWindow.close();
    }
    this.clear();
};

FileManager.prototype.clear = function () {
    window[this.callbackName] = null;
    this.managerWindow = null;
    this.preActions = [];
    this.postActions = [];
};
