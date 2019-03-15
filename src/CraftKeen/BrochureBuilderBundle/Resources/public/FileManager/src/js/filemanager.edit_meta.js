elFinder.prototype._options.sound = false;

elFinder.prototype.i18.en.messages['cmdeditmeta'] = 'Edit Meta';
elFinder.prototype._options.commands.push('editmeta');
elFinder.prototype._options.contextmenu.files = ["getfile", "|", "editmeta", "quicklook", "|", "download", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "edit", "rename", "resize", "|", "archive", "extract", "|", "info"];
elFinder.prototype._options.contextmenu.cwd = ['reload', 'back', '|', 'upload', 'mkdir', 'paste', '|', 'view', 'sort', 'colwidth', '|', 'info', '|', 'fullscreen'];
elFinder.prototype._options.uiOptions.toolbar = [
    ['back', 'forward'],
    ['mkdir', 'upload'],
    ['open', 'download', 'getfile'],
    ['info'],
    ['quicklook'],
    ['copy', 'cut', 'paste'],
    ['rm'],
    ['duplicate', 'rename', 'edit', 'resize'],
    ['extract', 'archive'],
    ['search'],
    ['view'],
    ['help']
];

elFinder.prototype.commands.editmeta = function() {

    var fm  = this.fm;

    this.shortcuts = [{
        pattern: 'ctrl+d'
    }];

    this.exec = function(hashes) {
        var self   = this,
            fm     = this.fm,
            dfrd   = $.Deferred()
                     .fail(function(error) {
                         error && fm.error(error);
                     }),
            files = this.files(hashes),
            cnt    = files.length,
            cwd    = fm.cwd().hash,
            tpl    = '<div class="ui-helper-clearfix elfinder-rm-title"><div><span class="elfinder-cwd-icon {class} ui-corner-all"/>{title}<div class="elfinder-editmeta-desc">{desc}</div></div><span class="meta-editor-span">Custom Meta</span><textarea class="elfinder-editmeta-textarea" id="metaEdit" placeholder="{placeholder}">{customMeta}</textarea><span>AODA Meta</span><textarea class="elfinder-editmeta-textarea" id="aodaEdit" placeholder="{placeholderAoda}">{customAoda}</textarea></div>',
            targets, text, f, fname, size, tmb, descs, dialog;

        if (! cnt) {
            return dfrd.reject();
        }

        $.each(files, function(i, file) {
            if (fm.isRoot(file)) {
                return !dfrd.reject(['errRm', file.name, 'errPerm']);
            }
            if (file.locked) {
                return !dfrd.reject(['errLocked', file.name]);
            }
        });

        if (dfrd.state() === 'pending') {
            targets = this.hashes(hashes);
            cnt     = files.length;
            descs   = [];

            if (cnt > 1) {
                if (!$.map(files, function(f) { return f.mime === 'directory' ? 1 : null ; }).length) {
                    size = 0;
                    $.each(files, function(h, f) {
                        if (f.size && f.size !== 'unknown') {
                            var s = parseInt(f.size);
                            if (s >= 0 && size >= 0) {
                                size += s;
                            }
                        } else {
                            size = 'unknown';
                            return false;
                        }
                    });
                    descs.push(fm.i18n('size')+': '+fm.formatSize(size));
                }
                text = [$(tpl.replace('{class}', 'elfinder-cwd-icon-group')
                             .replace('{title}', '<strong>' + fm.i18n('items')+ ': ' + cnt + '</strong>')
                             .replace('{desc}', descs.join('<br>'))
                             .replace('{placeholder}', '~-~')
                             .replace('{placeholderAoda}', '~-~')
                             .replace('{customMeta}', '')
                             .replace('{customAoda}', '')
                )];
            } else {
                f = files[0];
                tmb = fm.tmb(f);
                if (f.size) {
                    descs.push(fm.i18n('size')+': '+fm.formatSize(f.size));
                }
                fname = fm.escape(f.i18 || f.name).replace(/([_.])/g, '&#8203;$1');
                text = [$(tpl.replace('{class}', fm.mime2class(f.mime))
                             .replace('{title}', '<strong>' + fname + '</strong>')
                             .replace('{desc}', descs.join('<br>'))
                             .replace('{placeholder}', '')
                             .replace('{placeholderAoda}', '')
                             .replace('{customMeta}', f.customMeta ? f.customMeta : '')
                             .replace('{customAoda}', f.customAoda ? f.customAoda : '')
                )];
            }

            fm.lockfiles({files : targets});

            dialog = fm.confirm({
                title  : self.title,
                text   : text,
                accept : {
                    label    : 'btnSave',
                    callback : function() {
                        var customMeta = $("#metaEdit").val();
                        var customAoda = $("#aodaEdit").val();
                        fm.request({
                            data: {cmd: 'meta', targets: targets, meta: customMeta, aoda: customAoda},
                            notify: {type: 'rm', cnt: cnt},
                            preventFail: true
                        }).fail(function (error) {
                            dfrd.reject(error);
                        }).done(function (data) {
                            dfrd.done(data);
                        }).always(function (data) {
                            fm.unlockfiles({files: targets});
                            $.each(files, function (h, f) {
                                if (typeof data.updated === 'object' && data.updated.includes(f.hash)) {
                                    f.customMeta = customMeta;
                                    f.customAoda = customAoda;
                                }
                            });
                        });
                    }
                },
                cancel : {
                    label    : 'btnCancel',
                    callback : function() {
                        fm.unlockfiles({files: targets});
                        if (targets.length === 1 && fm.file(targets[0]).phash !== cwd) {
                            fm.select({selected: targets});
                        } else {
                            fm.selectfiles({files: targets});
                        }
                        dfrd.reject();
                    }
                }
            });
            if (tmb) {
                $('<img/>')
                    .on('load', function() { dialog.find('.elfinder-cwd-icon').addClass(tmb.className).css('background-image', "url('"+tmb.url+"')"); })
                    .attr('src', tmb.url);
            }
        }
        return dfrd;

    };
    this.getstate = function() {
        return 0;
    }
};

elFinder.prototype._options.commandsOptions.info.custom = {
    metaData: {
        label: 'Custom Metadata',
        tpl: '<div class="elfinder-info-meta"><span class="elfinder-info-spinner"></span></div>',
        action: function (file, fm, dialog) {
            dialog.find('div.elfinder-info-meta').html(file.customMeta || 'None');
        }
    },
    aodaData: {
        label: 'Custom AODA',
        tpl: '<div class="elfinder-info-aoda"><span class="elfinder-info-spinner"></span></div>',
        action: function (file, fm, dialog) {
            dialog.find('div.elfinder-info-aoda').html(file.customAoda || 'None');
        }
    },
    fileId: {
        label: 'File identifier',
        tpl: '<div class="elfinder-info-file-id"><span class="elfinder-info-spinner"></span></div>',
        action: function(file, fm, dialog) {
            dialog.find('div.elfinder-info-file-id').html(file.idFile || 'None');
        }
    }
};
