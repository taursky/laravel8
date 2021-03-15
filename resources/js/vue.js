var uri = window.location.href.split('/');
uri = uri[3];
// console.log(uri.length);
if (uri.length === 0) {
    const sldr = new Vue({
        el: '#sldr',
    });
}
