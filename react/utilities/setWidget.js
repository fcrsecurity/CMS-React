/* Help functions fo finding widget by ID and setting it data and config */
'use strict';
//Finding widget by ID and setting it data and config
const getObjects = (obj, key, val) => {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getObjects(obj[i], key, val));
        } else
        if (i == key && obj[i] == val || i == key && val == '') {
            objects.push(obj);
        } else if (obj[i] == val && key == ''){
            if (objects.lastIndexOf(obj) == -1){
                objects.push(obj);
            }
        }
    }
    return objects;
}
//checking if the window exists, because it server
let widgets = [];
export const setWidgetData = (data, conf) => {
    let d = JSON.parse(JSON.stringify(data));
    let c = JSON.parse(JSON.stringify(conf));
    let id = data.id;
    widgets.map((obj, i) => {
        if (id in obj) {
            widgets.splice(i,1)
        }
    })
    delete d.isLoggedIn;
    delete d.config;
    delete d.id;
    let w = {
            [id] :{
                'data': d,
                'config': c
                }
            }
    widgets.push(w)
    if(typeof window !== 'undefined') {
        window.widgets = widgets
    }
}