elFinder.prototype.i18.en.messages['btnMeta'] = 'Meta';
elFinder.prototype._options.commandsOptions.search.incsearch = {
    enable : false, // disabled by default
    minlen : 1,     // minimum number of characters
    wait   : 500    // wait milliseconds
};

/**
 * override search function
 */
elFinder.prototype.commands.search = function() {
    this.title          = 'Find files';
    this.options        = {ui : 'searchbutton'}
    this.alwaysEnabled  = true;
    this.updateOnSelect = false;

    /**
     * Return command status.
     * Search does not support old api.
     *
     * @return Number
     **/
    this.getstate = function() {
        return 0;
    }

    /**
     * Send search request to backend.
     *
     * @param q
     * @param target
     * @param mime
     * @param meta
     */
    this.exec = function(q, target, mime, meta) {
        var fm = this.fm,
            reqDef = [],
            onlyMimes = fm.options.onlyMimes,
            phash;

        if (typeof q == 'string' && q) {
            if (typeof target == 'object') {
                mime = target.mime || '';
                target = target.target || '';
            }
            target = target? target : '';
            if (mime) {
                mime = $.trim(mime).replace(',', ' ').split(' ');
                if (onlyMimes.length) {
                    mime = $.map(mime, function(m){
                        m = $.trim(m);
                        return m && ($.inArray(m, onlyMimes) !== -1
                            || $.map(onlyMimes, function(om) { return m.indexOf(om) === 0? true : null }).length
                        )? m : null
                    });
                }
            } else {
                mime = [].concat(onlyMimes);
            }

            fm.trigger('searchstart', {query : q, target : target, mimes : mime, meta: meta});

            if (! onlyMimes.length || mime.length) {
                if (target === '' && fm.api >= 2.1) {
                    $.each(fm.roots, function(id, hash) {
                        reqDef.push(fm.request({
                            data   : {cmd : 'search', q : q, target : hash, mimes : mime, meta: meta},
                            notify : {type : 'search', cnt : 1, hideCnt : (reqDef.length? false : true)},
                            cancel : true,
                            preventDone : true
                        }));
                    });
                } else {
                    reqDef.push(fm.request({
                        data   : {cmd : 'search', q : q, target : target, mimes : mime, meta: meta},
                        notify : {type : 'search', cnt : 1, hideCnt : true},
                        cancel : true,
                        preventDone : true
                    }));
                    if (target !== '' && fm.api >= 2.1 && Object.keys(fm.leafRoots).length) {
                        $.each(fm.leafRoots, function(hash, roots) {
                            phash = hash;
                            while(phash) {
                                if (target === phash) {
                                    $.each(roots, function() {
                                        reqDef.push(fm.request({
                                            data   : {cmd : 'search', q : q, target : this, mimes : mime, meta: meta},
                                            notify : {type : 'search', cnt : 1, hideCnt : false},
                                            cancel : true,
                                            preventDone : true
                                        }));
                                    });
                                }
                                phash = (fm.file(phash) || {}).phash;
                            }
                        });
                    }
                }
            } else {
                reqDef = [$.Deferred().resolve({files: []})];
            }

            fm.searchStatus.mixed = (reqDef.length > 1);

            return $.when.apply($, reqDef).done(function(data) {
                var argLen = arguments.length,
                    i;

                data.warning && fm.error(data.warning);

                if (argLen > 1) {
                    data.files = (data.files || []);
                    for(i = 1; i < argLen; i++) {
                        arguments[i].warning && fm.error(arguments[i].warning);

                        if (arguments[i].files) {
                            data.files.push.apply(data.files, arguments[i].files);
                        }
                    }
                }

                fm.lazy(function() {
                    fm.trigger('search', data);
                }).then(function() {
                    // fire event with command name + 'done'
                    return fm.lazy(function() {
                        fm.trigger('searchdone');
                    });
                }).then(function() {
                    // force update content
                    data.sync && fm.sync();
                });
            });
        }
        fm.getUI('toolbar').find('.'+fm.res('class', 'searchbtn')+' :text').focus();
        return $.Deferred().reject();
    }

};

/**
 *
 * Override search toolbar
 * @param cmd
 */
$.fn.elfindersearchbutton = function(cmd) {
    return this.each(function() {
        var result = false,
            fm     = cmd.fm,
            isopts = cmd.options.incsearch || { enable: false },
            id     = function(name){return fm.namespace + name},
            toolbar= fm.getUI('toolbar'),
            btnCls = fm.res('class', 'searchbtn'),
            button = $(this).hide().addClass('ui-widget-content elfinder-button '+btnCls),
            search = function() {
                input.data('inctm') && clearTimeout(input.data('inctm'));
                opts && opts.slideUp();
                var val = $.trim(input.val()),
                    from = !$('#' + id('SearchFromAll')).prop('checked'),
                    mime = $('#' + id('SearchMime')).prop('checked'),
                    meta = $('#' + id('SearchMeta')).prop('checked');
                if (from) {
                    if ($('#' + id('SearchFromVol')).prop('checked')) {
                        from = fm.root(fm.cwd().hash);
                    } else {
                        from = fm.cwd().hash;
                    }
                }
                if (mime && val) {
                    mime = val;
                    meta = '';
                    val = '.';
                }
                if (meta && val) {
                    meta = val;
                    mime = '';
                    val = '.';
                }
                if (!meta) {
                    meta = '';
                }
                if (val) {
                    cmd.exec(val, from, mime, meta).done(function() {
                        result = true;
                        input.focus();
                    }).fail(function() {
                        abort();
                    });

                } else {
                    fm.trigger('searchend');
                }
            },
            abort = function() {
                input.data('inctm') && clearTimeout(input.data('inctm'));
                input.val('').blur();
                if (result || incVal) {
                    result = false;
                    incVal = '';
                    fm.lazy(function() {
                        fm.trigger('searchend');
                    });
                }
            },
            incVal = '',
            input  = $('<input type="text" size="42"/>')
                .on('focus', function() {
                    incVal = '';
                    opts && opts.slideDown();
                })
                .on('blur', function(){
                    if (opts) {
                        if (!opts.data('infocus')) {
                            opts.slideUp();
                        } else {
                            opts.data('infocus', false);
                        }
                    }
                })
                .appendTo(button)
                // to avoid fm shortcuts on arrows
                .on('keypress', function(e) {
                    e.stopPropagation();
                })
                .on('keydown', function(e) {
                    e.stopPropagation();

                    e.keyCode == $.ui.keyCode.ENTER && search();

                    if (e.keyCode == $.ui.keyCode.ESCAPE) {
                        e.preventDefault();
                        abort();
                    }
                }),
            opts;

        if (isopts.enable) {
            isopts.minlen = isopts.minlen || 2;
            isopts.wait = isopts.wait || 500;
            input
                .attr('title', fm.i18n('incSearchOnly'))
                .on('compositionstart', function() {
                    input.data('composing', true);
                })
                .on('compositionend', function() {
                    input.removeData('composing');
                    input.trigger('input'); // for IE, edge
                })
                .on('input', function() {
                    if (! input.data('composing')) {
                        input.data('inctm') && clearTimeout(input.data('inctm'));
                        input.data('inctm', setTimeout(function() {
                            var val = input.val();
                            if (val.length === 0 || val.length >= isopts.minlen) {
                                (incVal !== val) && fm.trigger('incsearchstart', { query: val });
                                incVal = val;
                                if (val === '' && fm.searchStatus.state > 1 && fm.searchStatus.query) {
                                    input.val(fm.searchStatus.query).select();
                                }
                            }
                        }, isopts.wait));
                    }
                });

            if (fm.UA.ltIE8) {
                input.on('keydown', function(e) {
                    if (e.keyCode === 229) {
                        input.data('imetm') && clearTimeout(input.data('imetm'));
                        input.data('composing', true);
                        input.data('imetm', setTimeout(function() {
                            input.removeData('composing');
                        }, 100));
                    }
                })
                    .on('keyup', function(e) {
                        input.data('imetm') && clearTimeout(input.data('imetm'));
                        if (input.data('composing')) {
                            e.keyCode === $.ui.keyCode.ENTER && input.trigger('compositionend');
                        } else {
                            input.trigger('input');
                        }
                    });
            }
        }

        $('<span class="ui-icon ui-icon-search" title="'+cmd.title+'"/>')
            .appendTo(button)
            .click(search);

        $('<span class="ui-icon ui-icon-close"/>')
            .appendTo(button)
            .click(abort);

        // wait when button will be added to DOM
        fm.bind('toolbarload', function(){
            var parent = button.parent();
            if (parent.length) {
                toolbar.prepend(button.show());
                parent.remove();
                // position icons for ie7
                if (fm.UA.ltIE7) {
                    var icon = button.children(fm.direction == 'ltr' ? '.ui-icon-close' : '.ui-icon-search');
                    icon.css({
                        right : '',
                        left  : parseInt(button.width())-icon.outerWidth(true)
                    });
                }
            }
        });

        fm
            .one('open', function() {
                opts = (fm.api < 2.1)? null : $('<div class="ui-front ui-widget ui-widget-content elfinder-button-menu ui-corner-all"/>')
                    .append(
                        $('<div class="buttonset"/>')
                            .append(
                                $('<input id="'+id('SearchFromCwd')+'" name="serchfrom" type="radio" checked="checked"/><label for="'+id('SearchFromCwd')+'">'+fm.i18n('btnCwd')+'</label>'),
                                $('<input id="'+id('SearchFromVol')+'" name="serchfrom" type="radio"/><label for="'+id('SearchFromVol')+'">'+fm.i18n('btnVolume')+'</label>'),
                                $('<input id="'+id('SearchFromAll')+'" name="serchfrom" type="radio"/><label for="'+id('SearchFromAll')+'">'+fm.i18n('btnAll')+'</label>')
                            ),
                        $('<div class="buttonset"/>')
                            .append(
                                $('<input id="'+id('SearchName')+'" name="serchcol" type="radio" checked="checked"/><label for="'+id('SearchName')+'">'+fm.i18n('btnFileName')+'</label>'),
                                $('<input id="'+id('SearchMime')+'" name="serchcol" type="radio"/><label for="'+id('SearchMime')+'">'+fm.i18n('btnMime')+'</label>'),
                                $('<input id="'+id('SearchMeta')+'" name="serchcol" type="radio"/><label for="'+id('SearchMeta')+'">'+fm.i18n('btnMeta')+'</label>')
                            )
                    )
                    .hide()
                    .appendTo(button);
                if (opts) {
                    opts.find('div.buttonset').buttonset();
                    $('#'+id('SearchFromAll')).next('label').attr('title', fm.i18n('searchTarget', fm.i18n('btnAll')));
                    $('#'+id('SearchMime')).next('label').attr('title', fm.i18n('searchMime'));
                    opts.on('mousedown', 'div.buttonset', function(e){
                        e.stopPropagation();
                        opts.data('infocus', true);
                    })
                        .on('click', 'input', function(e) {
                            e.stopPropagation();
                            $.trim(input.val()) && search();
                        });
                }
            })
            .select(function() {
                input.blur();
            })
            .bind('searchend', function() {
                input.val('');
            })
            .bind('open parents', function() {
                var dirs    = [],
                    volroot = fm.file(fm.root(fm.cwd().hash));

                if (volroot) {
                    $.each(fm.parents(fm.cwd().hash), function(i, hash) {
                        dirs.push(fm.file(hash).name);
                    });

                    $('#'+id('SearchFromCwd')).next('label').attr('title', fm.i18n('searchTarget', dirs.join(fm.option('separator'))));
                    $('#'+id('SearchFromVol')).next('label').attr('title', fm.i18n('searchTarget', volroot.name));
                }
            })
            .shortcut({
                pattern     : 'ctrl+f f3',
                description : cmd.title,
                callback    : function() {
                    input.select().focus();
                }
            });

    });
};