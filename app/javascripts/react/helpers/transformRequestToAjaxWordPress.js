export function transformRequestToAjaxWordPress(data, headers){
    var str = [];
    for(var p in data){
        if (data.hasOwnProperty(p) && data[p]) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(data[p]));
        }
    }
    return str.join("&");
}